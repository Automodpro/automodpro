<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_usuario', 'correo', 'contrasena', 'nombre_completo', 'telefono', 'avatar_url', 'rol_id', 'activo', 'email_verified', 'verification_token'];

    // Evita que CodeIgniter intente usar campos inexistentes por default
    protected $selectFields = ['id', 'nombre_usuario', 'correo', 'contrasena', 'rol_id', 'activo'];

    // En la BD la tabla usuarios usa: creado_en y ultimo_acceso.
    // No existen columnas created_at/updated_at, por eso desactivamos timestamps.
    protected $useTimestamps = false;


    protected $validationRules = [
        'nombre_usuario' => 'required|min_length[3]|max_length[100]',
        'correo' => 'required|valid_email|is_unique[usuarios.correo]',
        'contrasena' => 'required|min_length[6]',
        'rol_id' => 'required|is_natural_no_zero',
    ];


    protected $validationMessages = [
        'nombre_usuario' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'Mínimo 3 caracteres',
        ],
        'correo' => [
            'required' => 'El correo es obligatorio',
            'valid_email' => 'Correo inválido',
            'is_unique' => 'El correo ya existe',
        ],
        'contrasena' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'Mínimo 6 caracteres',
        ],
        'rol_id' => [
            'required' => 'El rol es obligatorio',
            'is_natural_no_zero' => 'Rol inválido',
        ],
    ];


    public function getNivel($rol)
    {
        $niveles = ['admin' => 80, 'mecanico' => 50, 'usuario' => 10];
        return $niveles[$rol] ?? 0;
    }
}