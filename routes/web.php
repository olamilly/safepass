<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\StatefulGuard;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
//use Illuminate\Auth\Middleware\Authenticate;

use App\Models\User;
Route::post('/addnewrecord', [ApplicationController::class, 'addNewRecord']);
Route::get('/allrecords', [ApplicationController::class, 'getAllRecords']);
Route::get('/getrecordpassword/{id}', [ApplicationController::class, 'getMyRecord']);
Route::post('/deleterecord/{id}', [ApplicationController::class, 'deleteMyRecord']);
Route::post('/editrecord', [ApplicationController::class, 'editRecord']);
Route::get('/api/user', function () {
    if (auth()->user()){
        return auth()->user();
    }
    return null;
});



Route::view('/{any}', 'layouts.app')->where('any', '.*');