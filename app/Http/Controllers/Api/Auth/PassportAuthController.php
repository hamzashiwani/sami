<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DeviceToken;

class PassportAuthController extends BaseController
{
    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $temporary_token = Str::random(40);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'otp' => rand(100000, 999999),
                'is_active' => 1,
                'password' => bcrypt($request->password),
                // 'temporary_token' => $temporary_token,
            ]);

            $token = $user->createToken('LaravelAuthApp')->accessToken;
            dispatch(new \App\Jobs\UserRegistration($user, $user->email));
            DB::commit();
            return $this->respond(['token' => $token, 'user' => $user], [], 200,  'Account created successfully');
        } catch (Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $user = User::where(['email' => $request->email])->first();

            $data = [
                'email' => $request->email
            ];

            if (isset($user)) {
                $user->update(['otp'=>'12345']);
                dispatch(new \App\Jobs\UserRegistration($user, $user->email));
                // $token = auth()->user()->createToken('LaravelAuthApp');
                return $this->respond([], [], 200,  'Code Send successfully!');
            } else {
                $errors = [];
                array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid credential or account no verified yet']);
                return $this->respondBadRequest($errors,  false, '', 401);
            }
        } catch (Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }


    public function verify_otp(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->respondBadRequest($validator->errors());
            }

            $user = User::where(['email' => $request->email, 'otp' => $request->otp])->first();
            // dd($user);
            if (empty($user)) {

                return $this->respondNotFound([], false, 'Invalid code');
            }

            $user->update(['otp' => null]);

            $token = $this->createUserToken($user, 'login');
            $response = ['token' => $token, 'user' => $user];
            DB::commit();
            return $this->respond(
                $response,
                [],
                true,
                'Code match successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function resend_code(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            // dd($user);
            if ($user) {
                $data['otp'] = 12345;
                $user->update(['otp' => $data['otp']]);
                dispatch(new \App\Jobs\UserRegistration($user, $user->email));
                DB::commit();
                return $this->respond(
                    $user,
                    [],
                    true,
                    'Code resent successfully'
                );
            } else {
                return $this->respondNotFound('User not found'); // You can customize this response accordingly
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function saveDeviceToken(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $deviceToken = DeviceToken::updateOrCreate(
                ['user_id' => auth()->id()],
                ['device_token' => $request->device_token, 'user_id' => auth()->id()]
            );
            DB::commit();
            return $this->respond(
                [],
                [],
                true,
                'Device token saved successfully!'
            );

        } catch (Exception $e) {
            DB::rollBack();
            return $this->respondInternalError($e->getMessage());
        }
    }
}
