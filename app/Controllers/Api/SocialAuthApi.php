<?php
namespace App\Controllers\Api;

use App\Models\UsuarioModel;
use App\Models\AuthProviderModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Config\JWT as JWTConfig;

class SocialAuthApi extends ResourceController
{
    protected $format = 'json';

    public function google()
    {
        return $this->verifyProvider('google', function ($data) {
            $token = $data['id_token'] ?? $data['access_token'] ?? null;
            if (!$token) return null;

            $response = $this->httpGet('https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($token));
            if (!$response || isset($response['error'])) return null;

            return [
                'provider_id' => $response['sub'],
                'email' => $response['email'] ?? null,
                'name' => $response['name'] ?? $response['given_name'] ?? 'Usuario Google',
                'avatar' => $response['picture'] ?? null,
            ];
        });
    }

    public function facebook()
    {
        return $this->verifyProvider('facebook', function ($data) {
            $token = $data['access_token'] ?? null;
            if (!$token) return null;

            $response = $this->httpGet('https://graph.facebook.com/me?access_token=' . urlencode($token) . '&fields=id,name,email,picture');
            if (!$response || isset($response['error'])) return null;

            return [
                'provider_id' => $response['id'],
                'email' => $response['email'] ?? null,
                'name' => $response['name'] ?? 'Usuario Facebook',
                'avatar' => $response['picture']['data']['url'] ?? null,
            ];
        });
    }

    public function github()
    {
        return $this->verifyProvider('github', function ($data) {
            $token = $data['access_token'] ?? null;
            if (!$token) return null;

            $response = $this->httpGet('https://api.github.com/user', [
                'Authorization: Bearer ' . $token,
                'User-Agent: AutoModPro',
            ]);
            if (!$response || isset($response['error'])) return null;

            $email = $response['email'] ?? null;
            if (!$email) {
                $emails = $this->httpGet('https://api.github.com/user/emails', [
                    'Authorization: Bearer ' . $token,
                    'User-Agent: AutoModPro',
                ]);
                if (is_array($emails)) {
                    $primary = current(array_filter($emails, fn($e) => $e['primary']));
                    $email = $primary['email'] ?? null;
                }
            }

            return [
                'provider_id' => (string)$response['id'],
                'email' => $email,
                'name' => $response['name'] ?? $response['login'] ?? 'Usuario GitHub',
                'avatar' => $response['avatar_url'] ?? null,
            ];
        });
    }

    private function verifyProvider(string $provider, callable $fetchUser)
    {
        $data = $this->request->getJSON(true);
        if (!$data || !isset($data['access_token']) && !isset($data['id_token'])) {
            return $this->fail('Token de acceso requerido');
        }

        $userData = $fetchUser($data);
        if (!$userData) {
            return $this->failUnauthorized('Token de ' . ucfirst($provider) . ' inválido');
        }

        $usuarioModel = new UsuarioModel();
        $authProviderModel = new AuthProviderModel();

        // Buscar si ya existe vinculación con este proveedor
        $link = $authProviderModel
            ->where('provider', $provider)
            ->where('provider_id', $userData['provider_id'])
            ->first();

        $usuario = null;

        if ($link) {
            // Ya existe vinculación → obtener usuario
            $usuario = $usuarioModel->find($link['user_id']);
        } elseif ($userData['email']) {
            // No hay vinculación pero hay email → buscar usuario por email
            $usuario = $usuarioModel->where('correo', $userData['email'])->first();

            if ($usuario) {
                // Vincular provider al usuario existente
                $authProviderModel->insert([
                    'user_id' => $usuario['id'],
                    'provider' => $provider,
                    'provider_id' => $userData['provider_id'],
                ]);
            }
        }

        if (!$usuario) {
            // No existe usuario → crear uno nuevo
            $username = explode('@', $userData['email'] ?? $provider . '_' . $userData['provider_id'])[0];
            $username = substr(preg_replace('/[^a-zA-Z0-9_]/', '_', $username), 0, 55);

            // Asegurar username único
            $base = $username;
            $counter = 1;
            while ($usuarioModel->where('nombre_usuario', $username)->first()) {
                $username = $base . '_' . $counter++;
                if (strlen($username) > 60) $username = substr($username, 0, 57) . '_' . $counter;
            }

            $usuarioModel->insert([
                'nombre_usuario' => $username,
                'correo' => $userData['email'] ?? $username . '@' . $provider . '.com',
                'contrasena' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'nombre_completo' => $userData['name'],
                'avatar_url' => $userData['avatar'],
                'rol_id' => 3,
                'activo' => 1,
            ]);

            $usuario = $usuarioModel->find($usuarioModel->insertID());

            // Vincular provider al nuevo usuario
            $authProviderModel->insert([
                'user_id' => $usuario['id'],
                'provider' => $provider,
                'provider_id' => $userData['provider_id'],
            ]);
        }

        // Generar JWT
        $payload = [
            'iat' => time(),
            'exp' => time() + JWTConfig::$expireTime,
            'uid' => $usuario['id'],
            'nombre' => $usuario['nombre_completo'] ?: $usuario['nombre_usuario'],
            'rol' => $this->getRoleSlug($usuario['rol_id']),
        ];

        $token = JWT::encode($payload, JWTConfig::$key, JWTConfig::$algorithm);

        return $this->respond([
            'token' => $token,
            'usuario' => [
                'id' => $usuario['id'],
                'nombre_usuario' => $usuario['nombre_usuario'],
                'nombre_completo' => $usuario['nombre_completo'],
                'correo' => $usuario['correo'],
                'avatar_url' => $usuario['avatar_url'],
            ],
        ]);
    }

    private function httpGet(string $url, array $headers = [])
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => array_merge(['Accept: application/json'], $headers),
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) return null;

        return json_decode($response, true);
    }

    private function getRoleSlug(int $rolId): string
    {
        $roles = [1 => 'admin', 2 => 'mecanico', 3 => 'usuario'];
        return $roles[$rolId] ?? 'usuario';
    }
}
