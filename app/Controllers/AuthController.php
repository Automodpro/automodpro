<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
class AuthController extends BaseController
{
// LOGIN
public function index()
{
return view('login');
}
// Nota: AuthController es una implementación antigua.
// Las rutas reales de login/registro usan app/Controllers/Auth.php (Routes.php).
// Se deja este controlador para no romper referencias, pero evita ejecutar lógica con columnas inexistentes.

public function registrar()
{
    return redirect()->to('/auth/register');
}

public function guardar()
{
    return redirect()->to('/auth/register');
}

public function login()
{
    return redirect()->to('/');
}

public function logout()
{
    return redirect()->to('/logout');
}

}