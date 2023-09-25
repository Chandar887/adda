<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Banner;
use App\Models\Rating;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $orderId = Helper::generateRandomNumber();

        $hasAlreadyExist = Order::where('order_no', $orderId)->first();

        if ($hasAlreadyExist) {
            return $this->generateOrderNumber();
        }

        return $orderId;
    }

    /**
     * Get products list with rating
     */
    private function getProductsWithRating($productsList)
    {
        $productsWithRatings = $productsList->map(function ($product) {
            // Calculating product rating
            $ratingsTotal = $product->ratings->count() * 5;
            $ratingsSum = $product->ratings->sum('rating');
            $productRating = $ratingsSum / $ratingsTotal;
        
            // Add the 'ratings' data to the product
            $product->rating = $productRating;
        
            return $product;
        });

        $response = [
            'status'  => true,
            'data'    => $productsWithRatings
        ];

        return $response;
    }

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
                $ratingsTotal = $query->count() * 5;
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

    /**
     * Get products list
     */
    public function getProducts(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Products not found!',
            ];

            $productsList = Product::with('ratings')->orderBy('id', 'desc')->limit(9)->get();
            
            if (count($productsList) > 0) {
                // Get products with rating
                $response = $this->getProductsWithRating($productsList);
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Get all products
     */
    public function getAllProducts(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Products not found!',
            ];

            $productsList = Product::with('ratings')->orderBy('id', 'desc')->get();
            
            if (count($productsList) > 0) {
                // Get products with rating
                $response = $this->getProductsWithRating($productsList);
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Get orders
     */
    public function getOrders()
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Orders not found.',
            ];

            // Get order with details
            $orders = Order::with('orderDetails')->where('user_id', Auth::user()->id)->get();

            if (count($orders) > 0) {
                $response = [
                    'status' => true,
                    'data'   => $orders,
                ];
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Save order details
     */
    public function saveOrder(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'product_qty'   => 'required',
                'total_amount'  => 'required',
            ]);
            
            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Generate unique order number
            $orderNo = $this->generateOrderNumber();
            
            // Save order
            $order = Order::create([
                'order_no'     => $orderNo,
                'user_id'      => Auth::user()->id,
                'total_amount' => $request->total_amount
            ]);

            if ($order) {
                if (!empty($request->product_qty)) {
                    foreach ($request->product_qty as $val) {
                        // Fetch product details
                        $product = Product::find($val['product_id']);

                        if ($product) {
                            // Save order details
                            $orderDetails = OrderDetail::create([
                                'order_id'     => $order->id,
                                'product_id'   => $product->id,
                                'product_name' => $product->name,
                                'qty'          => $val['qty'],
                                'price'        => $product->sale_price,
                            ]);
                        }
                    }
                }

                $response = [
                    'status' => true,
                    'msg'    => 'Order placed successfully.',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Get favourites
     */
    public function getFavourites(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Products are not added in favourites yet.',
            ];

            // Get favourites
            $favourites = Favourite::where('user_id', Auth::user()->id)->get();

            if (count($favourites) > 0) {
                $response = [
                    'status' => true,
                    'data'   => $favourites,
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Save favourites
     */
    public function saveFavourites(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'product_id' => 'required'
            ]);
            
            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Save favourites
            $favourite = Favourite::create([
                'user_id'    => Auth::user()->id,
                'product_id' => $request->product_id
            ]);

            if ($favourite) {
                $response = [
                    'status' => true,
                    'msg'    => 'Added to favourites.',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }

    /**
     * Delete favourites
     */
    public function deleteFavourites(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'msg'    => 'Opps! Something went wrong.',
            ];

            /* Validation */
            $validation = Validator::make($request->all(), [
                'product_id' => 'required'
            ]);
            
            if ($validation->fails()) {
                $response['errors'] = $validation->errors();
                return response()->json($response);
            }

            // Check if product exist in favourites
            $favorite = Favourite::where([
                'user_id'    => Auth::user()->id,
                'product_id' => $request->product_id
            ])->first();
            
            if ($favorite) {
                // Permanently deleted
                $favorite->delete();

                $response = [
                    'status' => true,
                    'msg'    => 'Removed from favourites.',
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return Helper::getErrorResponse($e);
        }
    }
}
