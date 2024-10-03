<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventHotel;
use App\Models\EventFlight;
use App\Models\EventListing;
use App\Models\EventTransport;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $appends = ['cordinator'];

    function getCordinatorAttribute() {
        return json_encode([
            "name" => "Test User",
            "group" => "Marketing Group",
            "contact" => "00000000000"
        ]);
    }

    public function eventListings()
    {
        return $this->hasMany(EventListing::class);
    }

    public function hotel()
    {
        return $this->hasOne(EventHotel::class)->where('user_id', Auth::id());
    }

    public function flights()
    {
        return $this->hasMany(EventFlight::class)->where('user_id', Auth::id());
    }

    public function transports()
    {
        return $this->hasOne(EventTransport::class)->where('user_id', Auth::id());
    }
}
