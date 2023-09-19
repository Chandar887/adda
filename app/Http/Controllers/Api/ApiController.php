<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Dashboard
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user()
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            
            return response()->json([
                'status'  => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }
}
