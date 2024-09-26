<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgetPasswordController extends BaseController
{
    public function sendMail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);

            if ($validator->fails()) {
                return $this->respondBadRequest($validator->errors());
            }

            $user = User::Where(['email' => $request['email']])->first();

            if ($user) {
                $token = Str::random(30);
                $otp = mt_rand(100000, 999999);

                $data = [
                    'email' => $user['email'],
                    'token' => $token,
                    'otp'   => $otp,
                    'created_at' => now(),
                ];

                DB::table('password_resets')->updateOrInsert(['email' => $user['email']], $data);
                dispatch(new \App\Jobs\ForgetPassword($data));
                DB::commit();
                if ($user) {
                    return $this->respond($data, [], true, 'Verification code has been sent to the email');
                }
            }
            $this->respondBadRequest([], false, 'User not found');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->respondInternalError($e->getMessage());
        }
    }


    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'reset_token' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest($validator->errors());
        }

        try {
            $otp = $request->otp;
            $data = DB::table('password_resets')
                ->where(
                    [
                        'token' => $request['reset_token'],
                        'otp'   => $otp
                    ]
                )->first();

            if (isset($data)) {
                return $this->respond([], [], true, 'Your OTP is successfully matched.');
            }

            return $this->respondBadRequest([], false, __('OTP Does not matched.'));
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }


    public function resetPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'password' => 'min:8|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required',
                // 'reset_token' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->respondBadRequest($validator->errors());
            }

            $data = DB::table('password_resets')->where(['token' => $request['reset_token']])->first();
            if (isset($data)) {
                DB::table('users')->where(['email' => $data->email])->update([
                    'password' => bcrypt($request['confirm_password'])
                ]);

                DB::table('password_resets')
                    ->where(
                        [
                            'email' => $data->email,
                            'token' => $request['reset_token']
                        ]
                    )->delete();
                DB::commit();
                return $this->respond([], [], true, 'Password reset successfully.');
            }

            return $this->respondBadRequest([], false, __('Something went wrong, No record found.'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->respondInternalError($e->getMessage());
        }
    }
}
