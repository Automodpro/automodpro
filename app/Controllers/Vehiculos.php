<?php
namespace App\Controllers;

use App\Models\VehiculoModel;

class Vehiculos extends BaseController
{
    public function index()
    {
        $model = new VehiculoModel();
        $model->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
            ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
            ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
            ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left');


        if (session('rol') === 'usuario') {
            $model->where('vehiculos.usuario_id', session('id'));
        }

        $data['vehiculos'] = $model->orderBy('vehiculos.id', 'DESC')->findAll();
        return view('vehiculos/index', $data);
    }

    public function crear()
    {
        // Para admin/mecánico: se requiere selector de propietario.
        // Para usuario: se asigna automáticamente.
        $rol = (string) (session('rol') ?? '');

        if ($rol !== 'usuario') {
            $usuarios = (new \App\Models\UsuarioModel())->findAll();
        } else {
            $usuarios = null;
        }

        // Siempre cargamos catálogos requeridos por el formulario.
        // (marcas/modelos) deben venir para que se puedan seleccionar marca_id/modelo_id.
        $db = db_connect();
        $marcas = $db->table('marcas')->select('id, nombre')->orderBy('id', 'ASC')->get()->getResultArray();
        $modelos = $db->table('modelos')->select('id, marca_id, nombre, tipo')->orderBy('marca_id', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();

        $payload = ['marcas' => $marcas, 'modelos' => $modelos];
        if ($usuarios) {
            $payload['usuarios'] = $usuarios;
        }

        return view('vehiculos/form', $payload);
    }

    public function guardar()
    {
        $model = new VehiculoModel();

        $rol = (string) (session('rol') ?? '');
        $usuarioIdPost = $this->request->getPost('usuario_id');

        // Regla: Usuario siempre asignado automáticamente.
        if ($rol === 'usuario') {
            $usuarioId = session('id');
        } else {
            // Admin/Mecánico deben enviar usuario_id (selector en el formulario).
            $usuarioId = ($usuarioIdPost !== null && $usuarioIdPost !== '') ? $usuarioIdPost : null;
        }

        // Si usuario_id queda vacío, no intentamos insertar (para evitar el error de validación).
        if ($usuarioId === null || $usuarioId === '') {
            return redirect()->back()->with('errores', ['Propietario (usuario_id) es obligatorio'])->withInput();
        }

        $data = [
            'usuario_id' => $usuarioId,
            'marca_id' => $this->request->getPost('marca_id'),
            'modelo_id' => $this->request->getPost('modelo_id'),
            // Se guarda el tipo derivado del modelo (ver modelo_id -> modelos.tipo)
            'tipo' => $this->request->getPost('tipo'),
            // BD: columna `año` (ñ)
            'año' => $this->request->getPost('anio'),
            'placa' => $this->request->getPost('placa'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/vehiculos')->with('success', 'Vehículo guardado');
        }
        return redirect()->back()->with('errores', $model->errors())->withInput();
    }

    public function editar($id)
    {
        $model = new VehiculoModel();
        $data['vehiculo'] = $model->find($id);

        if (empty($data['vehiculo'])) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado');
        }

        if (session('rol') === 'usuario' && ($data['vehiculo']['usuario_id'] ?? null) != session('id')) {
            return redirect()->to('/vehiculos')->with('error', 'No puedes editar este vehículo');
        }


        // Para compatibilidad con la vista, añadimos el nombre del propietario si existe.
        if (session('rol') !== 'usuario') {
            $usuarioModel = new \App\Models\UsuarioModel();
            $data['usuarios'] = $usuarioModel->findAll();

            if (!empty($data['vehiculo']['usuario_id'])) {
                $u = $usuarioModel->find($data['vehiculo']['usuario_id']);
                $data['vehiculo']['propietario'] = $u['nombre_usuario'] ?? null;
            }
        }

        // Cargar catálogos para selects (100% compatible con BD actual)
        // No dependemos de clases Marcas/Modelos porque podrían no existir.
        $db = db_connect();
        $data['marcas'] = $db->table('marcas')->select('id, nombre')->orderBy('id', 'ASC')->get()->getResultArray();
        $data['modelos'] = $db->table('modelos')->select('id, marca_id, nombre, tipo')->orderBy('marca_id', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();





        return view('vehiculos/form', $data);
    }

    public function actualizar($id)
    {
        $model = new VehiculoModel();
        $vehiculo = $model->find($id);

        if (session('rol') === 'usuario' && $vehiculo['usuario_id'] != session('id')) {
            return redirect()->to('/vehiculos')->with('error', 'No puedes editar este vehículo');
        }

        // Regla: Usuario asigna automáticamente (no se permite cambiar), Admin/Mecánico mantiene compatibilidad.
        // Como el modelo valida usuario_id en creación, aquí no lo actualizamos para evitar validaciones/errores.
        $data = [
            'marca_id' => $this->request->getPost('marca_id'),
            'modelo_id' => $this->request->getPost('modelo_id'),
            'tipo' => $this->request->getPost('tipo'),
            // BD: columna `año` (ñ)
            'año' => $this->request->getPost('anio'),
            'placa' => $this->request->getPost('placa'),
        ];

        $model->update($id, $data);
        return redirect()->to('/vehiculos')->with('success', 'Vehículo actualizado');
    }

    public function eliminar($id)
    {
        $model = new VehiculoModel();
        $vehiculo = $model->find($id);

        if (!$vehiculo) {
            return redirect()->to('/vehiculos')->with('error', 'Vehículo no encontrado');
        }

        if (session('rol') === 'usuario' && $vehiculo['usuario_id'] != session('id')) {
            return redirect()->to('/vehiculos')->with('error', 'No puedes eliminar este vehículo');
        }

        $model->delete($id);
        return redirect()->to('/vehiculos')->with('success', 'Vehículo eliminado');
    }

    public function tipos()
    {
        return $this->response->setJSON(['tipos' => ['Carro', 'Camioneta', 'Camión', 'Moto']]);
    }
}
