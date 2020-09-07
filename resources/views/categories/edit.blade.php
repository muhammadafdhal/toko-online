@extends('layouts.template')

@section('title')
Edit Category
@endsection

@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
@endif

<div class="col-md-8">
    <form action="{{route('categories.update', [$category->id])}}" enctype="multipart/form-data" method="POST"
        class="bg-white shadow-sm p-3">
        @csrf
        <input type="hidden" value="PUT" name="_method">

        <label>Category name</label> <br>
        <input type="text" class="form-control" value="{{$category->name}}" name="name">
        <br><br>

        <label>Cateogry slug</label>
        <input type="text" class="form-control" value="{{$category->slug}}" name="slug">
        <br><br>

        @if($category->image)
        <span>Current image</span><br>
        <img src="{{asset('storage/'. $category->image)}}" width="120px">
        <br><br>
        @endif
        <input type="file" class="form-control" name="image">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah
            gambar</small>
        <br><br>
    </form>
</div>

@endsection
