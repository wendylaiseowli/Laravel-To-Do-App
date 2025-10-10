<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
    
    public function project(){
        return $this->belongsTo(Project::class);
    }
}
