<?php

namespace Whitewashing\Util;

class String
{
    /**
     * @param  string $string
     * @return string
     */
    static public function slugize($string)
    {
        $result = strtolower($string);

        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        $result = trim(preg_replace("/\s+/", " ", $result));
        $result = trim(substr($result, 0, 64));
        $result = preg_replace("/\s/", "-", $result);

        return $result;
    }
}