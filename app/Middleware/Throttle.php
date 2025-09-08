<?php

namespace Middlewares;

use Helpers\Session;

class Throttle {
    public static function tooManyAttempts($key): bool {
        $data = Session::get($key);
        if (!$data) return false;

        [$attempts, $expiresAt] = $data;
        if (time() > $expiresAt) {
            Session::remove($key);
            return false;
        }

        $max = config('auth.throttle.max_attempts', 5);
        return $attempts >= $max;
    }

    public static function increment($key): void {
        $data = Session::get($key);
        $decay = config('auth.throttle.decay_minutes', 1) * 60;

        if (!$data) {
            Session::set($key, [1, time() + $decay]);
        } else {
            [$attempts, $expiresAt] = $data;
            Session::set($key, [$attempts + 1, $expiresAt]);
        }
    }

    public static function clear($key): void {
        Session::remove($key);
    }
}
