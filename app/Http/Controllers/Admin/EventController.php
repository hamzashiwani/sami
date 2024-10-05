<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private $blog;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Event $blog)
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
            $data = Event::get();
            return view('admin.event.index', compact('data'));
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
        $data = new Event();

        $form = [
            'type' => 'create',
            'heading' => 'Create Event',
            'method' => 'POST',
            'action' => route('admin.event.store'),
            'cancel_url' => route('admin.event.index')
        ];

        return view('admin.event.form', compact('data', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image',
                    'previous_document'
                ]
            );

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // $data['file'] = $this->uploadFile($file, 'page');
                $path = $file->store('uploads', 'public');

                $data['image'] = $path;
            }


            if ($request->hasFile('document')) {
                $document = $request->file('document');
                // $data['file'] = $this->uploadFile($file, 'page');
                $path2 = $document->store('uploads', 'public');

                $data['document'] = $path2;
            }

            Event::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.event.index')
            ->with('success', 'Event has been added successfully.');
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
        return view('admin.event.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Event::find($id);
        $form = [
            'type' => 'create',
            'heading' => 'Edit Event',
            'method' => 'PUT',
            'action' => route('admin.event.update', $id),
            'cancel_url' => route('admin.event.index')
        ];
        return view('admin.event.form', compact('data', 'form'));
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

            $blog = Event::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image',
                    'previous_document'
                ]
            );

            $previousimage = $request->previous_image;
            $previousdocument = $request->previous_document;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads', 'public');

                $data['image'] = $path;
                // $data['image'] = $this->updateFile($file, $previousimage, 'name');
            } else {
                $data['image'] = $previousimage;
            }

            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $path2 = $document->store('uploads', 'public');

                $data['document'] = $path2;
                // $data['image'] = $this->updateFile($file, $previousimage, 'name');
            } else {
                $data['document'] = $previousdocument;
            }

            $blog->update($data);

            DB::commit();
            return redirect()
                ->route('admin.event.index')
                ->with('success', 'Event has been updated successfully.');
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
            $blog = Event::find($id);
            return redirect()
                ->route('admin.event.index')
                ->with('success', 'Event has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
