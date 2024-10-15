<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $data = User::orderBy('created_at', 'DESC')->get();
            return view('admin.users.index', compact('data'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->except([
            '_token',
            '_method',
            'image'
        ]);

        //move | upload file on server
         if ($request->hasFile('image')) {
            $file          = $request->file('image');
            $extension     = $file->getClientOriginalExtension();
            $filename      = 'admin-profile-'.time() . '.' . $extension;
            $file->move(uploadsDir('admin'), $filename);
            $data['image'] = $filename;
        }

        $password         = generateRandomString(8);
        $data['password'] = bcrypt($password);

            if ($data['email'] != '') {
                Mail::send(
                    'emails.admin.created',
                    [
                        'data'     => $data,
                        'password' => $password,
                    ],
                    function ($message) use ($data) {
                        $email   = $data['email'];
                        $message->to($email, $email);
                        $message->replyTo(config('mail.from.address'), config('mail.from.name'));
                        $subject = "Account created.";
                        $message->subject($subject);
                    }
                );
            }

        // generate-random-8digits-password (send in mail & store in DB).

        User::create($data);

        return redirect()
            ->route('admin.users.edit')
            ->with('success', 'User has been added successfully.');
    }

    public function show($id)
    {
        $data = User::findOrFail($id);
        return view('admin.users.show', compact('data'));
    }

    public function edit($id)
    {
      $data = User::find($id);
      return view('admin.users.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except([
            '_token',
            '_method',
            'email',
            'previous_image',
            'image',
            'password',
            'password_confirmation'
        ]);

        //move | upload file on server
         if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename  = 'admin-profile-'.time() . '.' . $extension;
            $file->move(uploadsDir('admin'), $filename);

            if ($request->previous_image != '' && file_exists(uploadsDir('admin') . $request->previous_image)) {
                unlink(uploadsDir('admin') . $request->previous_image);
            }

            $data['image'] = $filename;
        }

        if (isset($request->password) && $request->password !='') {
            $data['password'] = bcrypt($request->password);
        }

        User::where('id', $id)->update($data);

        return redirect()
            ->back()
            ->with('success', 'User has been updated successfully.');
    }



    public function destroy($id)
    {
        try {
            $data = User::findOrFail($id);
            if ($data->logo != '' && file_exists(uploadsDir() . $data->logo)) {
                unlink(uploadsDir() . $data->logo);
            }
            $data->delete();
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
    public function updateStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            // Find the user by ID
            $user = User::find($request->id);

            // Check if the user exists
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ]);
            }

            // Update the user's status based on the request
            $user->update([$request->column => $request->status]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Status has been updated',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function importLead(Request $request)
    {

        foreach ($request->name as $key => $value) {
        $data = [
            'name' => ($request->name[$key]) ? $request->name[$key] : '',
            'email' => ($request->email[$key]) ? $request->email[$key] : '',
            'phone' => ($request->phone[$key])   ? $request->phone[$key] : '',
        ];

         $user = User::create($data);
        }

            return response()->json([
                'status' => true,
                'message' => 'successfully Created',
            ]);
    }

     public function import_csv()
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
            $saveFile = $this->save_csv($request['file']);
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

    public function save_csv($fileNameOn)
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
                 $responseStatus = $this->save_user_import($data);
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

    public function save_user_import($data)
    {
        if (is_array($data)){
            $user = User::where('email',$data['email'])->first();
            if(!$user) {
                User::create($data);
            }
            return true;
        }
    }
}
