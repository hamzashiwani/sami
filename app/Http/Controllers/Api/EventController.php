<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Models\MainQuiz;
use App\Models\Quiz;
use App\Models\EventListing;
use App\Models\EventAttendance;
use App\Models\User;
use App\Models\Notification;
use App\Models\SubmitAnswer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        try {
            if($request->date) {
                $specificDate = $request->date;
                $getUserData = Event::orderBy('id','desc')->with(['eventListings' => function($query) use ($specificDate) {
                    $query->where('date', $specificDate)->where('attendance', 'No'); // Filter event listings by the requested date
                }])
                ->where(function ($query) use ($specificDate) {
                    $query->where('date', '<=', $specificDate)
                          ->where('end_date', '>=', $specificDate);
                })
                ->first();

                // $getUserData = Event::orderBy('id','desc')->where('date', '<=', $specificDate)
                // ->where('end_date', '>=', $specificDate)
                // ->with('eventListings')
                // ->first();

                // if ($getUserData && $getUserData->eventListings) {
                //     $getUserData->eventListings = $getUserData->eventListings->filter(function ($listing) use ($specificDate) {
                //         return $listing->date == $specificDate;
                //     });
                // }
            } else {
                $getUserData = [];
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
            $user = $request->user();

            // Retrieve all notifications
            $allNotifications = Notification::where(function($q) use($user) {
                $q->where('topic', 'Internal')->orWhereHas('group', function($qw) use($user) {
                    $qw->whereHas('members', function($qwe) use($user) {
                        $qwe->where('user_id', $user->id);
                    });
                });
            })->orderBy('created_at', 'desc')->limit(10)->get();

            // Map through notifications and check if the user has read them
            $notifications = $allNotifications->map(function ($notification) use ($user) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'file' => $notification->file,
                    'file_screenshot' => $notification->file_screenshot,
                    'file_type' => $notification->file_type,
                    'created_at' => $notification->created_at,
                    'is_read' => $user->notifications()->where('notification_id', $notification->id)->exists(), // Check if read
                ];
            });
            $getUserData['notification'] = $notifications;
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function dashboardGuest(Request $request)
    {
        try {
            $getUserData['event'] = json_encode([]);

            // Retrieve all notifications
            $allNotifications = Notification::where('topic', 'Guest')->orderBy('created_at', 'desc')->where('topic', 'Global')->limit(10)->get();

            // Map through notifications and check if the user has read them
            $notifications = $allNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'file' => $notification->file,
                    'file_screenshot' => $notification->file_screenshot,
                    'file_type' => $notification->file_type,
                    'created_at' => $notification->created_at,
                    'is_read' => 0, // Check if read
                ];
            });
            $getUserData['notification'] = $notifications;
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function getAttendance(Request $request)
    {
        try {
            $event = Event::where('end_date', '>=', date('Y-m-d'))->first();
            if($event) {
                $getUserData = EventListing::where('attendance', 'Yes')->where('date',date('Y-m-d'))->where('time', '<=',date('H:i:s'))->get();
                $user = $request->user();
            } else {
                $getUserData = [];
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function getLeadorboard(Request $request)
    {
        try {
            $event = MainQuiz::where('id', $request->quiz_id)->first();
            if($event) {
                // Get total participants
            $leaderboard['totalParticipants'] = SubmitAnswer::where('quiz_id', $event->id)->distinct('user_id')->count('user_id');

            // Get leaderboard data
            $leaderboard['leadorboard'] = SubmitAnswer::select('user_id', 'quiz_id')
                ->where('quiz_id', $event->id)
                ->selectRaw('MIN(seconds) as total_time, COUNT(CASE WHEN is_correct THEN 1 END) as total_correct_answers')
                ->groupBy('user_id')
                ->orderBy('points', 'asc') // Assuming you want to order by time ascending (faster is better)
                ->with('user') // Assuming you want user details as well
                ->get();
            } else {
                return $this->respond([], [], true, 'Quiz Not Found');    
            }
            return $this->respond($leaderboard, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function getQuestions(Request $request)
    {
        try {
            $event = MainQuiz::where('code', $request->code)->first();
            if($event) {
                $getUserData = Quiz::orderBy('id','asc')->where('quiz_id',$event->id)->where('status',0)->first();
                if(!$getUserData) {
                    return $this->respond([], [], true, 'Quiz Finish');
                }
                $user = $request->user();
                $getUserData->question_time = $event->question_time;
            } else {
                $getUserData = [];
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function getQuestionsApi(Request $request)
    {
        try {
            $event = MainQuiz::where('code', $request->code)->first();
            if($event) {
                $getUserData = Quiz::orderBy('id','desc')->where('quiz_id',$event->id)->where('status',0)->first();
                if(!$getUserData) {
                    return $this->respond([], [], true, 'Success');
                }
                $user = $request->user();
                $getUserData->question_time = $event->question_time;
            } else {
                $getUserData = [];
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function getQuiz(Request $request)
    {
        try {
            $data = [];
            $event = MainQuiz::where('code', $request->code)->first();
            if($event) {
                $data['quiz'] = $event;
                $data['total'] = Quiz::orderBy('id','desc')->where('quiz_id',$event->id)->get();
                $data['pending'] = Quiz::orderBy('id','desc')->where('quiz_id',$event->id)->where('status',0)->get();
            } else {
                $data = [];
            }
            return $this->respond($data, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $event = Quiz::where('id', $request->quiz_id)->first();
            if($event) {
                $getUserData = Quiz::where('id',$request->quiz_id)->update(['status'=>1]);                
                // if($getUserData) {
                    $event2 = Quiz::where('id', $request->quiz_id)->where('status',0)->get();
                    if(count($event2) > 0) {
                        $getUserData = MainQuiz::where('id',$event->quiz_id)->update(['status'=>1]);
                    } else {
                        $getUserData = MainQuiz::where('id',$event->quiz_id)->update(['status'=>2]);
                    }
                    return $this->respond([], [], true, 'Answer Submited');
                // }
                $user = $request->user();
            } else {
                $getUserData = [];
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function getQuizStats(Request $request)
    {
        try {
            $event = Quiz::where('id', $request->quiz_id)->first();
            if($event) {
                return SubmitAnswer::where('question_id', $request->quiz_id)
                ->select('answer', \DB::raw('count(*) as total'))
                ->groupBy('answer')
                ->get();
            } else {
                $getUserData = [];
            }
            return $this->respond($getUserData, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function submitAnswer(Request $request)
    {
        try {
            $event = Quiz::where('id', $request->question_id)->first();
            if($event) {
                $main = MainQuiz::where('id',$event->quiz_id)->first();
                $check = SubmitAnswer::where('user_id',auth()->user()->id)->where('question_id',$request->question_id)
                ->first();
                if(!$check) {
                    if($event->correct_answer == $request->answer) {
                        $points = $request->seconds + 5;
                        $correct = 1;
                        $time_remain = $main->question_time - $request->seconds;
                    } else {
                        $points = 0;
                        $correct = 0;
                        $time_remain = $main->question_time - $request->seconds;
                    }
                    $getUserData = [
                        'user_id' => auth()->user()->id,
                        'event_id' => $main->event_id,
                        'quiz_id' => $main->id,
                        'question_id' => $request->question_id,
                        'answer' => $request->answer,
                        'seconds' => $request->seconds,
                        'points' => $points,
                        'is_correct' => $correct,
                        'time_remain' => $time_remain,
                    ];              
                    SubmitAnswer::create($getUserData);
                    $user = $request->user();
                } else {
                    return $this->respond([], [], true, 'Already Submit');
                }
            } else {
                $getUserData = [];
            }
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
                $check = EventAttendance::where('user_id',auth()->user()->id)->
                where('event_attendance_id',$data->id)->first(); 
                if(!$check) {
                    $create = [
                        'user_id' => auth()->user()->id,
                        'event_id' => $data->event_id,
                        'event_attendance_id' => $data->id,
                    ];

                    EventAttendance::create($create);
                } else {
                    // return $this->respondBadRequest([], false, 'Already Check In');    
                    return $this->respond([], [], true, 'Already Check In');
                }
            } else {
                return $this->respondBadRequest([], false, 'Code Not Found');        
            }
            return $this->respond([], [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function profileImage(Request $request)
    {
        try {
            if(!$request->hasFile('image')) {
                return $this->respondBadRequest([], false, 'Upload File Not Found');
            }

            $allowedfileExtension=['jpeg','JPEG','jpg','JPG','png','PNG', 'webp'];
            $file = $request->file('image');
            $errors = [];

            $extension = $file->getClientOriginalExtension();

            $check = in_array($extension,$allowedfileExtension);
            if ($check) {

            $path = $file->store('user','public');
            $type = $file->getClientOriginalExtension();

            $user = User::where('id', $request->user()->id)->update(['image' => $path]);

            $user = User::find($request->user()->id);
            return $this->respond($user, [], true, 'Success');

            } else {
                return $this->respondBadRequest([], false, 'Invalid File Format');
            }
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $user = User::where('id', $request->user()->id)->first();
            if($user) {
                 return $this->respond($user, [], true, 'Success');  
            }
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}
