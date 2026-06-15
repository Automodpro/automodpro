<?php
namespace App\Models;

use CodeIgniter\Model;

class AuthProviderModel extends Model
{
    protected $table = 'auth_providers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'provider', 'provider_id'];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}
