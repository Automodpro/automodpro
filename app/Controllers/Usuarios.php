<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    public function index()
    {
        $model = new UsuarioModel();

        // SI ES ADMIN y no solicita ver su propio perfil explícitamente
        if (session('rol_id') == 1 && $this->request->getGet('view') !== 'perfil') {
            $data['usuarios'] = $model->select('usuarios.id, usuarios.nombre_usuario AS nombre, usuarios.correo, usuarios.contrasena, usuarios.creado_en, usuarios.rol_id, roles.nombre AS rol')
                ->join('roles', 'roles.id = usuarios.rol_id', 'left')
                ->findAll();
            return view('Tusuarios/index', $data);
        }

        // SI NO ES ADMIN: Comportamiento actual (Mi Perfil)
        $data['usuarios'] = $model->select('usuarios.id, usuarios.nombre_usuario AS nombre, usuarios.correo, usuarios.creado_en, usuarios.rol_id, roles.nombre AS rol')
            ->join('roles', 'roles.id = usuarios.rol_id', 'left')
            ->where('usuarios.id', session('id'))
            ->findAll();

        return view('usuarios/index', $data);
    }

    public function crear()
    {
        if (session('rol_id') != 1) {
            return redirect()->to('/usuarios');
        }

        $db = \Config\Database::connect();
        $data['roles'] = $db->table('roles')->orderBy('nombre', 'ASC')->get()->getResultArray();
        $data['usuario'] = null; // Para que el form sepa que es creación
        $data['titulo'] = 'Crear nuevo usuario';
        $data['actionUrl'] = base_url('usuarios/guardar');

        return view('Tusuarios/form', $data);
    }

    public function guardar()
    {
        if (session('rol_id') != 1) {
            return redirect()->to('/usuarios');
        }

        $nombre = trim((string)$this->request->getPost('nombre'));
        $correo = trim((string)$this->request->getPost('correo'));
        $rol_id = (int)$this->request->getPost('rol_id');
        $pass   = (string)$this->request->getPost('contrasena');

        if ($nombre === '' || $correo === '' || $pass === '' || $rol_id === 0) {
            return redirect()->back()->withInput()->with('error', 'Todos los campos son obligatorios');
        }

        if (mb_strlen($pass) < 6) {
            return redirect()->back()->withInput()->with('error', 'La contraseña debe tener mínimo 6 caracteres');
        }

        $model = new UsuarioModel();
        
        // Verificar si el correo ya existe
        if ($model->where('correo', $correo)->first()) {
            return redirect()->back()->withInput()->with('error', 'El correo ya está registrado');
        }

        $data = [
            'nombre_usuario' => $nombre,
            'correo'         => $correo,
            'rol_id'         => $rol_id,
            'contrasena'     => password_hash($pass, PASSWORD_DEFAULT),
            'activo'         => 1
        ];

        $model->insert($data);
        return redirect()->to('/usuarios')->with('success', 'Usuario creado correctamente');
    }

    public function editar($id)
    {
        $model = new UsuarioModel();
        $db = \Config\Database::connect();

        // Lógica de ADMIN: Puede editar otros usuarios usando la vista Tusuarios
        if (session('rol_id') == 1 && $id != session('id')) {
            $usuario = $model->find($id);

            if (!$usuario) {
                return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado');
            }

            $data['usuario'] = $usuario;
            $data['roles'] = $db->table('roles')->orderBy('nombre', 'ASC')->get()->getResultArray();
            $data['titulo'] = 'Editar usuario: ' . $usuario['nombre_usuario'];
            $data['actionUrl'] = base_url('usuarios/actualizar/' . $id);

            return view('Tusuarios/form', $data);
        }

        // Lógica PERFIL (Admin o Usuario): Usa la vista original usuarios/form
        if ($id != session('id')) {
            return redirect()->to('/usuarios')->with('error', 'Solo puedes editar tu propio perfil');
        }

        // Usuario actual con JOIN a roles (para tener el nombre del rol)
        $usuario = $model->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, usuarios.rol_id, roles.nombre AS rol')
            ->join('roles', 'roles.id = usuarios.rol_id', 'left')
            ->where('usuarios.id', $id)
            ->first();

        // Lista de roles para el select
        $roles = $db->table('roles')
            ->select(['id', 'nombre'])
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();

        $data['usuario'] = $usuario;
        $data['roles'] = $roles;

        return view('usuarios/form', $data);
    }

    public function actualizar($id)
    {
        // Lógica de ADMIN gestionando a otros
        if (session('rol_id') == 1 && $id != session('id')) {
            $model = new UsuarioModel();
            $usuarioActual = $model->find($id);
            
            if (!$usuarioActual) {
                return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado');
            }

            $nombre = trim((string)$this->request->getPost('nombre'));
            $rol_id = (int)$this->request->getPost('rol_id');
            $nueva_pass = (string)$this->request->getPost('contrasena');

            if ($nombre === '' || $rol_id === 0) {
                return redirect()->back()->withInput()->with('error', 'Nombre y Rol son obligatorios');
            }

            $data = [
                'nombre_usuario' => $nombre,
                'rol_id'         => $rol_id,
            ];

            // La contraseña es opcional en edición para el admin
            if ($nueva_pass !== '') {
                if (mb_strlen($nueva_pass) < 6) {
                    return redirect()->back()->withInput()->with('error', 'La nueva contraseña debe tener mínimo 6 caracteres');
                }
                $data['contrasena'] = password_hash($nueva_pass, PASSWORD_DEFAULT);
            }

            $model->update($id, $data);
            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado por el administrador');
        }

        // Lógica PERFIL: Actualización con validación de seguridad (Original de usuarios/*)
        if ($id != session('id')) {
            return redirect()->to('/usuarios')->with('error', 'Solo puedes editar tu propio perfil');
        }

        $db = \Config\Database::connect();

        $usuarioActual = $db->table('usuarios')
            ->select(['id', 'nombre_usuario', 'correo', 'rol_id', 'contrasena'])
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$usuarioActual) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado');
        }

        // -----------------------------
        // Bloque Datos Básicos
        // -----------------------------
        $nombre = trim((string)$this->request->getPost('nombre'));
        if ($nombre === '' || mb_strlen($nombre) < 3) {
            return redirect()->back()->withInput()->with('error', 'Nombre inválido');
        }

        $rol_id_actual = (int)($usuarioActual['rol_id'] ?? 0);
        $rol_id_post = (int)($this->request->getPost('rol_id') ?? $rol_id_actual);
        $rol_id = (session('rol_id') == 1) ? $rol_id_post : $rol_id_actual;

        $data = [
            'nombre_usuario' => $nombre,
            'rol_id' => $rol_id,
        ];

        // -----------------------------
        // Lectura inputs seguridad
        // -----------------------------
        $nuevo_correo = trim((string)$this->request->getPost('nuevo_correo'));
        $confirmar_nuevo_correo = trim((string)$this->request->getPost('confirmar_nuevo_correo'));

        $password_actual = (string)$this->request->getPost('contraseña_actual');
        $nueva_password = (string)$this->request->getPost('nueva_contraseña');
        $confirmar_password = (string)$this->request->getPost('confirmar_nueva_contraseña');

        $correoActual = (string)($usuarioActual['correo'] ?? '');
        $hashActual = (string)($usuarioActual['contrasena'] ?? '');

        $quiereCambiarCorreo = ($nuevo_correo !== '' || $confirmar_nuevo_correo !== '');
        $quiereCambiarPassword = ($nueva_password !== '' || $confirmar_password !== '');

        // -----------------------------
        // Validación de seguridad SOLO si hay campos sensibles con contenido
        // -----------------------------
        $requiereValidarPasswordActual = ($quiereCambiarCorreo || $quiereCambiarPassword);
        if ($requiereValidarPasswordActual) {
            if ($password_actual === '') {
                return redirect()->back()->withInput()->with('error', 'Debes ingresar tu contraseña actual');
            }
            if ($hashActual === '' || !password_verify($password_actual, $hashActual)) {
                return redirect()->back()->withInput()->with('error', 'Contraseña actual incorrecta');
            }
        }

        // -----------------------------
        // Persistencia: Correo
        // -----------------------------
        if ($quiereCambiarCorreo) {
            if ($nuevo_correo === '' || $confirmar_nuevo_correo === '') {
                return redirect()->back()->withInput()->with('error', 'Completa nuevo correo y confirmación');
            }
            if ($nuevo_correo !== $confirmar_nuevo_correo) {
                return redirect()->back()->withInput()->with('error', 'Los correos no coinciden');
            }
            if (!filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('error', 'Correo inválido');
            }

            if ($nuevo_correo !== $correoActual) {
                $data['correo'] = $nuevo_correo;
            }
        }

        // -----------------------------
        // Persistencia: Contraseña
        // -----------------------------
        if ($quiereCambiarPassword) {
            if ($nueva_password === '' || $confirmar_password === '' || $nueva_password !== $confirmar_password) {
                return redirect()->back()->withInput()->with('error', 'Las contraseñas nuevas no coinciden');
            }
            if (mb_strlen($nueva_password) < 6) {
                return redirect()->back()->withInput()->with('error', 'La nueva contraseña debe tener mínimo 6 caracteres');
            }

            $data['contrasena'] = password_hash($nueva_password, PASSWORD_DEFAULT);
        }

        $model = new UsuarioModel();
        $model->update($id, $data);

        return redirect()->to('/usuarios')->with('success', 'Perfil actualizado');
    }

    public function eliminar($id)
    {
        if (session('rol_id') != 1) {
            return redirect()->to('/usuarios')->with('error', 'Solo el administrador puede eliminar usuarios');
        }

        if ($id == session('id')) {
            return redirect()->to('/usuarios')->with('error', 'No puedes eliminarte a ti mismo');
        }

        $model = new UsuarioModel();
        $model->delete($id);
        return redirect()->to('/usuarios')->with('success', 'Usuario eliminado');
    }
}
