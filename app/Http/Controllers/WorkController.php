<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'img' => ['required']
        ], [
            'img.required' => 'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        foreach ($request->file('img') as $img) {
            $work = new Work();
            $work->user_id = $request->master_id;
            $path = $img->store('img/works');
            $work->img = 'storage/' . $path;
            $work->save();
        }
        return response()->json('Новые работы добавлены', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Work::destroy($request->id);
    }
}
