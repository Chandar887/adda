<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Helpers\Helper;
use App\Models\Category;
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

    /**
     * Get banner images
     */
    public function getBannerImages(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Images not found!',
            ];

            $bannerImages = Banner::select('image')->orderBy('id', 'desc')->limit(4)->get();
            
            if (count($bannerImages) > 0) {
                $response = [
                    'status'  => true,
                    'data'    => $bannerImages
                ];
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Get category list
     */
    public function getCategoryList(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Categories not found!',
            ];

            $categoryList = Category::where('parent', '')->limit(9)->get();
            
            if (count($categoryList) > 0) {
                $response = [
                    'status'  => true,
                    'data'    => $categoryList
                ];
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Get product ratings
     */
    public function getProductRatings(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'No ratings found for this product.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'product_id' => 'required'
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Rating query
            $query = Rating::where('product_id', $request->product_id)->where('user_id', Auth::user()->id);

            if ($query->count() > 0) {
                $ratingsTotal = count($ratings) * 5;
                $ratingsSum = $query->sum('rating');
                $productRating = $ratingsSum / $ratingsTotal;

                $response = [
                    'status' => true,
                    'rating' => $productRating,
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Save product ratings
     */
    public function saveProductRating(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'product_id' => 'required',
                'rating'     => 'required',
            ]);

            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Save rating
            $rating = Rating::create([
                'user_id'     => Auth::user()->id,
                'product_id'  => $request->product_id,
                'rating'      => $request->rating,
                'description' => $request->description,
            ]);

            if ($rating) {
                $response = [
                    'status' => true,
                    'msg'    => 'Rating added for this product.',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }
}
