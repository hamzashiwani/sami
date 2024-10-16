<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreGroupRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    private $blog;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Group $blog)
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
            $data = Group::get();
            return view('admin.group.index', compact('data'));
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
        $data = new Group();

        $form = [
            'type' => 'create',
            'heading' => 'Create Group',
            'method' => 'POST',
            'action' => route('admin.group.store'),
            'cancel_url' => route('admin.group.index')
        ];

        return view('admin.group.form', compact('data', 'form'));
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
                    'users',
                    'previous_image',
                    'previous_document'
                ]
            );

            $group = Group::create($data);
            $group->members()->attach($request->input('users', []));
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.group.index')
            ->with('success', 'Group has been added successfully.');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Group::find($id);
        return view('admin.group.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Group::find($id);
        $form = [
            'type' => 'create',
            'heading' => 'Edit Group',
            'method' => 'PUT',
            'action' => route('admin.group.update', $id),
            'cancel_url' => route('admin.group.index')
        ];
        $users = User::all(); 
        return view('admin.group.form', compact('data', 'form','users'));
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

            $blog = Group::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'previous_image',
                    'previous_document'
                ]
            );

            $blog->update($data);

            DB::commit();
            return redirect()
                ->route('admin.group.index')
                ->with('success', 'Group has been updated successfully.');
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
            $blog = Group::find($id);
            Group::where('id',$id)->delete();
            return redirect()
                ->route('admin.group.index')
                ->with('success', 'Group has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }

    public function getUsers()
    {
        $users = User::all(); // Fetch all users
        return response()->json($users); // Return as JSON
    }
}
