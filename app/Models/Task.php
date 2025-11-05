<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'priority',
        'due_date',
        'description',
        'status',
        'project_id',
        'completed_at',
    ];

    protected $casts=[
        'due_date'=>'datetime',
        'completed_at' =>'datetime',
    ];
}
