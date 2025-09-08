<?php

namespace Helpers;

use App\Models\User;
use Helpers\Session;
use Middlewares\SessionMiddleware;
use Middlewares\Throttle;

class Auth
{
    public static function attempt(array $credentials): bool
    {
        try {
            $throttleEnabled = config('auth.throttle.enabled', false);
            $throttleKey = 'login_attempt_' . md5($credentials['identifier'] . $_SERVER['REMOTE_ADDR']);

            if ($throttleEnabled && Throttle::tooManyAttempts($throttleKey)) {
                Session::flash('error', 'Terlalu banyak percobaan login. Coba lagi nanti.');
                return false;
            }

            $fields = config('auth.login_fields', ['username']);
            $user = null;

            foreach ($fields as $field) {
                $user = User::query()->where($field, '=', $credentials['identifier'])->first();
                if ($user) break;
            }

            if (!$user) {
                if ($throttleEnabled) Throttle::increment($throttleKey);
                return false;
            }

            $passwordAlgo = config('auth.password_hash', 'bcrypt');
            $isValid = $passwordAlgo === 'bcrypt'
                ? password_verify($credentials['password'], $user->password)
                : false;

            if ($isValid) {
                if ($throttleEnabled) Throttle::clear($throttleKey);

                Session::set(config('auth.session_key', 'user'), $user->toArray());

                if (config('auth.regenerate_session', true)) {
                    SessionMiddleware::regenerate();
                }

                if (config('auth.remember_me') && ($credentials['remember'] ?? false)) {
                    $rememberDays = config('auth.remember_days', 7);
                    setcookie('remember_user', $user->id, time() + ($rememberDays * 86400), "/", "", false, true);
                }

                return true;
            }

            if ($throttleEnabled) Throttle::increment($throttleKey);
            return false;
        } catch (\Throwable $e) {
            error_log("Auth Error: " . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return false;
        }
    }

    public static function user(): ?User
    {
        $sessionKey = config('auth.session_key', 'user');
        $data = Session::get($sessionKey);

        if ($data) {
            return new User($data);
        }

        return null;
    }

    public static function check(): bool
    {
        // Sudah login via session?
        if (self::user()) return true;

        // Coba ambil dari cookie
        if (isset($_COOKIE['remember_user'])) {
            $user = User::find($_COOKIE['remember_user']);
            if ($user) {
                Session::set(config('auth.session_key', 'user'), $user->toArray());
                return true;
            }
        }

        return false;
    }

    public static function role(string|array $role): bool
    {
        $user = self::user();
        if (!$user || !isset($user->role)) return false;

        return is_array($role)
            ? in_array($user->role, $role)
            : $user->role === $role;
    }

    public static function id(): ?int
    {
        return self::user()?->id;
    }

    public static function logout(): void
    {
        $sessionKey = config('auth.session_key', 'user');
        Session::remove($sessionKey);

        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, "/");
        }

        if (config('auth.regenerate_session', true)) {
            SessionMiddleware::regenerate();
        }

        Session::destroy();
    }
}
