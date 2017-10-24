<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions;

class QueryConverter
{

    /**
     * Query string
     *
     * @var string
     */
    protected $query;

    /**
     * Buffer string
     *
     * @var string
     */
    protected $buffer = '';
    
    /**
     * Is the current char in a string?
     *
     * @var bool
     */
    protected $in_phrase = false;
    
    /**
     * Is the current char in a filter?
     *
     * @var bool
     */
    protected $in_filter = false;

    /**
     * Is the current char in filter brackets ?
     *
     * @var bool
     */
    protected $in_filter_parameters;

    /**
     * This char has been escaped?
     *
     * @var bool
     */
    protected $escape = false;
    
    /**
     * Current node position
     *
     * @var FilterSupportNode
     */
    protected $node;

    /**
     * Construct
     *
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = "(".$query.")";
    }

    /**
     * Convert the string $query into a tree object
     *
     * @return Object
     */
    public function convert()
    {
        try {
            $this->node = new QuerySupportNode();

            foreach (str_split($this->query) as $char) {
                $char === Token::TOKEN_ESCAPE && $this->parseStatusEscaping($char);
                $char === Token::TOKEN_PHRASE_DELIMETER && $this->parseStatusPhrase($char);
                $char === Token::TOKEN_FILTER_DELIMETER && !$this->in_phrase && $this->in_filter = true;
                $char === Token::TOKEN_WHITESPACE && $this->in_filter && !$this->in_phrase && !$this->in_filter_parameters && $this->in_filter = false;

                $char === Token::TOKEN_FILTER_DELIMETER && !$this->in_phrase && $this->parseWhiteSpace();


                !$this->in_filter && !$this->in_phrase && $char === Token::TOKEN_OPENING_PARENTHESIS && $this->parseOpeningBracket($char);
                !$this->in_filter && !$this->in_phrase && $char === Token::TOKEN_CLOSING_PARENTHESIS && $this->parseClosingBracket($char);
                !$this->in_filter && !$this->in_phrase && $char === Token::TOKEN_WHITESPACE && $this->parseWhiteSpace($char);

                !$this->in_filter && !in_array($char, [Token::TOKEN_OPENING_PARENTHESIS, Token::TOKEN_CLOSING_PARENTHESIS, Token::TOKEN_WHITESPACE]) && $this->concatBufferString($char);


                $this->in_phrase && $char === Token::TOKEN_WHITESPACE && $this->concatBufferString($char);

                if ($this->in_filter && !$this->in_phrase) {
                    // Closing one before opening should throw an exception
                    if ($char === Token::TOKEN_CLOSING_PARENTHESIS && !$this->in_filter_parameters) {
                        throw new Exceptions\QuerySyntaxException($this->query);
                    }

                    // Opening one before closing should throw an exception
                    if ($char === Token::TOKEN_OPENING_PARENTHESIS && $this->in_filter_parameters) {
                        throw new Exceptions\QuerySyntaxException($this->query);
                    }

                    $char === Token::TOKEN_OPENING_PARENTHESIS && $this->in_filter_parameters = true;
                    $char === Token::TOKEN_CLOSING_PARENTHESIS && $this->in_filter_parameters = false;

                }
                
                # Concat always when "in_filter"
                $this->in_filter && $char !== Token::TOKEN_WHITESPACE && $this->concatBufferString($char);

                $char !== Token::TOKEN_ESCAPE && $this->escape = false;
            }

            # Ouch. Flag "in string" is still true?
            if ($this->in_phrase) {
                throw new Exceptions\QuerySyntaxException($this->query);
            }

            # Ouch. Flag "in filter" is still true?
            if ($this->in_filter) {
                throw new Exceptions\QuerySyntaxException($this->query);
            }

            return (new QueryNodeBridge())->newBySupportNode($this->node->parts[0]);
        } catch (\Exception $e) {
            // throw $e;
            throw new Exceptions\QuerySyntaxException($this->query);
        }
    }

    /**
     * Concat current buffer with char
     *
     * @param string $char
     *
     * @return void
     */
    public function concatBufferString($char)
    {
        $this->buffer .= $char;
    }

    /**
     * Reset buffer
     *
     * @return void
     */
    public function resetBuffer()
    {
        $this->buffer = "";
    }


    /**
     * Invert escape
     *
     * @return void
     */
    public function parseStatusEscaping()
    {
        $this->escape = !$this->escape;
    }

    /**
     * Detect "in string" flag
     *
     * @return void
     */
    public function parseStatusPhrase()
    {
        if (!$this->escape) {
            $this->in_phrase = !$this->in_phrase;
        }
    }


    /**
     * Add current buffer to parts
     *
     * @return void
     */
    public function addPart()
    {
        if (!empty($this->buffer)) {
            $this->node->parts[] = $this->buffer;
        }
    }

    /**
     * Parse opening bracket
     *
     * @return void
     */
    public function parseOpeningBracket()
    {
        $this->addPart();

        $new = new QuerySupportNode();
        $new->setParent($this->node);
        $this->node = $new;
        $this->resetBuffer();
    }

    /**
     * Parse white space
     *
     * @return void
     */
    public function parseWhiteSpace()
    {
        $this->addPart();
        $this->resetBuffer();
    }

    /**
     * Parse closing bracket
     *
     * @return void
     */
    public function parseClosingBracket()
    {
        $this->addPart();
        $this->node->getParent()->parts[] = $this->node;
        $this->node = $this->node->getParent();
        $this->resetBuffer();
    }
}
