<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Misc
{
     /**
     * Calculates an MD5 hash from a sorted array
     *
     * @param array $array The array whose key-value pairs will be sorted and hashed.
     * @return string The generated MD5 hash.
     */
    public static function CalculateHash($array)
    {
        ksort($array);

        $appendString = http_build_query($array);

        $calculateHash = hash('MD5', $appendString);

        return $calculateHash;
    }
}

?>