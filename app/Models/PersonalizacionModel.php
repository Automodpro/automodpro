<?php
namespace App\Models;
use CodeIgniter\Model;

class PersonalizacionModel extends Model
{
    protected $table = 'personalizaciones';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario_id', 'color_carro', 'color_ruedas', 'color_vidrios', 'tipo_aleron', 'tipo_faros'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
