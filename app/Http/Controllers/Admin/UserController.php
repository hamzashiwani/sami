<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show($id)
    {
        $data = User::findOrFail($id);
        return view('admin.users.show', compact('data'));
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
}
