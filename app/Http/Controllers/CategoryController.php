<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        //
        $categories = \App\Category::paginate(10);
        $filterKeyword = $request->get('name');

        if ($filterKeyword) {
            $categories = \App\Category::where("name", "LIKE", "%$filterKeyword%")->paginate(10);
        }

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $name = $request->get('name');

        $category = new \App\Category;
        $category->name = $name;

        if ($request->file('image')) 
        {
            $image_path = $request->file('image')->store('category_images', 'public');
            $category->image = $image_path;
        }

        $category->created_by = \Auth::user()->id;
        $category->slug = Str::slug($name, '-');
        $category->save();

        return redirect()->route('categories.create')->with('status', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $category = \App\Category::findOrFail($id);

        return view('categoried.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category_to_edit = \App\Category::findOrFail($id);

        return view('categories.edit', ['category' => $category_to_edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $name = $request->get('name');
        $slug = $request->get('slug');

        $category = \App\Category::findOrFail($id);
        $category->name = $name;
        $category->slug = $slug;

        if ($request->file('image')) 
        {
            if ($category->image && file_exists(storage_path('app/public/'. $category->image))) 
            {
                \Storage::delete('public/'. $category->name);
            }

            $new_image = $request->file('image')->store('category_images', 'public');

            $category->image = $new_image;
        }

        $category->updated_by = \Auth::user()->id;
        $category->slug = \Str::slug($name);
        $category->save();

        return redirect()->route('categories.edit', [$id])->with('status', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $category = \App\Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Data Berhasil dipindahkan ke softdelete');
    }

    public function trash()
    {
        $delete = \App\Category::onlyTrashed()->paginate(10);

        return view('categories.trash', ['categories' => $delete]);
    }

    public function restore($id)
    {
        $category = \App\Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) 
        {
            $category->restore();
        }
        else
        {
            return redirect()->route('categories.index')->with('status','Data Tidak ada di trash');
        }

        return redirect()->route('categories.index')->with('status','Data berhasil direstore');
    }

    public function deletePermanent($id)
    {
        $category = \App\Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) 
        {
            return redirect()->route('categories.index')->with('status', 'Can not delete permanent active category');
        }
        else
        {
            $category->forceDelete();
            return redirect()->route('categories.index')->with('status','Category permanently deleted');
        }
    }

    public function ajaxSearch(Request $request)
    {
        $keyword = $request->get('q');

        $category = \App\Category::where("name", "LIKE", "%$keyword%")->get();

        return $category;
    }
}
