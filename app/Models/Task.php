<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'status',
        'assigned_to',
        'user_id',
        'added_by'
    ];
    protected $guarded = ['due_date'];

    protected $table ='my_tasks';

    // Specify the column name for the primary key
    protected $primaryKey = 'task_id';

    // Indicate whether the primary key is auto-incrementing
    public $incrementing = true;
    
    // Define the data type of the primary key 
    protected $keyType = 'int';

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
