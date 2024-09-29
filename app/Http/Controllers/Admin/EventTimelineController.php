<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\EventListing;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventTimelineController extends Controller
{
    private $blog;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EventListing $blog)
    {
        $this->middleware('auth:admin');
        $this->blog = $blog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try{
            $data = EventListing::where('event_id',$id)->get();
            return view('admin.event-timeline.index', compact('data','id'));
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
    public function create($id)
    {
        $data = new EventListing();

        $event =  Event::find($id);
        $startDate = $event->date;
        $endDate = $event->end_date;

        $dates = $this->generateDateRange($startDate, $endDate);

        $form = [
            'type' => 'create',
            'heading' => 'Create Event Timeline',
            'method' => 'POST',
            'action' => route('admin.event-timeline.store',$id),
            'cancel_url' => route('admin.event-timeline.index',$id)
        ];

        return view('admin.event-timeline.form', compact('data', 'form','id','dates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
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

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // $data['file'] = $this->uploadFile($file, 'page');
                $path = $file->store('uploads', 'public');

                $data['image'] = $path;
            }

            EventListing::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.event-timeline.index',$id)
            ->with('success', 'Event Timeline has been added successfully.');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Event::find($id);
        return view('admin.event-timeline.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = EventListing::find($ids);
        $id = $data->id;

        $event =  Event::find($data->event_id);
        $startDate = $event->date;
        $endDate = $event->end_date;

        $dates = $this->generateDateRange($startDate, $endDate);


        $form = [
            'type' => 'create',
            'heading' => 'Edit Event Timeline',
            'method' => 'PUT',
            'action' => route('admin.event-timeline.update', $ids),
            'cancel_url' => route('admin.event-timeline.index',$data->event_id)
        ];
        return view('admin.event-timeline.form', compact('data', 'form','id','dates'));
    }

    private function generateDateRange($startDate, $endDate)
    {
        $dates = [];
        $currentDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate);

        while ($currentDate->format('Y-m-d') <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d'); // Format YYYY-MM-DD
            $currentDate->addDay(); // Increment the date by one day
        }

        return $dates;
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

            $blog = EventListing::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image'
                ]
            );

            $previousimage = $request->previous_image;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads', 'public');

                $data['image'] = $path;
                // $data['image'] = $this->updateFile($file, $previousimage, 'name');
            } else {
                $data['image'] = $previousimage;
            }

            $blog->update($data);

            DB::commit();
            return redirect()
                ->route('admin.event-timeline.index',$blog->event_id)
                ->with('success', 'Event Timeline has been updated successfully.');
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
            $blog = EventListing::find($id);
            return redirect()
                ->route('admin.event-timeline.index')
                ->with('success', 'Event Timeline has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
