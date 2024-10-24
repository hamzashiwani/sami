<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreNotificationRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Event;
use App\Models\Group;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private $blog;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Notification $blog)
    {
        $this->middleware('auth:admin');
        $this->blog = $blog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = Notification::get();
            return view('admin.notifications.index', compact('data'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new Notification();

        $form = [
            'type' => 'create',
            'heading' => 'Create Notification',
            'method' => 'POST',
            'action' => route('admin.notification.store'),
            'cancel_url' => route('admin.notification.index')
        ];
        $events = Event::pluck('title', 'id');
        return view('admin.notifications.form', compact('data', 'form', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotificationRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image'
                ]
            );

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                // $path = $this->uploadFile($file, 'page');
                 $filename = 'custom_filename_' . time() . '.' . $file->getClientOriginalExtension(); // Example: custom_filename_1634242000.jpg
                $path = $file->storeAs('uploads', $filename, 'public');

                $data['file'] = $path;
                if (strpos($request->file('file')->getMimeType(), 'video/') === 0) {
                     $videoFullPath = storage_path('app/public/' . $path);
                    // dd($videoFullPath);
                    $data['file_type'] = 'video';
                    $screenshotPath = $this->generateScreenshot($videoFullPath);
                    $data['file_screenshot'] = $screenshotPath;
                    // Save screenshot logic here
                } elseif (strpos($request->file('file')->getMimeType(), 'image/') === 0) {
                    $data['file_type'] = 'image';
                }
            }

            $notification = Notification::create($data);
            DB::commit();
            if($notification->topic == 'Internal') {
                Notification::sendPushNotification($notification->topic, $notification->title, $notification->message, $notification->id, "notification", $notification->id);
            }
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.notification.index')
            ->with('success', 'Notification has been added successfully.');
    }

    private function generateScreenshot($videoFile) {
        $screenshotName = 'screenshot_' . time() . '.png';
        $screenshotPath = 'screenshots/' . $screenshotName;
        $screenshotFullPath = storage_path('app/public/' . $screenshotPath);

        // Execute FFmpeg command to generate a screenshot
        $ffmpegPath = 'C:/ffmpeg/bin/ffmpeg';
        $command = $ffmpegPath . " -i " . escapeshellarg($videoFile) . " -ss 00:00:02 -vframes 1 " . escapeshellarg($screenshotFullPath);
        
        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            return response()->json(['success' => false, 'error' => 'Could not generate screenshot.'], 500);
        }

        return $screenshotName;
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Notification::find($id);
        return view('admin.notifications.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Notification::find($id);
        $form = [
            'type' => 'create',
            'heading' => 'Edit Notification',
            'method' => 'PUT',
            'action' => route('admin.notification.update', $id),
            'cancel_url' => route('admin.notification.index')
        ];
        return view('admin.notifications.form', compact('data', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $notification = Notification::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image'
                ]
            );

            $previousimage = $request->previous_image;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                // $path = $this->uploadFile($file, 'page');
                 $filename = 'custom_filename_' . time() . '.' . $file->getClientOriginalExtension(); // Example: custom_filename_1634242000.jpg
                $path = $file->storeAs('uploads', $filename, 'public');

                $data['file'] = $path;
                if (strpos($request->file('file')->getMimeType(), 'video/') === 0) {
                     $videoFullPath = storage_path('app/public/' . $path);
                    // dd($videoFullPath);
                    $data['file_type'] = 'video';
                    $screenshotPath = $this->generateScreenshot($videoFullPath);
                    $data['file_screenshot'] = $screenshotPath;
                    // Save screenshot logic here
                } elseif (strpos($request->file('file')->getMimeType(), 'image/') === 0) {
                    $data['file_type'] = 'image';
                }
            } else {
                $data['file'] = $previousimage;
                $data['file_type'] = 'image';
            }

            $notification->update($data);

            DB::commit();
            return redirect()
                ->route('admin.notification.index')
                ->with('success', 'Notification has been updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $blog = Notification::find($id);
            $blog->delete();
            return redirect()
                ->route('admin.notification.index')
                ->with('success', 'Notification has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
