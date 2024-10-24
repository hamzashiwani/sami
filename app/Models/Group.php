<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cordinator()
    {
        return $this->belongsTo(User::class,'cordinator_id');
    }
    
    public function members()
    {
        return $this->belongsToMany(User::class,'group_members');
    }
}
