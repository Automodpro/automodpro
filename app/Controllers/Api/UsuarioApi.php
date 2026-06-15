<?php
namespace App\Controllers\Api;

use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class UsuarioApi extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $model = new UsuarioModel();
        $users = $model->findAll();
        $result = array_map([$this, 'formatUser'], $users);
        return $this->respond($result);
    }

    public function show($id = null)
    {
        $model = new UsuarioModel();
        $user = $model->find($id);
        if (!$user) {
            return $this->failNotFound('Usuario no encontrado');
        }
        return $this->respond($this->formatUser($user));
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->fail('Datos requeridos');
        }

        $model = new UsuarioModel();

        if (isset($data['contrasena'])) {
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        }

        if (!$model->insert($data)) {
            return $this->failValidationErrors($model->errors());
        }

        $user = $model->find($model->insertID());
        return $this->respondCreated($this->formatUser($user));
    }

    public function update($id = null)
    {
        $model = new UsuarioModel();
        $user = $model->find($id);
        if (!$user) {
            return $this->failNotFound('Usuario no encontrado');
        }

        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->fail('Datos requeridos');
        }

        if (isset($data['contrasena'])) {
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        }

        if (!$model->update($id, $data)) {
            return $this->failValidationErrors($model->errors());
        }

        $user = $model->find($id);
        return $this->respond($this->formatUser($user));
    }

    public function delete($id = null)
    {
        $model = new UsuarioModel();
        $user = $model->find($id);
        if (!$user) {
            return $this->failNotFound('Usuario no encontrado');
        }

        $model->delete($id);
        return $this->respondDeleted(['message' => 'Usuario eliminado']);
    }

    private function formatUser(array $user): array
    {
        unset($user['contrasena']);
        $user['rol'] = $this->getRoleSlug($user['rol_id']);
        return $user;
    }

    private function getRoleSlug(int $rolId): string
    {
        $roles = [1 => 'admin', 2 => 'mecanico', 3 => 'usuario'];
        return $roles[$rolId] ?? 'usuario';
    }
}
