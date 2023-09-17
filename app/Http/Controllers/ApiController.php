<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
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
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'msg'    => 'New user registered successfully.',
                    'token'  => $token
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

                    $user->otp = '';
                    $user->save();
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
}
