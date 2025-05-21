<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function welcome()
    {
        return view('welcome');
    }

    public function registration()
    {
        return view('guest.reg');
    }

    public function authorization()
    {
        return view('guest.auth');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function admin()
    {
        return view('admin.panel');
    }

    public function masterProfile()
    {
        return view('master.profile');
    }

    public function services()
    {
        return view('services');
    }

    public function masters()
    {
        return view('masters');
    }

    public function master($id)
    {
        $master = User::with('qualification', 'calendars.time_masters')
            ->where('id', $id)->first();
        return view('master', ['master'=>$master]);
    }

    public function services_filter($id)
    {
        $services = Service::query()->where('category_id', $id)->get();
        return view('services_filter', ['services'=>$services]);
    }
}
