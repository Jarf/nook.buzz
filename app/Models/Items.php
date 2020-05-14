<?php namespace App\Models;

use CodeIgniter\Model;

class Items extends Model
{
    protected $table      = 'items';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [];

    protected $useTimestamps = false;
}