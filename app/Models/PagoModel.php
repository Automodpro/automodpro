<?php
namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pedido_id',
        'monto',
        'metodo_pago',
        'referencia',
        'estado',
        'fecha_pago',
        'creado_en',
    ];

    protected $useTimestamps = false;

    // CI4 valida en Model::insert() solo si $validationRules está configurado.
    // Aquí nos limitamos a lo estrictamente requerido por sql/automod_pro.sql.
    // NOTA: en Pagos::guardar() se envía 'estado' => 'pagado'.
    protected $validationRules = [
        'pedido_id' => 'required|integer',
        'monto' => 'required|decimal',
        'metodo_pago' => 'required|in_list[efectivo,tarjeta,transferencia,otros]',
        'referencia' => 'permit_empty|string|max_length[100]',
        'estado' => 'required|in_list[pendiente,pagado,reembolsado,fallido]',
    ];
}

