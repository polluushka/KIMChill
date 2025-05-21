<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualifications = Qualification::all();
        return response()->json($qualifications, 200);
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
            'title' => ['required', 'unique:qualifications']
        ], [
            'title.required' => 'Обязательное поле',
            'title.unique' => 'Такая квалификация уже существует',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $qualification = new Qualification();
        $qualification->title = $request->title;
        $qualification->save();
        return response()->json('Квалификация успешно добавлена', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Qualification $qualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Qualification $qualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title_edit' => ['required', 'unique:qualifications']
        ], [
            'title_edit.required' => 'Обязательное поле',
            'title_edit.unique' => 'Такая квалификация уже существует',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $qualification = Qualification::query()->where('id', $request->id)->first();
        $qualification->title = $request->title_edit;
        $qualification->update();
        return response()->json('Квалификация успешно изменена', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Qualification::destroy($request->id);
    }
}
