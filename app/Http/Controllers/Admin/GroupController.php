<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreGroupRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Group;
use App\Models\GroupMembers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    public function index($id)
    {
        try{
            $data = Group::where('event_id',$id)->get();
            return view('admin.group.index', compact('data','id'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function getGroupsByEvent($id)
    {
        $data = Group::where('event_id',$id)->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data = new Group();

        $form = [
            'type' => 'create',
            'heading' => 'Create Group',
            'method' => 'POST',
            'action' => route('admin.group.store',$id),
            'cancel_url' => route('admin.group.index',$id)
        ];

        return view('admin.group.form', compact('data', 'form','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
{
    try {
        DB::beginTransaction();

        // Validate users
        $users = $request->input('users', []);
        if ($users) {
            foreach ($users as $userId) {
                // Check if user is already in another group
                $existingGroups = Group::where('cordinator_id',$userId)->orwhereHas('members', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->get();

                if ($existingGroups->count() > 0) {
                    return redirect()
                        ->back()
                        ->with('error', "User with ID $userId is already in another group.");
                }
            }
        }

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
        $group->members()->attach($users);

        DB::commit();
    } catch (\Exception $exception) {
        DB::rollBack();
        return redirect()
            ->back()
            ->with('error', $exception->getMessage());
    }

    return redirect()
        ->route('admin.group.index',$id)
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
        return view('admin.group.show', compact('data',));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = Group::find($ids);
        $id = $data->event_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit Group',
            'method' => 'PUT',
            'action' => route('admin.group.update', $id),
            'cancel_url' => route('admin.group.index',$id)
        ];
        $users = User::all(); 
        return view('admin.group.form', compact('data', 'form','users','id'));
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

            $blog = Group::findOrFail($request->id);
            $users = $request->input('users', []);

            $data = $request->except(
                [
                    '_method',
                    '_token',
                    'users',
                    'previous_image',
                    'previous_document'
                ]
            );
            $blog->update($data);
            $blog->members()->sync($users);
            DB::commit();
            return redirect()
                ->route('admin.group.index',$id)
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

    public function getUsers(Request $request)
    {
        if($request->event_id) {
            $groupMembers = Group::where('event_id', $request->event_id)
            ->with('members') // Assuming 'members' is the relationship to fetch users
            ->get()
            ->pluck('members.*.id') // Get all user IDs from group members
            ->flatten()
            ->unique(); // Get unique user IDs
    
            $users = User::whereNotIn('id', $groupMembers)->get();// Fetch all users
        } else {
            $users = User::get();// Fetch all users
        }
    // Fetch all users excluding those who are group members
        return response()->json($users); // Return as JSON
    }

    public function import_csv($id)
    { 
        $request = request()->all();
        $rules = [
                'file' => 'required|max:5000|mimes:csv,txt,xlsx',
        ];
        $validator = Validator::make($request, $rules);
        if($validator->fails()){
            return $this->respondWithError($validator->errors(),false,trans('messages.validation_bad_request'));
        }
        if (!empty($request['file']))
        {
            $file = $request['file'];
            $fileName = md5($file->getClientOriginalName()) . time() . "." . $file->getclientOriginalExtension();
            $file->move(public_path('csv/'), $fileName);
            $request['file'] = $fileName;
            $saveFile = $this->save_csv($request['file'],$id,$request['group_id']);
            if (count($saveFile) > 0){
                return response()->json([
                'status' => true,
                'message' => 'successfully Created',
            ]);
                // return $this->respond($saveFile, [], true, 'success');
            }
            return response()->json([
                'status' => false,
                'message' => 'error',
            ]);
            // return $this->respondInternalError([], false, 'error');
        }
        return response()->json([
                'status' => false,
                'message' => 'error',
            ]);
        // return $this->respondInternalError([], false, 'error');

    }

    public function save_csv($fileNameOn, $id, $group_id)
    {
        $fileName = public_path('csv').'/'.$fileNameOn;
        $file = fopen($fileName,"r");
        $arrayData = [];
        while(! feof($file)){
            array_push($arrayData, fgetcsv($file));
        }
        $col = [];
        $totalRecord=[];
        foreach ($arrayData as $key => $val) {
            if ($key > 0) {
                $data = [];
                $other = [];
                if (!empty($val)) {
                    foreach ($val as $dataKey => $dataVal) {
                        if (is_array($col[$dataKey])) {
                            array_push($other,$col[$dataKey][0]);
                            array_push($other,isset($val[$dataKey]) ? $val[$dataKey] : null);
                        } else {
                            $data[$col[$dataKey]] = isset($val[$dataKey]) ? $val[$dataKey] : null;
                        }
                    }
                }
                if (!empty($data['email'])) {
                 $responseStatus = $this->save_user_import($data, $id , $group_id);
                    // if ($responseStatus) {
                        $totalRecord[] = $data;
                    // }
                }
            } else {
                foreach ($val as $colKey => $colVal){
                    // $colVal = $this->manage_col($colVal,$colKey);
                    array_push($col,$colVal);
                }
            }
        }
        fclose($file);
        // dd($totalRecord);
        return $totalRecord;

    }

    public function save_user_import($data,$id,$group_id)
    {
        if (is_array($data)){
            $user = User::where('email',$data['email'])->first();
            if($user) {
                $hotel = GroupMembers::where('group_id',$group_id)->where('user_id',$user->id)->first();
                if($hotel) {
                    return false;
                } else {
                    $data2['user_id'] = $user->id;
                    $data2['group_id'] = $group_id;
                    GroupMembers::create($data2);
                }
            }
            return true;
        }
    }
}
