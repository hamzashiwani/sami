<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hotel;
use App\Models\Flight;
use App\Models\Transport;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hotel()
    {
        return $this->hasOne(Hotel::class)->where('user_id', Auth::id());
    }

    public function flights()
    {
        return $this->hasOne(Flight::class)->where('user_id', Auth::id());
    }

    public function transports()
    {
        return $this->hasOne(Transport::class)->where('user_id', Auth::id());
    }
}
