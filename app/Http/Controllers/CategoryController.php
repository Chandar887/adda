<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->whereNull('parent')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::with('subcategories')->whereNull('parent')->get();
        return view('admin.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

       $countCat = Category::where('name', $request->name)->count();
       if($countCat > 0) {
        return redirect()->route('admin.categories.index')->with('success', 'This Menu is already Exist.');
       }

        Category::create([
            'name' => $request->input('name'),
            'parent' => $request->input('parent'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }
}
