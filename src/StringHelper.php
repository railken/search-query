<?php

namespace Railken\SQ;

class StringHelper
{
    public function divideBy($string, $divider)
    {
        $in_phrase = false;
        $buffer = '';
        $r = [];

        foreach (str_split($string) as $char) {
            $char === '"' && $in_phrase = !$in_phrase;
            
            if (!$in_phrase && $char === $divider) {
                $r[] = $buffer;
                $buffer = '';
            }

            $char !== $divider && $buffer .= $char;
        }

        $r[] = $buffer;


        return $r;
    }
}
