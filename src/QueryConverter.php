<?php

namespace Railken\SQ;

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
    protected $in_string = false;
    
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
                $char == "\\" && $this->parseStatusEscaping($char);
                $char == "\"" && $this->parseStatusString($char);


                !$this->in_string && $char === "(" && $this->parseOpeningBracket($char);
                !$this->in_string && $char === ")" && $this->parseClosingBracket($char);
                !$this->in_string && $char === " " && $this->parseWhiteSpace($char);

                !in_array($char, ["(", ")", " "]) && $this->concatBufferString($char);
                $this->in_string && $char === " " && $this->concatBufferString($char);
                

                $char != "\\" && $this->escape = false;
            }

            # Ouch. Flag "in string" is still true?
            if ($this->in_string) {
                throw new Exceptions\QuerySyntaxException($this->query);
            }

            return (new QueryNodeBridge())->newBySupportNode($this->node->parts[0]);
        } catch (\Exception $e) {
            throw $e;
            //throw new Exceptions\FilterSyntaxException($this->query);
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
    public function parseStatusString()
    {
        if (!$this->escape) {
            $this->in_string = !$this->in_string;
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
