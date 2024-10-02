<?php

use App\Http\Controllers\Admin\Config\SplashController;
use App\Http\Controllers\Admin\SystemCore\ClubsController;
use App\Http\Controllers\Admin\SystemCore\LeagueController;
use App\Http\Controllers\Admin\SystemCore\SeasonsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Public\Auth\AuthController;
use App\Http\Controllers\Public\Website\HomepageController;
use Illuminate\Support\Facades\Route;
//
//Route::get('/', function () {
//    return view('welcome');
//});

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
Route::prefix('/admin')->middleware('auth')->group(function () {
    /**
     *  Users routes
     */
    Route::prefix('/users')->group(function (){
        /*
         *  Admin role routes
         */
        Route::middleware('is-admin')->group(function (){
            Route::get ('/',                                [UsersController::class, 'index'])->name('admin.users.index');
            Route::get ('/preview/{username}',              [UsersController::class, 'preview'])->name('admin.users.preview');
            Route::get ('/edit/{username}',                 [UsersController::class, 'edit'])->name('admin.users.edit');
            Route::post('/update',                          [UsersController::class, 'update'])->name('admin.users.update');
        });

        /*
         *  User routes
         */
        Route::get ('/my-profile',                      [UsersController::class, 'myProfile'])->name('admin.users.my-profile');
        Route::get ('/update-profile',                  [UsersController::class, 'updateProfile'])->name('admin.users.update-profile');
    });

    /**
     *  System core
     */
    Route::prefix('/core')->group(function (){
        /**
         *  Clubs
         */
        Route::prefix('/clubs')->middleware('is-admin')->group(function (){
            Route::get ('/',                                [ClubsController::class, 'index'])->name('admin.core.clubs');
            Route::get ('/create',                          [ClubsController::class, 'create'])->name('admin.core.clubs.create');
            Route::post('/save',                            [ClubsController::class, 'save'])->name('admin.core.clubs.save');
            Route::get ('/preview/{id}',                    [ClubsController::class, 'preview'])->name('admin.core.clubs.preview');
            Route::get ('/edit/{id}',                       [ClubsController::class, 'edit'])->name('admin.core.clubs.edit');
            Route::post('/update',                          [ClubsController::class, 'update'])->name('admin.core.clubs.update');
            Route::get ('/delete/{id}',                     [ClubsController::class, 'delete'])->name('admin.core.clubs.delete');

            Route::prefix('/venues')->middleware('is-admin')->group(function (){
                Route::get ('/edit/{club_id}',              [ClubsController::class, 'editVenue'])->name('admin.core.clubs.edit-venue');
                Route::post('/update',                      [ClubsController::class, 'updateVenue'])->name('admin.core.clubs.update-venue');
            });
        });

        /**
         *  Leagues
         */
        Route::prefix('/leagues')->middleware('is-league-moderator')->group(function (){
            Route::get ('/',                                [LeagueController::class, 'index'])->name('admin.core.league');
            Route::middleware('is-sys-moderator')->group(function (){
                Route::get ('/create',                          [LeagueController::class, 'create'])->name('admin.core.league.create');
                Route::post('/save',                            [LeagueController::class, 'save'])->name('admin.core.league.save');
                Route::get ('/delete/{id}',                     [LeagueController::class, 'delete'])->name('admin.core.league.delete');
            });
            Route::get ('/preview/{id}',                    [LeagueController::class, 'preview'])->name('admin.core.league.preview');
            Route::get ('/edit/{id}',                       [LeagueController::class, 'edit'])->name('admin.core.league.edit');
            Route::post('/update',                          [LeagueController::class, 'update'])->name('admin.core.league.update');

            Route::middleware('is-sys-moderator')->group(function (){
                /**
                 *  Add and remove moderators
                 */
                Route::prefix('/moderators')->middleware('is-sys-moderator')->group(function (){
                    Route::get ('/add/{id}',                    [LeagueController::class, 'addModerator'])->name('admin.core.league.moderators.add');
                    Route::post('/save',                        [LeagueController::class, 'saveModerator'])->name('admin.core.league.moderators.save');
                    Route::get ('/remove/{league_id}/{id}',     [LeagueController::class, 'removeModerator'])->name('admin.core.league.moderators.remove');
                });
            });
        });

        /**
         *  Seasons
         */
        Route::prefix('/seasons')->middleware('is-league-moderator')->group(function (){
            Route::get ('/',                                [SeasonsController::class, 'index'])->name('admin.core.seasons');
            Route::get ('/create',                          [SeasonsController::class, 'create'])->name('admin.core.seasons.create');
            Route::post('/save',                            [SeasonsController::class, 'save'])->name('admin.core.seasons.save');
            Route::get ('/preview/{id}',                    [SeasonsController::class, 'preview'])->name('admin.core.seasons.preview');
            Route::get ('/edit/{id}',                       [SeasonsController::class, 'edit'])->name('admin.core.seasons.edit');
            Route::post('/update',                          [SeasonsController::class, 'update'])->name('admin.core.seasons.update');
            Route::get ('/delete/{id}',                     [SeasonsController::class, 'delete'])->name('admin.core.seasons.delete');

            Route::get ('/lock-season/{id}',                [SeasonsController::class, 'lockSeason'])->name('admin.core.seasons.lock-season');

            /*
             *  Copy previous season and teams
             */
            Route::get ('/copy/{id}',                       [SeasonsController::class, 'copy'])->name('admin.core.seasons.copy');
            Route::post('/copy-season',                     [SeasonsController::class, 'copySeason'])->name('admin.core.seasons.copy-season');

            /*
             *  Seasons teams
             */
            Route::post('/save-team',                               [SeasonsController::class, 'saveTeam'])->name('admin.core.seasons.save-team');
            Route::get ('/delete-team/{season_id}/{team_id}',       [SeasonsController::class, 'deleteTeam'])->name('admin.core.seasons.delete-team');

            /*
             *  Match schedule
             */
            Route::get ('/match-schedule/{season_id}',              [SeasonsController::class, 'matchSchedule'])->name('admin.core.seasons.match-schedule');
            Route::post('/save-match-schedule',                     [SeasonsController::class, 'saveMatchSchedule'])->name('admin.core.seasons.system.core.seasons.save-match-schedule');
            Route::get ('/delete-match-schedule/{id}',              [SeasonsController::class, 'deleteMatchSchedule'])->name('admin.core.seasons.delete-match-schedule');
        });
    });

    /**
     *  Configuration
     */
    Route::prefix('/config')->middleware('is-sys-moderator')->group(function () {
        /*
         *  Splash pages
         */
        Route::prefix('/splash')->group(function (){
            Route::get ('/',                                [SplashController::class, 'index'])->name('admin.config.splash');
            Route::get ('/create',                          [SplashController::class, 'create'])->name('admin.config.splash.create');
            Route::post('/save',                            [SplashController::class, 'save'])->name('admin.config.splash.save');
            Route::get ('/edit/{id}',                       [SplashController::class, 'edit'])->name('admin.config.splash.edit');
            Route::post('/update',                          [SplashController::class, 'update'])->name('admin.config.splash.update');
            Route::get ('/delete/{id}',                     [SplashController::class, 'delete'])->name('admin.config.splash.delete');
        });
    });
});

/**
 *  Website routes:
 *
 *  1. Homepage
 *  2. Contact us
 */
Route::prefix('')->group(function () {
    Route::get('/',                                     [HomepageController::class, 'homepage'])->name('public.website.homepage');
});
