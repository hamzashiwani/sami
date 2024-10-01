<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Models\EventListing;
use App\Models\EventAttendance;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        try {
            if($request->date) {
                $specificDate = $request->date;
                $getUserData = Event::with(['eventListings' => function($query) use ($specificDate) {
                    $query->where('date', $specificDate); // Filter event listings by the requested date
                }])
                ->where(function ($query) use ($specificDate) {
                    $query->where('date', '<=', $specificDate)
                          ->where('end_date', '>=', $specificDate);
                })
                ->first();
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        try {
            if($request->id) {
                $getUserData = Event::with('eventListings')->where('id',$request->id)
                ->first();
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        try {
            $getUserData['event'] = Event::with(['hotel', 'flights', 'transports'])
            ->where('end_date', '>=', Carbon::now())
            ->first();
            $getUserData['notification'] = Notification::orderBy('created_at', 'desc')->get();
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function attendance(Request $request)
    {
        try {
            $data = EventListing::where('code',$request->code)->first();
            if($data) {
                $check = EventAttendance::where('user_id',auth()->user()->id)->where('event_id',$getUserData->id)->
                where('event_attendance_id',$data->id)->first(); 
                if(!$check) {
                    $create = [
                        'user_id' => auth()->user()->id,
                        'event_id' => $data->event_id,
                        'event_attendance_id' => $data->id,
                    ];

                    EventAttendance::create($create);
                } else {
                    return $this->respondBadRequest([], false, 'Already Check In');    
                }
            } else {
                return $this->respondBadRequest([], false, 'Code Not Found');        
            }
            return $this->respond([], [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}