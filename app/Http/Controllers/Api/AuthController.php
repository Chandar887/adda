<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendMail;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Get exception error response
     */
    private function getErrorResponse($exception)
    {
        return response()->json([
            'status' => false,
            'msg'    => $exception->getMessage(),
        ]);
    }
    
    /**
     * Login
     */
    public function login(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Login credentials
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;

                $response = [
                    'status' => true,
                    'msg'    => 'Login successfully',
                    'token'  => $token
                ];
            } else {
                $response = [
                    'status' => false,
                    'msg'    => 'Invalid credentials'
                ];
            }

            return response()->json($response);            
        } catch (\Exception $e) {
           return $this->getErrorResponse($e);
        }
    }

    /**
     * Register
     */
    public function register(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'firstname' => 'required|string',
                'lastname'  => 'required|string',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|confirmed',
                'phone'     => 'required|integer',
                'company'   => 'required',
                'address'   => 'required',
                'country'   => 'required',
                'state'     => 'required',
                'city'      => 'required',
                'postcode'  => 'required'
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Create new user
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname'  => $request->lastname,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'phone'     => $request->phone,
                'company'   => $request->company,
                'address'   => $request->address,
                'country'   => $request->country,
                'state'     => $request->state,
                'city'      => $request->city,
                'postcode'  => $request->postcode,
                'otp'       => rand(1000, 9999)
            ]);

            if ($user) {
                return response()->json([
                    'status' => true,
                    'msg'    => 'New user registered successfully.',
                ]);
            }
        } catch (\Exception $e) {
           return $this->getErrorResponse($e);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'email'    => 'required|email',
                'otp'      => 'required',
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }
            
            $user = User::email($request->email)->first();
                
            if ($user) {
                if ($user->otp == $request->otp) {
                    $response = [
                        'status' => true,
                        'msg'    => 'OTP verified'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'msg'    => 'Invalid OTP. Please try again.'
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'msg'    => 'Invalid credentials'
                ];
            }

            return response()->json($response);            
        } catch (\Exception $e) {
           return $this->getErrorResponse($e);
        }
    }

    /**
     * Send reset password token email
     */
    public function forgotPasswordMail(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                $response = [
                    'status' => false,
                    'msg'    => 'This email is not exists in our records.',
                ];
            } else {
                // Generate and send reset password email
                $token = Str::random(64);

                $passwordReset = DB::table('password_reset_tokens')->where('email', $request->email)->first();
                
                if (!empty($passwordReset)) {
                    DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->update(['token' => $token]);
                } else {
                    // Store the token and user's email in the database
                    DB::table('password_reset_tokens')->insert([
                        'email'      => $user->email,
                        'token'      => $token,
                        'created_at' => Carbon::now(),
                    ]);
                }

                // Send mail to user
                // $emailParams = [
                //     'mailTo'       => $user->email,
                //     'mailFrom'     => env('MAIL_FROM_ADDRESS'),
                //     'mailFromName' => 'Adda',
                //     'subject'      => 'Password Reset',
                //     'view'         => 'emails.reset-password'
                // ];

                // $test = Mail::send('emails.reset-password', ['userName' => 'Test', 'token' => $token], function ($message) {
                //     $message->to('chandarbhan887@gmail.com', 'Tutorials Point')
                //         ->subject('Laravel HTML Testing Mail')
                //         ->from(env('MAIL_FROM_ADDRESS'), 'Adda');
                // });
                //  dd($test);

                // $mail = Helper::sendMail($emailParams);

                // if ($mail) {
                    $response = [
                        'status' => true,
                        'msg'    => 'Password reset link sent!',
                    ];
                // }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->getErrorResponse($e);
        } 
    }

    /**
     * Verify token
     */
    public function verifyToken(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required'
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            $passwordReset = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();

            if ($passwordReset) {
                $response = [
                    'status' => true,
                    'msg'    => 'Success',
                ];
            } else {
                $response = [
                    'status' => false,
                    'msg'    => 'Token mismatching!',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->getErrorResponse($e);
        }
    } 

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|confirmed',
            ]);
            
            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();

                $response = [
                    'status' => false,
                    'msg'    => 'Your password has been changed.',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->getErrorResponse($e);
        }    
    }
}
