<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTransport extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
