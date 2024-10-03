<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\EventHotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    private $faq;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EventHotel $faq)
    {
        $this->middleware('auth:admin');
        $this->faq = $faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try{
            $data = EventHotel::where('event_id',$id)->get();
            return view('admin.hotel.index', compact('data','id'));
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
        $data = new Faq();
        $form = [
            'type' => 'create',
            'heading' => 'Add EventHotel',
            'method' => 'POST',
            'action' => route('admin.event-hotel.store',$id),
            'cancel_url' => route('admin.event-hotel.index',)
        ];
        return view('admin.hotel.form', compact('data', 'form','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqRequest $request,$id)
    {
        try{
            DB::beginTransaction();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'id'
                ]
            );
            EventHotel::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.event-hotel.index',$id)
            ->with('success', 'EventHotel has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = EventHotel::find($id);
        return view('admin.hotel.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = EventHotel::find($ids);
        $id = $data->event_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit EventHotel',
            'method' => 'PUT',
            'action' => route('admin.event-hotel.update', $ids),
            'cancel_url' => route('admin.event-hotel.index',$id)
        ];
        return view('admin.hotel.form', compact('data', 'form','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaqRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $hotel = EventHotel::where('id',$id)->first();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                ]
            );
            EventHotel::update(['id' => $request->id], $data);
            DB::commit();
            return redirect()
                ->route('admin.event-hotel.index',$hotel->event_id)
                ->with('success', 'EventHotel has been updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('errors', $exception->getMessage());
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
            $faq = EventHotel::find($id);
            return redirect()
                ->route('admin.event-hotel.index')
                ->with('success', 'EventHotel has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
