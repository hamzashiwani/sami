<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $getUserData = Event::orderBy('created_at', 'desc')->get();
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        try {
            $getUserData['event'] = [];
            $getUserData['notification'] = Notification::orderBy('created_at', 'desc')->get();
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}