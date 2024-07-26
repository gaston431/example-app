<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Jobs\TranslateJob;
use App\Mail\JobPosted;
use App\Models\Job;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

/* $jobs = [
    [
        'id' => 1,
        'title' => 'Director',
        'salary' => '50,000',
    ],
    [
        'id' => 2,
        'title' => 'Programmer',
        'salary' => '10,000',
    ],
    [
        'id' => 3,
        'title' => 'Teacher',
        'salary' => '40,000',
    ],
]; */


/* Route::get('/', function () {
    return view('index');
}); */
Route::view('/', 'index');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::middleware('auth')->group(function () {
    Route::get('/jobs', [JobController::class,'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobController::class,'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class,'store'])->middleware('auth')->name('jobs.store');
    Route::get('/jobs/{job}', [JobController::class,'show'])->name('jobs.show');
    
    Route::get('/jobs/{job}/edit', [JobController::class,'edit'])
        ->middleware('auth')
        ->can('edit', 'job')
        ->name('jobs.edit');

    Route::patch('/jobs/{job}', [JobController::class,'update'])->name('jobs.update');
    Route::delete('jobs/{job}', [JobController::class,'destroy'])->name('jobs.destroy');

});

//Route::resource('jobs', JobController::class)->middleware('auth');

// Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('test', function () {
    $job = Job::first();

    TranslateJob::dispatch($job);

    /* dispatch(function (){
        logger('hello there');
    })->delay(5); */
    
    return 'Done';
});