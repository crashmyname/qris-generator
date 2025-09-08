<?php

namespace Helpers;

class Char {

    public static function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function random(int $length = 16): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $str;
    }

    public static function slug(string $text, string $separator = '-'): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/i', $separator, $text);
        return trim($text, $separator);
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    public static function camel(string $value): string
    {
        $value = str_replace(['-', '_'], ' ', $value);
        $value = ucwords($value);
        $value = lcfirst(str_replace(' ', '', $value));
        return $value;
    }

    public static function snake(string $value): string
    {
        $value = preg_replace('/([a-z])([A-Z])/', '$1_$2', $value);
        return strtolower(str_replace([' ', '-'], '_', $value));
    }

    public static function limit(string $text, int $limit = 100, string $end = '...'): string
    {
        return strlen($text) <= $limit ? $text : substr($text, 0, $limit) . $end;
    }

    public static function clean(string $text): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $text);
    }

    public static function upperWords(string $text): string
    {
        return ucwords(strtolower($text));
    }

    public static function initials(string $text): string
    {
        $parts = preg_split('/[\s\-]+/', trim($text));
        $initials = array_map(fn($word) => strtoupper($word[0]), $parts);
        return implode('', $initials);
    }
}