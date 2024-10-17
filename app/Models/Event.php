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
        $userId = auth()->id();
        $group = Group::where('event_id', $this->id)
            ->whereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();
            if($group) {
                $user = User::find($group->cordinator_id);
                return [
                    "name" => $user->name,
                    "group" => $group->name,
                    "contact" => $user->phone
                ];
            } else {
                return [
                    "name" => "",
                    "group" => "",
                    "contact" => ""
                ];
            }
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
