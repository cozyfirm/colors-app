<?php

use App\Http\Controllers\Admin\SystemCore\ClubsController;
use App\Http\Controllers\Admin\SystemCore\LeagueController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Public\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('')->group(function () {
    /**
     *  Auth routes
     */
    Route::prefix('/auth')->group(function (){
        Route::get('/',                                [AuthController::class, 'auth'])->name('public.auth');
        Route::post('/authenticate',                   [AuthController::class, 'authenticate'])->name('public.auth.authenticate');

        /* Logout */
        Route::get('/logout',                          [AuthController::class, 'logout'])->name('public.auth.logout');
    });
});

/**
 *  Admin routes: Manage complete app
 */
Route::prefix('/admin')->group(function () {
    /**
     *  Users routes
     */
    Route::prefix('/users')->group(function (){
        /*
         *  Admin role routes
         */
        Route::get('/',                                [UsersController::class, 'index'])->name('admin.users.index');
        Route::get('/preview/{username}',              [UsersController::class, 'preview'])->name('admin.users.preview');
        Route::get('/edit/{username}',                 [UsersController::class, 'edit'])->name('admin.users.edit');
        Route::get('/update',                          [UsersController::class, 'update'])->name('admin.users.update');

        /*
         *  User routes
         */
        Route::get('/my-profile',                      [UsersController::class, 'myProfile'])->name('admin.users.my-profile');
        Route::get('/update-profile',                  [UsersController::class, 'updateProfile'])->name('admin.users.update-profile');
    });

    /**
     *  System core
     */
    Route::prefix('/core')->group(function (){
        /**
         *  Clubs
         */
        Route::prefix('/clubs')->group(function (){
            Route::get ('/',                                [ClubsController::class, 'index'])->name('admin.core.clubs');
            Route::get ('/create',                          [ClubsController::class, 'create'])->name('admin.core.clubs.create');
            Route::post('/save',                            [ClubsController::class, 'save'])->name('admin.core.clubs.save');
            Route::get ('/preview/{id}',                    [ClubsController::class, 'preview'])->name('admin.core.clubs.preview');
            Route::get ('/edit/{id}',                       [ClubsController::class, 'edit'])->name('admin.core.clubs.edit');
            Route::post('/update',                          [ClubsController::class, 'update'])->name('admin.core.clubs.update');
            Route::get ('/delete/{id}',                     [ClubsController::class, 'delete'])->name('admin.core.clubs.delete');
        });

        /**
         *  Leagues
         */
        Route::prefix('/leagues')->group(function (){
            Route::get ('/',                                [LeagueController::class, 'index'])->name('admin.core.league');
            Route::get ('/create',                          [LeagueController::class, 'create'])->name('admin.core.league.create');
            Route::post('/save',                            [LeagueController::class, 'save'])->name('admin.core.league.save');
            Route::get ('/preview/{id}',                    [LeagueController::class, 'preview'])->name('admin.core.league.preview');
            Route::get ('/edit/{id}',                       [LeagueController::class, 'edit'])->name('admin.core.league.edit');
            Route::post('/update',                          [LeagueController::class, 'update'])->name('admin.core.league.update');
            Route::get ('/delete/{id}',                     [LeagueController::class, 'delete'])->name('admin.core.league.delete');
        });
    });
});
