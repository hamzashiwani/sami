<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventHotel;
use App\Models\EventFlight;
use App\Models\EventTransport;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hotel()
    {
        return $this->hasOne(EventHotel::class)->where('user_id', Auth::id());
    }

    public function flights()
    {
        return $this->hasOne(EventFlight::class)->where('user_id', Auth::id());
    }

    public function transports()
    {
        return $this->hasOne(EventTransport::class)->where('user_id', Auth::id());
    }
}
