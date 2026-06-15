<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
class UsuarioController extends BaseController
{
// LISTAR
public function index()
{
if (session('rol') != 'admin') {
return redirect()->to('/dashboard');
}
$modelo = new UsuarioModel();
$datos['usuarios'] = $modelo->findAll();

return view('usuarios', $datos);
}
// EDITAR
public function editar($id)
{
$modelo = new UsuarioModel();
$usuario = $modelo->find($id);
return view(
'editar_usuario',
['usuario' => $usuario]
);
}
// ACTUALIZAR
public function actualizar($id)
{
$modelo = new UsuarioModel();
$datos = [
'nombre' => $this->request->getPost('nombre'),
'correo' => $this->request->getPost('correo'),
'rol' => $this->request->getPost('rol')
];
$modelo->update($id, $datos);
return redirect()->to('/usuarios');
}
// ELIMINAR
public function eliminar($id)
{
$modelo = new UsuarioModel();

$modelo->delete($id);
return redirect()->to('/usuarios');
}
}