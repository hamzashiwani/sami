<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $skip = 0;
            $skip = $request->skip;
            $getUserData = Notification::orderBy('created_at', 'desc')
                ->skip($skip)->take(10)->get();
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}