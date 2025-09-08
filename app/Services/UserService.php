<?php
namespace App\Services;

use App\Models\User;
use Helpers\Hash;
use Helpers\Validator;

class UserService
{
    public function register(array $data): User
    {
        Validator::make($data, [
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function login(string $username, string $password): ?User
    {
        $user = User::where('username', $username)->first();
        if ($user && Hash::verify($password, $user->password)) {
            return $user;
        }

        return null;
    }
}
