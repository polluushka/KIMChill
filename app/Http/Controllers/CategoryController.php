<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => ['required', 'unique:categories']
        ], [
            'title.required' => 'Обязательное поле',
            'title.unique' => 'Такая категория уже существует',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $category = new Category();
        $category->title = $request->title;
        $category->save();
        return response()->json('Категория успешно добавлена', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title_edit' => ['required', 'unique:categories']
        ], [
            'title_edit.required' => 'Обязательное поле',
            'title_edit.unique' => 'Такая категория уже существует',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $category = Category::query()->where('id', $request->id)->first();
        $category->title = $request->title_edit;
        $category->update();
        return response()->json('Категория успешно изменена', 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Category::destroy($request->id);
    }
}
