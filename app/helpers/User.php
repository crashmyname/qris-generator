<?php
namespace Helpers;

class User {
    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function __get($key) {
        return $this->data[$key] ?? null;
    }

    public static function Auth(){
        $user = \App\Models\User::query()
                ->where('uid','=',\Helpers\Session::user()->uid)
                ->first();
        return $user;
    }
}
