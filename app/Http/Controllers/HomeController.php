<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;
use App\Models\Banner;

class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('admin.dashboard');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            // Authentication successful
            return redirect('/admin/dashboard');
        }

        // Authentication failed
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    /**
     * Add Banner Image
     */
    public function banner(Request $request)
    {
        $banner = Banner::orderBy('id', 'desc')->paginate(20);
        return view('admin.banner.add', compact('banner'));
    }
    /**
     * Store Banner Image
     */
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('public/banner', $filename);
        Banner::create([
            'image' => 'storage/banner/' . $filename, // Store the path in the database
        ]);

        return back()->with('success', 'Image uploaded successfully.');
    }
    /**
     * Edit Banner Image
     */
    public function editBanner(Request $request, $id)
    {
        $banner = Banner::where('id', $id)->first();
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update Banner image
     */
    public function updateBanner(Request $request, $id)
    {
        // Validate the request, allowing the image field to be optional
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // The image field is now optional
        ]);
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/banner', $filename);
            // Assuming you have a Banner model with an existing record
            $banner = Banner::find($id); // Replace $bannerId with the actual ID of the banner to update

            $banner->image = 'storage/banner/' . $filename;
            $banner->save();
        }
        // You can add other fields and data to update here
        return back()->with('success', 'Banner updated successfully.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
