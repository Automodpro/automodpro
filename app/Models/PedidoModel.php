<?php
namespace App\Models;
use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario_id', 'vehiculo_id', 'estado', 'total'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'usuario_id' => 'required|numeric',
        'vehiculo_id' => 'required|numeric',
        'estado' => 'permit_empty|in_list[pendiente,aprobado,en_proceso,completado,cancelado]',
        'total' => 'permit_empty|numeric',
    ];
}
