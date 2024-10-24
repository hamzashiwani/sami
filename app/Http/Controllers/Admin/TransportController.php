<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\EventTransport;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    private $faq;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EventTransport $faq)
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
            $data = EventTransport::where('event_id',$id)->get();
            $users = User::get();
            return view('admin.transport.index', compact('data','id','users'));
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
        $data = new EventTransport();
        $form = [
            'type' => 'create',
            'heading' => 'Add EventTransport',
            'method' => 'POST',
            'action' => route('admin.event-transport.store',$id),
            'cancel_url' => route('admin.event-transport.index',$id)
        ];
        $users = User::get();
        $groups = Group::where('event_id', $id)->get();
        return view('admin.transport.form', compact('data', 'form','id','users','groups'));
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

            // if($request->type == '0') {
            //     $transport = EventTransport::where('event_id',$id)->where('user_id',$request->user_id)->first();
            //     if($transport) {
            //         DB::rollBack();
            //         return redirect()
            //             ->back()
            //             ->with('error', 'Reacord Already Available On Selected User');
            //     }
            // } else {
            //     $transport = EventTransport::where('event_id',$id)->where('group_id',$request->group_id)->first();
            //     if($transport) {
            //         DB::rollBack();
            //         return redirect()
            //             ->back()
            //             ->with('error', 'Reacord Already Available On Selected Group');
            //     }
            // }
            EventTransport::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.event-transport.index',$id)
            ->with('success', 'EventTransport has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = EventTransport::find($id);
        return view('admin.transport.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = EventTransport::find($ids);
        $id = $data->event_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit EventTransport',
            'method' => 'PUT',
            'action' => route('admin.event-transport.update', $ids),
            'cancel_url' => route('admin.event-transport.index',$id)
        ];
        $users = User::get();
        $groups = Group::where('event_id', $id)->get();
        return view('admin.transport.form', compact('data', 'form','id','users','groups'));
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
            $transport = EventTransport::where('id',$id)->first();
            $data = $request->except(
                [
                    '_method',
                    '_token',
                ]
            );
            EventTransport::where(['id' => $request->id])->update($data);
            DB::commit();
            return redirect()
                ->route('admin.event-transport.index',$transport->event_id)
                ->with('success', 'EventTransport has been updated successfully.');
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
            $faq = EventTransport::find($id);
            $event_id = $faq->event_id;
            $faq->delete();
            return redirect()
                ->route('admin.event-transport.index', $event_id)
                ->with('success', 'EventTransport has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
