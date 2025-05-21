<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show_me()
    {
        $user_data = User::with('review')->where('id', Auth::id())->first();

        $applications_future = Application::with('service')->where('user_id', Auth::id())
            ->where('status', 'Запланировано')->orderBy('date')->orderBy('time')->get();

        $applications_past = Application::with('service')->where('user_id', Auth::id())
            ->where('status', 'Проведено')->orderByDesc('date')->orderByDesc('time')->get();

        $user = [
            'id' => $user_data->id,
            'name' => $user_data->name,
            'surname' => $user_data->surname,
            'tel' => $user_data->tel,
            'birthday' => $user_data->birthday,
            'discount' => $user_data->discount,
            'img' => $user_data->img,
            'applications_future' => $applications_future->map(function ($application) {
                return [
                    'id' => $application->id,
                    'date' => $application->date,
                    'time' => $application->time,
                    'price' => $application->price,
                    'discount' => $application->discount,
                    'discounted_price' => $application->discounted_price,
                    'duration' => $application->duration,
                    'service' => $application->service,
                    'master' => User::query()->where('role', 'master')
                        ->where('id', $application->master_id)->first(),
                ];
            }),
            'applications_past' => $applications_past->map(function ($application) {
                return [
                    'id' => $application->id,
                    'date' => $application->date,
                    'time' => $application->time,
                    'discounted_price' => $application->discounted_price,
                    'service' => $application->service,
                    'master' => User::query()->where('role', 'master')
                        ->where('id', $application->master_id)->first(),
                ];
            }),
            'review' => $user_data->review,
        ];
        return response()->json($user, 200);
    }

    public function show_master_me()
    {
        $master_data = User::with('qualification.prices', 'calendars.time_masters', 'works')
            ->where('id', Auth::id())->first();

        $applications_future = Application::with('service')->where('master_id', Auth::id())
            ->where('status', 'Запланировано')->orderBy('date')->orderBy('time')->get();

        $applications_past = Application::with('service')->where('master_id', Auth::id())
            ->where('status', 'Проведено')->orderByDesc('date')->orderByDesc('time')->get();

        $master = [
            'id' => $master_data->id,
            'name' => $master_data->name,
            'specialization' => $master_data->specialization,
            'qualification_id' => $master_data->qualification_id,
            'qualification' => $master_data->qualification,
            'description' => $master_data->description,
            'img' => $master_data->img,
            'calendars' => $master_data->calendars->map(function ($calendar) {
                return [
                    'id' => $calendar->id,
                    'month_number' => $calendar->month_number,
                    'month_name' => $calendar->month_name,
                    'year' => $calendar->year,
                    'dates' => $calendar->time_masters->map(function ($time_master) {
                        return $time_master->date;
                    }),
                ];
            }),
            'services' => $master_data->master_services->map(function ($master_service) {
                return [
                    $master_service->service,
                ];
            }),
            'applications_future' => $applications_future->map(function ($application) {
                return [
                    'id' => $application->id,
                    'tel' => $application->tel,
                    'date' => $application->date,
                    'time' => $application->time,
                    'price' => $application->price,
                    'discount' => $application->discount,
                    'discounted_price' => $application->discounted_price,
                    'duration' => $application->duration,
                    'service' => $application->service,
                ];
            }),
            'applications_past' => $applications_past->map(function ($application) {
                return [
                    'id' => $application->id,
                    'date' => $application->date,
                    'time' => $application->time,
                    'discounted_price' => $application->discounted_price,
                    'service' => $application->service,
                ];
            }),
            'works' => $master_data->works->sortByDesc('created_at')->values()->map(function ($work) {
                return [
                    'id' => $work->id,
                    'img' => $work->img,
                ];
            }),

        ];
        return response()->json($master, 200);
    }

    public function show_master(Request $request)
    {
        $master_data = User::with('qualification.prices', 'calendars.time_masters', 'master_services.service', 'works')
            ->where('id', $request->id)->first();
        $currentMonth = Carbon::now()->month;

        $master = [
            'id' => $master_data->id,
            'name' => $master_data->name,
            'specialization' => $master_data->specialization,
            'qualification_id' => $master_data->qualification_id,
            'qualification' => $master_data->qualification,
            'description' => $master_data->description,
            'img' => $master_data->img,
            'services' => $master_data->master_services->map(function ($master_service) {
                return $master_service->service;
            }),
            'works' => $master_data->works->sortByDesc('created_at')->values()->map(function ($work) {
                return $work->img;
            }),
            'calendars' => $master_data->calendars->filter(function ($calendar) use ($currentMonth) {
                return $calendar->month_number >= $currentMonth;
            })->map(function ($calendar) {
                return [
                    'id' => $calendar->id,
                    'month_number' => $calendar->month_number,
                    'month_name' => $calendar->month_name,
                    'year' => $calendar->year,
                    'dates' => $calendar->time_masters->map(function ($time_master) {
                        return $time_master->date;
                    }),
                ];
            })->values()->toArray(),
        ];
        return response()->json($master, 200);
    }

    public function index()
    {
        $users = User::query()->where('role', 'user')->get();
        return response()->json($users, 200);
    }

    public function all_masters()
    {
        $masters = User::with('qualification', 'calendars.time_masters')
            ->where('role', 'master')->get();
        return response()->json($masters, 200);
    }

    public function active_masters()
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

        $masters_data = User::with(['qualification', 'master_services.service', 'calendars' => function($query) use ($months) {
            $query->where(function($q) use ($months) {
                foreach ($months as $month) {
                    $q->orWhere(function($innerQ) use ($month) {
                        $innerQ->where('year', $month['year'])
                            ->where('month_number', $month['month']);
                    });
                }
            })->with('time_masters');
        }])->where('role', 'master')
            ->whereHas('calendars', function($query) use ($months) {
                $query->where(function($q) use ($months) {
                    foreach ($months as $month) {
                        $q->orWhere(function($innerQ) use ($month) {
                            $innerQ->where('year', $month['year'])
                                ->where('month_number', $month['month']);
                        });
                    }
                });
            })
            ->get();

        $masters = $masters_data->map(function ($master) {
            return [
                'id' => $master->id,
                'name' => $master->name,
                'specialization' => $master->specialization,
                'qualification_id' => $master->qualification_id,
                'qualification' => $master->qualification,
                'description' => $master->description,
                'calendars' => $master->calendars->map(function ($calendar) {
                    return [
                        'id' => $calendar->id,
                        'month_number' => $calendar->month_number,
                        'month_name' => $calendar->month_name,
                        'year' => $calendar->year,
                        'dates' => $calendar->time_masters->map(function ($time_master) {
                            return $time_master->date;
                        }),
                    ];
                }),
                'services' => $master->master_services->map(function ($master_service) {
                    return $master_service->service;
                }),
            ];
        });

        return response()->json($masters, 200);
    }

    public function create_master(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user'=>['required'],
            'specialization'=>['required'],
            'qualification'=>['required'],
            'description'=>['required'],
        ], [
            'user.required'=>'Обязательное поле',
            'specialization.required'=>'Обязательное поле',
            'qualification.required'=>'Обязательное поле',
            'description.required'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if ($request->qualification == '0' || $request->user == '0') {
            return response()->json('Пожалуйста, заполните все поля', 422);
        }

        $master = User::query()->where('id', $request->user)->first();
        $master->role = 'master';
        $master->specialization = $request->specialization;
        $master->qualification_id = $request->qualification;
        $master->description = $request->description;
        $master->update();
        return response()->json('Мастер успешно добавлен', 200);
    }

    public function update_master(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'specialization_edit'=>['required'],
            'qualification_edit'=>['required'],
            'description_edit'=>['required'],
        ], [
            'specialization_edit.required'=>'Обязательное поле',
            'qualification_edit.required'=>'Обязательное поле',
            'description_edit.required'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $master = User::query()->where('id', $request->id)->first();
        $master->specialization = $request->specialization_edit;
        $master->qualification_id = $request->qualification_edit;
        $master->description = $request->description_edit;
        $master->update();
        return response()->json('Информация о мастере успешно изменена', 200);
    }

    public function delete_master(Request $request)
    {
        $master = User::query()->where('id', $request->id)->first();
        $master->role = 'user';
        $master->qualification_id = null;
        $master->update();
        return response()->json('Мастер удалён', 200);
    }

    public function admins()
    {
        $admin = User::query()->where('role', 'admin')->get();
        return response()->json($admin, 200);
    }

    public function create_admin(Request $request)
    {
        if ($request->user == '0') {
            return response()->json('Пожалуйста, заполните все поля', 400);
        }

        $admin = User::query()->where('id', $request->user)->first();
        $admin->role = 'admin';
        $admin->update();
        return response()->json('Админ успешно добавлен', 200);
    }

    public function delete_admin(Request $request)
    {
        $admin = User::query()->where('id', $request->id)->first();
        $admin->role = 'user';
        $admin->update();
        return response()->json('Администратор удалён', 200);
    }

    public function reg(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'=>['required', 'regex:/[А-Яа-яЁё]/u'],
            'surname'=>['nullable', 'regex:/[А-Яа-яЁё\s\-]/u'],
            'tel'=>['required', 'unique:users', 'regex:/^\+7 \(\d{3}\) \d{3} \d{2} \d{2}$/u'],
            'birthday'=>['required'],
            'password'=>['required', 'min:8', 'confirmed'],
            'password_confirmation'=>['required'],
            'rules'=>['required']
        ], [
            'name.required'=>'Обязательное поле',
            'name.regex'=>'Разрешены только символы кириллицы',
            'surname.required'=>'Обязательное поле',
            'surname.regex'=>'Разрешены только символы кириллицы, пробел и тире',
            'tel.required'=>'Обязательное поле',
            'tel.regex'=>'Для формата номера телефона +7-(XXX)-XXX-XX-XX',
            'tel.unique'=>'Пользователь с таким номером телефона уже есть',
            'birthday.required'=>'Обязательное поле',
            'password.required'=>'Обязательное поле',
            'password.min'=>'Пароль должен содержать минимум 8 символов',
            'password.confirmed'=>'Пароли должны совпадать',
            'password_confirmation.required'=>'Обязательное поле',

        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->tel = $request->tel;
        $user->birthday = $request->birthday;
        $user->password = Hash::make($request->password);
        $user->img = '';
        if (User::all()->count() == 0) {
            $user->role = 'admin';
        }
        $user->save();
        Auth::login($user);
        return redirect()->route('profile');
    }

    public function auth(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'tel'=>['required', 'regex:/^\+7 \(\d{3}\) \d{3} \d{2} \d{2}$/u'],
            'password'=>['required'],
        ],
            [
                'tel.required'=>'Обязательное поле',
                'tel.regex'=>'Для формата номера телефона +7-(XXX)-XXX-XX-XX',
                'password.required'=>'Обязательное поле',
            ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::query()->where('tel', $request->tel)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                if ($user->role === 'user') {
                    return redirect()->route('profile');
                }
                if ($user->role === 'master') {
                    return redirect()->route('masterProfile');
                }
                if ($user->role === 'admin') {
                    return redirect()->route('admin');
                }

            } else {
                return response()->json('Неверный логин или пароль', 403);
            }
        } else {
            return response()->json('Неверный логин или пароль', 403);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'=>['required', 'regex:/[А-Яа-яЁё]/u'],
            'surname'=>['nullable', 'regex:/[А-Яа-яЁё\s\-]/u'],
            'tel'=>['required', 'regex:/^\+7 \(\d{3}\) \d{3} \d{2} \d{2}$/u'],
            'img' => ['image', 'mimes:jpeg,jpg,png', 'nullable']
        ], [
            'name.required'=>'Обязательное поле',
            'name.regex'=>'Разрешены только символы кириллицы',
            'surname.required'=>'Обязательное поле',
            'surname.regex'=>'Разрешены только символы кириллицы, пробел и тире',
            'tel.required'=>'Обязательное поле',
            'tel.regex'=>'Для формата номера телефона +7-(XXX)-XXX-XX-XX',
            'img.image'=>'Только для изображения',
            'img.mimes'=>'Разрешённые форматы: jpeg, jpg, png',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::query()->where('tel', $request->tel)
            ->where('id', '!=', Auth::id())->first();

        if (!$user) {
            $user = User::query()->where('id', Auth::id())->first();
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->tel = $request->tel;
            if ($request->file('img')) {
                $path = $request->file('img')->store('img/users/');
                $user->img = 'storage/' . $path;
            }
            $user->update();
            return response()->json('Данные успешно изменены', 200);
        } else {
            $validated_tel = Validator::make($request->all(), [
                'tel'=>['unique:users'],
            ], [
                'tel.unique'=>'Пользователь с таким номером телефона уже есть',
            ]);
            return response()->json($validated_tel->errors(), 400);
        }


    }

    public function update_password(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'password'=>['required', 'min:8', 'confirmed'],
            'password_confirmation'=>['required'],
        ], [
            'password.required'=>'Обязательное поле',
            'password.min'=>'Пароль должен содержать минимум 8 символов',
            'password.confirmed'=>'Пароли должны совпадать',
//            'password.regex'=>'Пароль должен содержать буквы латиницы, цифры и специальные символы',
            'password_confirmation.required'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::query()->where('id', Auth::id())->first();
        $user->password = Hash::make($request->password);
        $user->update();
        return response()->json('Пароль успешно изменён', 200);

    }

    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'password_delete'=>['required'],
        ], [
            'password_delete.required'=>'Обязательное поле',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $user = User::query()->where('id', Auth::id())->first();
        if (Hash::check($request->password_delete, $user->password)) {
            User::destroy($user->id);
        } else {
            return response()->json('Неверный пароль', 403);
        }

        return redirect()->route('welcome', 200);

    }
}
