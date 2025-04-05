<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MatchChat\MatchChatController;
use App\Http\Controllers\API\OpenApi\ClubsController as OpenApiClubsController;
use App\Http\Controllers\API\OpenApi\CountryController as OpenApiCountryController;
use App\Http\Controllers\API\OpenApi\InfoController as OpenApiInfoController;
use App\Http\Controllers\API\OpenApi\SplashController as OpenApiSplashController;
use App\Http\Controllers\API\Posts\PostsController as ApiPostsController;
use App\Http\Controllers\API\Posts\CommentsController as ApiCommentsController;
use App\Http\Controllers\API\Posts\Streams\StreamsController as ApiStreamsController;
use App\Http\Controllers\API\Social\Fans\FansController;
use App\Http\Controllers\API\Social\Fans\FansRequestsController;
use App\Http\Controllers\API\Social\Fans\SearchController as FansSearchController;
use App\Http\Controllers\API\Social\Groups\GroupsController;
use App\Http\Controllers\API\Social\Groups\GroupsMembershipController;
use App\Http\Controllers\API\Social\Groups\PostsController as GroupsPostsController;
use App\Http\Controllers\API\Social\Groups\CommentsController as GroupsCommentsController;
use App\Http\Controllers\API\SystemCore\Notifications\NotificationsController;
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

    /**
     *  Verify email using token
     */
    Route::post('/verify-an-email',                             [AuthController::class, 'verifyAnEmail'])->name('api.auth.verify-an-email');
    Route::get ('/verify-an-email/{username}/{api_token}',      [AuthController::class, 'verifyAnEmailGET'])->name('api.auth.verify-an-email');

    /**
     *  Check for:
     *      1. Email
     *      2. Username
     *      3. Password
     */
    Route::prefix('/check')->group(function (){
        Route::post('/email',                              [AuthController::class, 'checkEmail'])->name('api.auth.check.email');
        Route::post('/username',                           [AuthController::class, 'checkUsername'])->name('api.auth.check.username');
        Route::post('/password',                           [AuthController::class, 'checkPassword'])->name('api.auth.check.password');
    });

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
    Route::post('/update',                                    [ApiPostsController::class, 'update'])->name('api.posts.update');
    Route::post('/delete',                                    [ApiPostsController::class, 'delete'])->name('api.posts.delete');

    /**
     *  Fetch user posts:
     *      1. Fetch my posts (by api token)
     *      2. Fetch other users posts (check if posts can be fetched)
     */
    Route::post('/fetch-my-posts',                            [ApiPostsController::class, 'fetchMyPosts'])->name('api.posts.fetch-my-posts');
    Route::post('/fetch-user-posts',                          [ApiPostsController::class, 'fetchUserPosts'])->name('api.posts.fetch-user-posts');

    /* Like / unlike post */
    Route::post('/like',                                      [ApiPostsController::class, 'like'])->name('api.posts.like');

    Route::prefix('/comments')->middleware('api-auth')->group(function (){
        Route::post('/add',                                   [ApiCommentsController::class, 'add'])->name('api.posts.comments.add');
        /**
         *  Fetch comments and comments of comment
         */
        Route::post('/fetch',                                 [ApiCommentsController::class, 'fetch'])->name('api.posts.comments.fetch');
        Route::post('/fetch-comments-on-comment',             [ApiCommentsController::class, 'fetchCommentsOnComment'])->name('api.posts.comments.fetch-comments-on-comment');
        /* Delete comment */
        Route::post('/delete',                                [ApiCommentsController::class, 'delete'])->name('api.posts.comments.delete');
        /* Like / unlike comment */
        Route::post('/like',                                  [ApiCommentsController::class, 'like'])->name('api.posts.comments.like');
    });

    /**
     *  Streams API:
     *      1. Search for streams
     *      2. Preview streams
     *      3. Preview single stream
     */
    Route::prefix('/streams')->middleware('api-auth')->group(function (){
        /** Fetch home streams */
        Route::post('/',                                      [ApiStreamsController::class, 'index'])->name('api.posts.streams');
        /** Fetch specific stream */
        Route::post('/fetch',                                 [ApiStreamsController::class, 'fetch'])->name('api.posts.streams.fetch');

        /** Search streams|posts, users and groups */
        Route::post('/search',                                [ApiStreamsController::class, 'search'])->name('api.posts.streams.search');
    });
});

/**
 *  User routes: Fetch users; users data, etc:
 */

Route::prefix('/users')->middleware('api-auth')->group(function (){
    /** Basic user info; Get by owner */
    Route::post('/get-data',                           [UsersController::class, 'getUserData'])->name('api.users.get-data');
    /** Update basic info */
    Route::post('/update',                             [UsersController::class, 'update'])->name('api.users.update');
    /** Update profile image */
    Route::post('/update-image',                       [UsersController::class, 'updateImage'])->name('api.users.update-image');

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
 *  Fans section
 */
Route::prefix('/fans')->middleware('api-auth')->group(function (){
    /** Search fans by name */
    Route::post('/search',                             [FansSearchController::class, 'search'])->name('api.fans.search');
    Route::post('/recommended',                        [FansSearchController::class, 'recommended'])->name('api.fans.recommended');

    Route::prefix('/my-fans')->middleware('api-auth')->group(function (){
        Route::post('/fetch',                          [FansController::class, 'fetch'])->name('api.fans.my-fans');
        Route::post('/search',                         [FansController::class, 'search'])->name('api.fans.my-fans.search');

        Route::post('/delete',                         [FansController::class, 'delete'])->name('api.fans.my-fans.delete');
    });

    Route::prefix('/requests')->middleware('api-auth')->group(function (){
        Route::post('/create',                         [FansRequestsController::class, 'create'])->name('api.fans.requests.create');
        Route::post('/update',                         [FansRequestsController::class, 'update'])->name('api.fans.requests.action');

        Route::prefix('/fetch')->middleware('api-auth')->group(function (){
            Route::post('/',                           [FansRequestsController::class, 'fetchRequests'])->name('api.fans.requests.fetch');
            Route::post('/sent',                       [FansRequestsController::class, 'fetchSentRequests'])->name('api.fans.requests.fetch.sent');
        });
    });
});

/** ---------------------------------------------------------------------------------------------------------------- **/
/**
 *  Groups routes
 */
Route::prefix('/groups')->middleware('api-auth')->group(function (){
    Route::post('/save',                               [GroupsController::class, 'save'])->name('api.groups.save');
    Route::post('/update',                             [GroupsController::class, 'update'])->name('api.groups.update');
    Route::post('/update-photo',                       [GroupsController::class, 'updatePhoto'])->name('api.groups.update-photo');

    /**
     *  Get group info and posts
     */
    Route::post('/get-info',                           [GroupsController::class, 'getInfo'])->name('api.groups.get-info');

    /** Search groups by name */
    Route::post('/search',                             [GroupsController::class, 'search'])->name('api.groups.search');

    /**
     *  Fetch groups as:
     *      1. All groups
     *      2. My groups
     *      3. Top groups
     */
    Route::prefix('/fetch')->middleware('api-auth')->group(function (){
        Route::post('/all',                            [GroupsController::class, 'fetchAllGroups'])->name('api.groups.fetch.all');
        Route::post('/my-groups',                      [GroupsController::class, 'fetchMyGroups'])->name('api.groups.fetch.my-groups');
        Route::post('/top-groups',                     [GroupsController::class, 'fetchTopGroups'])->name('api.groups.fetch.top-groups');
    });

    /** Membership */
    Route::prefix('/membership')->group(function (){
        /** Get all members */
        Route::post('/all-members',                              [GroupsMembershipController::class, 'allMembers'])->name('api.groups.membership.all-members');

        /** Create new request */
        Route::post('/send-request',                             [GroupsMembershipController::class, 'sendRequest'])->name('api.groups.membership.send-request');
        /** Allow or deny request: Performed by admins */
        Route::post('/allow-deny-request',                       [GroupsMembershipController::class, 'allowDenyRequest'])->name('api.groups.membership.allow-deny-request');
        /** Join if group is public */
        Route::post('/join',                                     [GroupsMembershipController::class, 'join'])->name('api.groups.membership.join');
        /** Leave group */
        Route::post('/leave',                                    [GroupsMembershipController::class, 'leave'])->name('api.groups.membership.leave');
    });

    /**
     *  Posts by Group
     */
    Route::prefix('/posts')->middleware('api-auth')->group(function (){
        Route::post('/save',                                     [GroupsPostsController::class, 'save'])->name('api.groups.posts.save');
        Route::post('/update',                                   [GroupsPostsController::class, 'update'])->name('api.groups.posts.update');
        Route::post('/delete',                                   [GroupsPostsController::class, 'delete'])->name('api.groups.posts.delete');

        /** Fetch first n posts of group */
        Route::post('/fetch',                                    [GroupsPostsController::class, 'fetch'])->name('api.groups.posts.fetch');

        /** Like post */
        Route::post('/like',                                     [GroupsPostsController::class, 'like'])->name('api.groups.posts.like');

        Route::prefix('/comments')->middleware('api-auth')->group(function (){
            Route::post('/add',                                   [GroupsCommentsController::class, 'add'])->name('api.groups.posts.comments.add');
            /**
             *  Fetch comments and comments of comment
             */
            Route::post('/fetch',                                 [GroupsCommentsController::class, 'fetch'])->name('api.groups.posts.comments.fetch');
            Route::post('/fetch-comments-on-comment',             [GroupsCommentsController::class, 'fetchCommentsOnComment'])->name('api.groups.posts.comments.fetch-comments-on-comment');
            /* Delete comment */
            Route::post('/delete',                                [GroupsCommentsController::class, 'delete'])->name('api.groups.posts.comments.delete');
            /* Like / unlike comment */
            Route::post('/like',                                  [GroupsCommentsController::class, 'like'])->name('api.groups.posts.comments.like');
        });
    });
});

/** ---------------------------------------------------------------------------------------------------------------- **/
/**
 *  Match chat
 */
Route::prefix('/match-chat')->middleware('api-auth')->group(function (){
    Route::post('/fetch',                              [MatchChatController::class, 'fetch'])->name('api.match-chat.fetch');

    /** Messages in MatchChat */
    Route::prefix('/messages')->group(function (){
        Route::post('/save-message',                       [MatchChatController::class, 'saveMessage'])->name('api.match-chat.messages.save-message');
        Route::post('/upload-photo',                       [MatchChatController::class, 'uploadPhoto'])->name('api.match-chat.messages.upload-photo');

        /** Fetch n-th messages */
        Route::post('/fetch',                              [MatchChatController::class, 'fetchMessages'])->name('api.match-chat.messages.fetch-messages');

        /** Like message */
        Route::post('/like-message',                       [MatchChatController::class, 'likeMessage'])->name('api.match-chat.messages.like-message');
    });
    Route::post('/recommended',                        [FansSearchController::class, 'recommended'])->name('api.fans.recommended');
});

Route::prefix('/system-core')->middleware('api-auth')->group(function (){
    /**
     *  Notifications
     */
    Route::prefix('/notifications')->middleware('api-auth')->group(function (){
        Route::post('/my-notifications',                         [NotificationsController::class, 'myNotifications'])->name('api.system-core.notifications.my-notifications');
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

        Route::post('/fetch-by-id',                        [OpenApiClubsController::class, 'fetchByID'])->name('api.open-api.teams.fetch-by-id');
        Route::post('/fetch-by-name',                      [OpenApiClubsController::class, 'fetchByName'])->name('api.open-api.teams.fetch-by-name');
    });

    /**
     *  Config data
     */
    Route::prefix('/config')->group(function (){
        /**
         *  Splash screens
         */
        Route::prefix('/splash-screen')->group(function (){
            Route::post('/',                                [OpenApiSplashController::class, 'get'])->name('api.open-api.config.splash-screen');
        });

        /**
         *  Open links:
         *
         *  - Terms and conditions
         *  - Privacy policy
         */
        Route::prefix('/info')->group(function () {
            Route::post('/terms-and-conditions',            [OpenApiInfoController::class, 'termsAndConditions'])->name('api.open-api.config.info.terms-and-conditions');
            Route::post('/privacy-policy',                  [OpenApiInfoController::class, 'privacyPolicy'])->name('api.open-api.config.info.privacyPolicy');
        });
    });
});
