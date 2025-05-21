<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Calendar;
use App\Models\Price;
use App\Models\Service;
use App\Models\TimeMaster;
use App\Models\User;
use App\Notifications\ApplicationCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{

    public function confirmPage(Application $application)
    {
        return view('confirm', compact('application'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $applications_data = Application::with('user', 'service')
            ->orderBy('date')->orderBy('time')->get();

        $applications = $applications_data->map(function ($application) {
            return [
                'id' => $application->id,
                'tel' => $application->tel,
                'date' => $application->date,
                'time' => $application->time,
                'price' => $application->price,
                'discount' => $application->discount,
                'discounted_price' => $application->discounted_price,
                'duration' => $application->duration,
                'status' => $application->status,
                'master' => User::query()->where('id', $application->master_id)->first(),
                'service' => $application->service,
            ];
        });
        return response()->json($applications, 200);

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
            'tel'=>['required', 'regex:/^\+7 \(\d{3}\) \d{3} \d{2} \d{2}$/u'],
        ], [
            'tel.required'=>'Обязательное поле',
            'tel.regex'=>'Для формата номера телефона +7-(XXX)-XXX-XX-XX',
        ]);

        if ($request->master == '0' || $request->month == '0' || !$request->day || !$request->time) {
            return response()->json('Пожалуйста, заполните все поля', 422);
        }

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::query()->where('id', Auth::id())->first();
        $master = User::query()->where('id', $request->master)->first();
        $service = Service::with('prices')->where('id', $request->service_id)->first();
        $price = Price::query()->where('service_id', $service->id)
            ->where('qualification_id', $master->qualification_id)->first();
        $calendar = Calendar::with('time_masters')->where('id', $request->month)->first();
        $h_mins = explode(':', $request->time);

        $application = new Application();
        $application->date = Carbon::parse($calendar->year . '-' . $calendar->month_number . '-' . $request->day . ' ' . $h_mins[0] . ':' .  $h_mins[1]);
        $application->time = $request->time;
        $application->tel = $request->tel;
        $application->master_id = $master->id;
        $application->service_id = $service->id;
        $application->price = $price->price;
        $application->duration = $price->duration;
        $application->user_id = $user->id;
        $application->discount = $user->discount;
        $application->discounted_price = $price->price - ($price->price * $user->discount / 100);
        $application->save();

        $date_json = TimeMaster::query()->where('calendar_id', $calendar->id)
            ->where('date', 'LIKE', '%"' . $request->day . '":%')->first();


        if (is_string($date_json->date)) {
            $date_data = json_decode($date_json->date, true);
        } else {
            $date_data = $date_json->date;
        }

        if (isset($date_data[$request->day])) {
            foreach ($date_data[$request->day] as $key=>$time) {
                if ($time[0] == $request->time) {
                    $date_data[$request->day][$key][1] = 'Занято';

                }
            }
            $date_json->date = $date_data;
            $date_json->update();
        }

        Notification::send($application->user, new ApplicationCreated($application));
        return redirect()->route('profile');

    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $application = Application::query()->where('id', $request->id)->first();
        $application->status = $request->status;
        $application->update();

        $day = Carbon::parse($application->date)->day;
        $month = Carbon::parse($application->date)->month;
        $year = Carbon::parse($application->date)->year;
        $calendar = Calendar::query()->where('user_id', $request->master_id)
            ->where('month_number', $month)->where('year', $year)->first();

        $date_json = TimeMaster::query()->where('calendar_id', $calendar->id)
            ->where('date', 'LIKE', '%"' . $day . '":%')->first();


        if (is_string($date_json->date)) {
            $date_data = json_decode($date_json->date, true);
        } else {
            $date_data = $date_json->date;
        }

        if (isset($date_data[$day])) {
            foreach ($date_data[$day] as $key=>$time) {
                // Проверяем, совпадает ли время с тем, что мы хотим изменить
                if ($time[0] == $request->time) {
                    if ($request->status == 'Отменено') {
                        $date_data[$day][$key][1] = 'Свободно';
                    }
                }
            }
            $date_json->date = $date_data;
            $date_json->update();
        }

        return response()->json('Изменения сохранены', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
