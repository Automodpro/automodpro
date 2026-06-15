<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (!$header) {
            $token = $request->getGet('token') ?? $request->getGet('Authorizacion');
            if ($token) {
                $request->user = $this->decodeToken($token);
                if ($request->user) return;
            }
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token requerido']);
        }

        $token = str_replace('Bearer ', '', $header);
        $request->user = $this->decodeToken($token);
        if (!$request->user) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token inválido']);
        }
    }

    private function decodeToken(string $token)
    {
        try {
            return JWT::decode($token, new Key(JWTConfig::$key, JWTConfig::$algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
