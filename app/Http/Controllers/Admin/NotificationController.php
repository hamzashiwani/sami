<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
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
            'heading' => 'Create Notication',
            'method' => 'POST',
            'action' => route('admin.notification.store'),
            'cancel_url' => route('admin.notification.index')
        ];

        return view('admin.notifications.form', compact('data', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                // $data['file'] = $this->uploadFile($file, 'page');
                $path = $file->store('uploads', 'public');

                $data['file'] = $path;
                if (strpos($request->file('file')->getMimeType(), 'video/') === 0) {
                    $data['file_type'] = 'video';
                    $screenshotPath = $this->generateScreenshot($file);
                    $data['file_screenshot'] = $screenshotPath;
                    // Save screenshot logic here
                } elseif (strpos($request->file('file')->getMimeType(), 'image/') === 0) {
                    $data['file_type'] = 'image';
                }
            }

            Notification::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
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
        $name = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME); // Get original file name without extension
        $screenshotPath = uploadsDir('front').$name.'.jpg';
    
        // Ensure the uploads directory exists
        if (!file_exists(dirname($screenshotPath))) {
            mkdir(dirname($screenshotPath), 0755, true);
        }
    
        $videoPath = $videoFile->getRealPath(); // Get the uploaded video path
    
        // FFmpeg command to generate a screenshot
        $command = "ffmpeg -i \"{$videoPath}\" -ss 00:00:01.000 -vframes 1 \"{$screenshotPath}\" 2>&1";
    
        // Execute the command
        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);
    
        if ($returnVar !== 0) {
            return response()->json(['error' => 'FFmpeg error: ' . implode("\n", $output)], 500);
        }
    
        return $screenshotPath; // Return the path of the screenshot
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
            'heading' => 'Edit Notication',
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

            $blog = Notication::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image'
                ]
            );

            $data['slug'] = generateBlogUniqueSlug($request->title, $blog->slug);

            $previousimage = $request->previous_image;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $data['image'] = $this->updateFile($file, $previousimage, 'name');
            } else {
                $data['image'] = $previousimage;
            }

            $blog->update($data);

            DB::commit();
            return redirect()
                ->route('admin.notification.index')
                ->with('success', 'Notication has been updated successfully.');
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
            $blog = Notication::find($id);
            return redirect()
                ->route('admin.notification.index')
                ->with('success', 'Notication has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
