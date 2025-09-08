<?php
namespace Middlewares;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Helpers\View;

class Middleware
{
    public function handle()
    {
        if (!$this->checkToken()) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'Token tidak valid atau tidak ditemukan']);
            exit();
        }
    }

    public function checkToken()
    {
        $headers = getallheaders();

        // Periksa keberadaan Authorization Header
        if (!isset($headers['Authorization'])) {
            header('Content-Type: application/json');
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'Authorization token tidak ditemukan']);
            return false;
        }

        // Ambil Authorization token
        $authHeader = $headers['Authorization'];
        $token = substr($authHeader, 7); // Mengambil token setelah 'Bearer '

        // Validasi token berdasarkan panjang (JWT atau Bearer)
        if (strlen($token) > 128) {
            if (!$this->validateJWT($token)) {
                return false;
            }
        } else {
            if (!$this->validateBearer($token)) {
                return false;
            }
        }

        // Periksa keberadaan api_token di header
        if (!isset($headers['api_key'])) {
            header('Content-Type: application/json');
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'API Token tidak ditemukan']);
            return false;
        }

        // Validasi api_token
        $apiToken = $headers['api_key'];
        if (!$this->validateApiToken($apiToken)) {
            return false;
        }

        return true;
    }

    // Fungsi untuk memvalidasi Bearer Token biasa
    private function validateBearer($token)
    {
        if (!isset($_SESSION['token']) || $_SESSION['token'] !== $token) {
            header('Content-Type: application/json');
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'Bearer Token tidak valid']);
            return false;
        }

        return true;
    }

    // Fungsi untuk memvalidasi JWT
    private function validateJWT($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $_SESSION['user'] = $decoded; // Simpan informasi user dari JWT
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'JWT Token tidak valid', 'message' => $e->getMessage()]);
            return false;
        }

        return true;
    }

    // Fungsi untuk memvalidasi api_token
    private function validateApiToken($apiToken)
    {
        $user = User::query()->where('api_key','=',$apiToken)->first();
        // Validasi API token, misalnya mencocokkan dengan token yang disimpan di database
        if ($apiToken !== $user->api_key) { // Contoh validasi dengan .env
            header('Content-Type: application/json');
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(['error' => 'API Token tidak valid']);
            return false;
        }

        return true;
    }
}
