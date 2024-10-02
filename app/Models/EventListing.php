<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EventListing extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['is_attendance'];

    function getIsAttendanceAttribute() {
        $check = EventAttendance::where('user_id',Auth::id())->where('event_attendance_id',$this->id)->first();
        if($check) {
            return true;
        }
        return false;
    }
}
