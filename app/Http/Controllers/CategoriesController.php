<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests;
use App\Http\Resources\Categories as CategoryResource;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }
    public function index()
    {
        
        $categories=Category::orderBy('created_at','desc')->paginate(5);
        return CategoryResource::collection($categories);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category=$request->isMethod('put') ? Category::findOrFail
        ($request->category_id):new Category;
        $category->id=$request->input('category_id');
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        if($category->save()){
            return new CategoryResource($category);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=Category::findorFail($id);

        // Return the product as a resource
        return new CategoryResource($category);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::findorFail($id);

        if($category->delete()){
             return new CategoryResource($category);
        }
    }
}
