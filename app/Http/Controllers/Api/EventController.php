<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        try {
            if($request->date) {
                $getUserData = Event::with('eventListings')->where(function ($query) use ($request) {
                    $query->where('date', '<=', $request->date)
                          ->where('end_date', '>=', $request->date);
                })->orwhereHas('eventListings',function ($que) use ($request){
                    $que->where('date',$request->date);
                })->first();
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
            $getUserData = Event::where('date', '=', Carbon::now())->first();
            if($getUserData) {
                $data = EventListing::where('event_id',$getUserData->id)->where('code',$request->code)->first();
                if($data) {
                    $check = EventAttendance::where('user_id',auth()->user()->id)->where('event_id',$getUserData->id)->
                    where('event_attendance_id',$data->id)->first(); 
                    if(!$check) {
                        $create = [
                            'user_id' => auth()->user()->id,
                            'event_id' => $getUserData->id,
                            'event_attendance_id' => $data->id,
                        ];
    
                        EventAttendance::create($create);
                    } else {
                        return $this->respondBadRequest([], false, 'Already Check In');    
                    }
                } else {
                    return $this->respondBadRequest([], false, 'Code Not Found');        
                }
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}