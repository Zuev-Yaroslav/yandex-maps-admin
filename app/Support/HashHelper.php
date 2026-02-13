<?php

namespace App\Support;

class HashHelper
{
    public static function djb2Hash(string $str): int
    {
        $n = 5381;
        for ($r = 0; $r < strlen($str); $r++) {
            $n = (($n << 5) + $n) ^ ord($str[$r]);
            $n = $n & 0xFFFFFFFF; // Эмуляция 32-битного unsigned int
        }

        return $n;
    }
}
