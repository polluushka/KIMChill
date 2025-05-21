<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\TimeMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $current = Carbon::now();
        $months = [];

        for ($i = 0; $i < 4; $i++) {
            $months[] = [
                'year' => $current->year,
                'month' => $current->month
            ];
            $current->addMonth();
        }

        $calendars_data = Calendar::with('time_masters', 'user')
            ->where(function($query) use ($months) {
                foreach ($months as $month) {
                    $query->orWhere(function($q) use ($month) {
                        $q->where('year', $month['year'])
                            ->where('month_number', $month['month']);
                    });
                }
            })->get();

        $calendars = $calendars_data->map(function ($calendar) {
            return [
                'id' => $calendar->id,
                'month_number' => $calendar->month_number,
                'month_name' => $calendar->month_name,
                'year' => $calendar->year,
                'master_id' => $calendar->user_id,
                'master_name' => $calendar->user->name,
                'dates' => $calendar->time_masters->map(function ($time_master) {
                    return $time_master->date;
                }),
            ];
        });

        return response()->json($calendars, 200);
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

        if ($request->master == '0' || $request->month == '0' || $request->time === [0 => null] || !$request->day) {
            return response()->json('Пожалуйста, заполните все поля', 400);
        }

        $year = explode('_', $request->month)[1];
        $month = explode('_', $request->month)[0];
        $calendar = Calendar::query()
            ->where('user_id', $request->master)
            ->where('year', $year)->where('month_number', $month)
            ->first();
        if (!$calendar) {
            $calendar = new Calendar();
            $calendar->user_id = $request->master;
            $calendar->month_number = $month;
            $calendar->month_name = $request->month_name;
            $calendar->year = $year;
            $calendar->save();
        }

        $date_json = TimeMaster::query()->where('calendar_id', $calendar->id)
            ->where('date', 'LIKE', '%"' . $request->day . '":%')->first();

        if ($date_json) {
            if (is_string($date_json->date)) {
                $date_data = json_decode($date_json->date, true);
            } else {
                $date_data = $date_json->date;
            }

            if (isset($date_data[$request->day])) {
                $existing_times = $date_data[$request->day];

                // Фильтруем существующее время, удаляя те, что свободны
                $filtered_times = array_filter($existing_times, function ($time) {
                    return $time[1] !== 'Свободно';
                });

                // Добавляем новые времена из запроса
                $new_times = [];
                if ($request->time) {
                    foreach ($request->time as $time) {
                        $new_times[] = [$time, 'Свободно'];
                    }
                }

                // Объединяем отфильтрованные существующие времена с новыми
                $date_data[$request->day] = array_merge($filtered_times, $new_times);

                // Сохраняем обновленный JSON
                $date_json->date = $date_data;
                $date_json->update();
            }
        } else {
            if (!$request->time) {
                return response()->json('Пожалуйста, заполните все поля', 400);
            } else {
                $date = new TimeMaster();
                $array_day = [];
                foreach ($request->time as $time) {
                    $array_time = [$time, 'Свободно'];
                    array_push($array_day, $array_time);
                }
                $date->date = [$request->day => $array_day];
                $date->calendar_id = $calendar->id;
                $date->save();
            }
        }

        return response()->json('Свободные окошки успешно добавлены', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Calendar $calendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Calendar::destroy($request->id);
    }
}
