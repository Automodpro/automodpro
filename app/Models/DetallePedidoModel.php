<?php
namespace App\Models;
use CodeIgniter\Model;

class DetallePedidoModel extends Model
{
    protected $table = 'detalles_pedido';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pedido_id', 'servicio_id', 'precio_unitario', 'cantidad', 'observaciones'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'pedido_id' => 'required|numeric',
        'servicio_id' => 'required|numeric',
        'precio_unitario' => 'required|numeric',
        'cantidad' => 'permit_empty|numeric',
    ];
}
