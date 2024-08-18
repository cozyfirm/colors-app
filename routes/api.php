<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OpenApi\ClubsController as OpenApiClubsController;
use App\Http\Controllers\API\OpenApi\CountryController as OpenApiCountryController;
use App\Http\Controllers\API\Posts\PostsController as ApiPostsController;
use App\Http\Controllers\API\Social\Groups\GroupsController;
use App\Http\Controllers\API\Social\Groups\GroupsMembershipController;
use App\Http\Controllers\API\TeamsController as APITeamsController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 *  Authentication routes
 */
Route::prefix('/auth')->group(function (){
    Route::post('/',                                   [AuthController::class, 'auth'])->name('api.auth');
    Route::post('/register',                           [AuthController::class, 'register'])->name('api.auth.register');

    /* Restart password */
    Route::prefix('/restart-password')->group(function (){
        Route::post('/generate-pin',                         [AuthController::class, 'generatePIN'])->name('api.auth.restart-password.generate-pin');
        Route::post('/verify-pin',                           [AuthController::class, 'verifyPIN'])->name('api.auth.restart-password.verify-pin');
        Route::post('/new-password',                         [AuthController::class, 'newPassword'])->name('api.auth.restart-password.new-password');
    });
});

/**
 *  All routes related to teams and national teams
 */
Route::prefix('/teams')->middleware('api-auth')->group(function (){
    /*
     *  Fetch all teams and national teams
     */
    Route::post ('/fetch-national-teams',                     [APITeamsController::class, 'nationalTeams'])->name('api.teams.fetch-national-teams');
    Route::post('/fetch-teams',                              [APITeamsController::class, 'fetchTeams'])->name('api.teams.fetch-teams');

    /*
     *  Save selected teams
     */
    Route::post('/save-teams',                               [APITeamsController::class, 'saveTeams'])->name('api.teams.save-teams');

});

/**
 *  Posts - API Endpoint for posts
 */
Route::prefix('/posts')->middleware('api-auth')->group(function (){
    Route::post('/save',                                      [ApiPostsController::class, 'save'])->name('api.posts.save');
    Route::post('/delete',                                    [ApiPostsController::class, 'delete'])->name('api.posts.delete');

    /*
     *  Fetch user posts:
     *      1. Fetch my posts (by api token)
     *      2. Fetch other users posts (check if posts can be fetched)
     */
    Route::post('/fetch-my-posts',                            [ApiPostsController::class, 'fetchMyPosts'])->name('api.posts.fetch-my-posts');
    Route::post('/fetch-user-posts',                          [ApiPostsController::class, 'fetchUserPosts'])->name('api.posts.fetch-user-posts');

});

/**
 *  User routes: Fetch users; users data, etc:
 */

Route::prefix('/users')->middleware('api-auth')->group(function (){
    /** Basic user info; Get by owner */
    Route::post('/get-data',                           [UsersController::class, 'getUserData'])->name('api.users.get-data');
    /** Update basic info */
    Route::post('/update',                             [UsersController::class, 'update'])->name('api.users.update');

    /**
     *  Settings APIs:
     *      1. Language
     *      2. Notifications
     *      3. Show location
     *      4. Show date of birth
     */
    Route::post('/set-language',                        [UsersController::class, 'setLanguage'])->name('api.users.set-language');
    Route::post('/profile-settings',                    [UsersController::class, 'profileSettings'])->name('api.users.profile-settings');
});

/** ---------------------------------------------------------------------------------------------------------------- **/
/**
 *  Groups routes
 */
Route::prefix('/groups')->middleware('api-auth')->group(function (){
    Route::post('/save',                               [GroupsController::class, 'save'])->name('api.groups.save');
    Route::post('/update',                             [GroupsController::class, 'update'])->name('api.groups.update');
    Route::post('/update-photo',                       [GroupsController::class, 'updatePhoto'])->name('api.groups.update-photo');

    /** Search groups by name */
    Route::post('/search',                             [GroupsController::class, 'search'])->name('api.groups.search');

    /** Membership */
    Route::prefix('/membership')->group(function (){
        /** Get all members */
        Route::post('/all-members',                              [GroupsMembershipController::class, 'allMembers'])->name('api.groups.membership.all-members');

        /** Create new request */
        Route::post('/send-request',                             [GroupsMembershipController::class, 'sendRequest'])->name('api.groups.membership.send-request');
        /** Allow or deny request: Performed by admins */
        Route::post('/allow-deny-request',                       [GroupsMembershipController::class, 'allowDenyRequest'])->name('api.groups.membership.allow-deny-request');
    });
});

/**
 *  Open API routes -> Available for public usage
 */
Route::prefix('/open-api')->group(function (){
    /**
     *  Countries
     */
    Route::prefix('/countries')->group(function (){
        Route::post('/',                                   [OpenApiCountryController::class, 'getCountries'])->name('api.open-api.countries');
        Route::post('/get-by-id',                          [OpenApiCountryController::class, 'getCountryByID'])->name('api.open-api.countries.get-by-id');
        Route::post('/get-by-code',                        [OpenApiCountryController::class, 'getCountryByCode'])->name('api.open-api.countries.get-by-code');
    });
    /**
     *  Teams
     */
    Route::prefix('/teams')->group(function (){
        Route::post('/by-name',                            [OpenApiClubsController::class, 'searchByName'])->name('api.open-api.teams.by-name');
    });
});
