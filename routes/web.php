<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\AuthUser;
use App\Http\Middleware\Master;
use App\Http\Middleware\NoAuthUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;



//get
Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/masters', [PageController::class, 'masters'])->name('masters');
Route::get('/masters/{id}', [PageController::class, 'master'])->name('master');
Route::get('/services/filter/{id}', [PageController::class, 'services_filter'])->name('services_filter');


//get-data
Route::get('/categories/get', [CategoryController::class, 'index'])->name('getCategories');
Route::get('/qualifications/get', [QualificationController::class, 'index'])->name('getQualifications');
Route::get('/masters/active/get', [UserController::class, 'active_masters'])->name('getActiveMasters');
Route::get('/masters/all/get', [UserController::class, 'all_masters'])->name('getAllMasters');
Route::get('/services/get', [ServiceController::class, 'index'])->name('getServices');
Route::get('/calendars/get', [CalendarController::class, 'index'])->name('getCalendars');
Route::get('/reviews/get', [ReviewController::class, 'index'])->name('getReviews');


//post-data
Route::post('/master/get', [UserController::class, 'show_master'])->name('getMaster');


//no-auth-user
Route::middleware(NoAuthUser::class)->group(function () {
    //get
    Route::get('/registration', [PageController::class, 'registration'])->name('registration');
    Route::get('/authorization', [PageController::class, 'authorization'])->name('authorization');

    //post
    Route::post('/reg', [UserController::class, 'reg'])->name('reg');
    Route::post('/auth', [UserController::class, 'auth'])->name('auth');
});


//auth-user
Route::middleware(AuthUser::class)->group(function () {
    //get
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    //get-data
    Route::get('/user/get', [UserController::class, 'show_me'])->name('getUser');

    //post
    Route::post('/review/save', [ReviewController::class, 'store'])->name('storeReview');

    //post-edit
    Route::post('/user/edit', [UserController::class, 'update'])->name('editUser');
    Route::post('/user/password/edit', [UserController::class, 'update_password'])->name('editPasswordUser');
    Route::post('/review/edit', [ReviewController::class, 'update'])->name('editReview');

    //post-delete
    Route::post('/user/delete', [UserController::class, 'destroy'])->name('deleteUser');

});


//master
Route::middleware(Master::class)->group(function () {
    //get
    Route::get('/master/profile', [PageController::class, 'masterProfile'])->name('masterProfile');

    //get-data
    Route::get('/master/me/get', [UserController::class, 'show_master_me'])->name('getMeMaster');

    //post
    Route::post('/work/save', [WorkController::class, 'store'])->name('saveWork');

    //post-delete
    Route::post('/work/delete', [WorkController::class, 'destroy'])->name('deleteWork');
});


//admin
Route::middleware(Admin::class)->group(function () {
    //get
    Route::get('/admin', [PageController::class, 'admin'])->name('admin');

    //get-data
    Route::get('/admins/get', [UserController::class, 'admins'])->name('getAdmins');
    Route::get('/users/get', [UserController::class, 'index'])->name('getUsers');
    Route::get('/applications/get', [ApplicationController::class, 'index'])->name('getApplications');

    //post-save
    Route::post('/category/save', [CategoryController::class, 'store'])->name('saveCategory');
    Route::post('/qualification/save', [QualificationController::class, 'store'])->name('saveQualification');
    Route::post('/master/save', [UserController::class, 'create_master'])->name('saveMaster');
    Route::post('/admin/save', [UserController::class, 'create_admin'])->name('saveAdmin');
    Route::post('/service/save', [ServiceController::class, 'store'])->name('saveService');
    Route::post('/calendar/save', [CalendarController::class, 'store'])->name('saveCalendar');

    //post-edit
    Route::post('/category/edit', [CategoryController::class, 'update'])->name('editCategory');
    Route::post('/qualification/edit', [QualificationController::class, 'update'])->name('editQualification');
    Route::post('/master/edit', [UserController::class, 'update_master'])->name('editMaster');
    Route::post('/service/edit', [ServiceController::class, 'update'])->name('editService');
    Route::post('/application/edit', [ApplicationController::class, 'update'])->name('editStatusApplication');

    //post-delete
    Route::post('/category/delete', [CategoryController::class, 'destroy'])->name('deleteCategory');
    Route::post('/qualification/delete', [QualificationController::class, 'destroy'])->name('deleteQualification');
    Route::post('/master/delete', [UserController::class, 'delete_master'])->name('deleteMaster');
    Route::post('/admin/delete', [UserController::class, 'delete_admin'])->name('deleteAdmin');
    Route::post('/service/delete', [ServiceController::class, 'destroy'])->name('deleteService');
    Route::post('/calendar/delete', [CalendarController::class, 'destroy'])->name('deleteCalendar');
});

//post-save
Route::post('/application/save', [ApplicationController::class, 'store'])->name('saveApplication');

Route::get('/application/confirm/{application}', [ApplicationController::class, 'confirmPage'])->name('confirm');

Route::post('/save-subscription', [SubscriptionController::class, 'save'])->middleware('auth');









