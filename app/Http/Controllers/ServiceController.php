<?php

namespace App\Http\Controllers;

use App\Models\MasterService;
use App\Models\Price;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services_data = Service::with('category', 'prices.qualification', 'master_services.user')->get();
        $services = $services_data->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'category_id' => $service->category_id,
                'category' => $service->category,
                'description' => $service->description,
                'qualifications' => $service->prices->map(function ($price) {
                    return [
                        'id' => $price->qualification->id,
                        'duration' => $price->duration,
                        'price' => $price->price,
                    ];
                }),
                'masters' => $service->master_services->map(function ($master_service) {
                    return [
                        'id' => $master_service->user->id,
                        'qualification_id' => $master_service->user->qualification_id,
                    ];
                }),
            ];
        });
        return response()->json($services, 200);
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
            'title'=>['required'],
            'category'=>['required'],
            'qualification'=>['required'],
            'duration'=>['required'],
            'price'=>['required'],
            'master'=>['required'],
        ], [
            'title.required'=>'Обязательное поле',
            'category.required'=>'Обязательное поле',
            'qualification.required'=>'Обязательное поле',
            'duration.required'=>'Обязательное поле',
            'price.required'=>'Обязательное поле',
            'price.master'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if ($request->category == '0') {
            return response()->json('Пожалуйста, заполните все обязательные поля', 422);
        }

        foreach ($request->qualification as $qualification) {
            if ($qualification == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->duration as $duration) {
            if ($duration == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->price as $price) {
            if ($price == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->master as $master) {
            if ($master == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        $service = new Service();
        $service->title = $request->title;
        $service->category_id = $request->category;
        $service->description = $request->description;
        $service->save();

        foreach ($request->qualification as $key=>$qualification) {
            $price = new Price();
            $price->price = $request->price[$key];
            $price->duration = $request->duration[$key];
            $price->service_id = $service->id;
            $price->qualification_id = $qualification;
            $price->save();
        }

        foreach ($request->master as $master) {
            $master_service = new MasterService();
            $master_service->user_id = $master;
            $master_service->service_id = $service->id;
            $master_service->save();
        }

        return response()->json('Услуга успешно добавлена', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title_edit'=>['required'],
            'qualification_edit'=>['required'],
            'duration_edit'=>['required'],
            'price_edit'=>['required'],
            'master_edit'=>['required'],
        ], [
            'title_edit.required'=>'Обязательное поле',
            'qualification_edit.required'=>'Обязательное поле',
            'duration_edit.required'=>'Обязательное поле',
            'price_edit.required'=>'Обязательное поле',
            'master_edit.master'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        foreach ($request->qualification_edit as $qualification) {
            if ($qualification == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->duration_edit as $duration) {
            if ($duration == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->price_edit as $price) {
            if ($price == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        foreach ($request->master_edit as $master) {
            if ($master == '0') {
                return response()->json('Пожалуйста, заполните все обязательные поля', 422);
            }
        }

        $service = Service::query()->where('id', $request->id)->first();
        $service->title = $request->title_edit;
        $service->category_id = $request->category_edit;
        $service->description = $request->description_edit;
        $service->update();

        $prices = Price::query()->where('service_id', $service->id)->get();
        foreach ($prices as $price) {
            $price->delete();
        }

        $master_services = MasterService::query()->where('service_id', $service->id)->get();
        foreach ($master_services as $master_service) {
            $master_service->delete();
        }

        foreach ($request->qualification_edit as $key=>$qualification) {
            $price = new Price();
            $price->price = $request->price_edit[$key];
            $price->duration = $request->duration_edit[$key];
            $price->service_id = $service->id;
            $price->qualification_id = $qualification;
            $price->save();
        }

        foreach ($request->master_edit as $master) {
            $master_service = new MasterService();
            $master_service->user_id = $master;
            $master_service->service_id = $service->id;
            $master_service->save();
        }

        return response()->json('Услуга успешно изменена', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Service::destroy($request->id);
    }
}
