<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Evita que el navegador cachee páginas protegidas.
        // Si el usuario no está autenticado, redirige.
        if (!session('logueado')) {
            return redirect()->to('/')->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
