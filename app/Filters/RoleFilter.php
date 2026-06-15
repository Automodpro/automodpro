<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsuarioModel;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $rolMinimo = $arguments[0] ?? 'usuario';
        $usuario = session('rol');

        $model = new UsuarioModel();
        $nivelActual = $model->getNivel($usuario);
        $nivelRequerido = $model->getNivel($rolMinimo);

        if ($nivelActual < $nivelRequerido) {
            return redirect()->to('/dashboard')->with('error', 'No tienes permisos');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
