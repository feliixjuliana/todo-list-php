<?php namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $allowedFields = [
        'user_id','name','description','start_date','end_date','status'
    ];
    protected $useTimestamps = false;
}
