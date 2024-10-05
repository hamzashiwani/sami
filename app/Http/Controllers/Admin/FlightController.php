<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\EventFlight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    private $faq;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EventFlight $faq)
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
            $data = EventFlight::where('event_id',$id)->get();
            $users = User::get();
            return view('admin.flight.index', compact('data','id','users'));
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
        $data = new EventFlight();
        $form = [
            'type' => 'create',
            'heading' => 'Add EventFlight',
            'method' => 'POST',
            'action' => route('admin.event-flight.store',$id),
            'cancel_url' => route('admin.event-flight.index',$id)
        ];
        $users = User::get();
        return view('admin.flight.form', compact('data', 'form','id','users'));
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
                    'id'
                ]
            );

            $hotel = EventFlight::where('event_id',$id)->where('user_id',auth()->user()->id)->get();
            if(count($hotel) > 1) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with('error', 'Reacord Already Available On Selected User');
            }
            EventFlight::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.event-flight.index',$id)
            ->with('success', 'EventFlight has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = EventFlight::find($id);
        return view('admin.flight.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = EventFlight::find($ids);
        $id = $data->event_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit EventFlight',
            'method' => 'PUT',
            'action' => route('admin.event-flight.update', $ids),
            'cancel_url' => route('admin.event-flight.index',$id)
        ];
        $users = User::get();
        return view('admin.flight.form', compact('data', 'form','id','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try {
            DB::beginTransaction();
            $flight = EventFlight::where('id',$id)->first();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                ]
            );
            EventFlight::where(['id' => $request->id])->update($data);
            DB::commit();
            return redirect()
                ->route('admin.event-flight.index',$flight->event_id)
                ->with('success', 'EventFlight has been updated successfully.');
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
            $faq = EventFlight::find($id);
            return redirect()
                ->route('admin.event-flight.index')
                ->with('success', 'EventFlight has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
