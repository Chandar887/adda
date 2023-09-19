@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Category</a>

    <ul>
        @foreach ($categories as $category)
            <li>{{ $category->name }}</li>
            @if ($category->subcategories->count() > 0)
                <ul>
                    @foreach ($category->subcategories as $subcategory)
                        <li>{{ $subcategory->name }}</li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    </ul>
</div>
@endsection
