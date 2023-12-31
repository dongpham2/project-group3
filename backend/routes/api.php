<?php

use App\Http\Controllers\API\BloggerAuthController;
use App\Http\Controllers\API\BloggerProfileController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\SavePostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [BloggerAuthController::class, 'register']);
Route::post('/login', [BloggerAuthController::class, 'login']);
Route::get('/tags/get-all-tags', [PostController::class, 'getAllTags']);
Route::get('/categories/get-all-categories', [PostController::class, 'getAllCategories']);
Route::get('/bloggers', [BloggerProfileController::class, 'getAllBloggers']);
Route::post('bloggers/forget-password', [BloggerAuthController::class, 'forgetPassword']);
Route::get('/categories-tags', [PostController::class, 'getTagsCategories']);

Route::prefix('/posts')->group(function () {
    Route::get('/', [PostController::class, 'getAllActivePost']);
    Route::get('/tags/{id}', [PostController::class, 'getPostsByTagId']);
    Route::get('/{id}', [PostController::class, 'getPublishedPostById']);
    Route::get('/highlight/get-most-like', [PostController::class, 'getMostLikePosts']);
    Route::get('/highlight/get-most-view', [PostController::class, 'getMostViewPosts']);
    Route::get('/filter/filter-post', [PostController::class, 'filterPost']);
    Route::get('/filter/filter-post2', [PostController::class, 'filterPost2']);
    Route::get('/recommend/recommend-posts', [PostController::class, 'getRecommendPost']);
    Route::get('/category/{id}', [PostController::class, 'getPostByCategoryId']);
});

Route::prefix('/blogger')->group(function () {
    Route::get('/{id}', [BloggerProfileController::class, 'getPublicProfileInfor']);
});

Route::middleware(['auth:blogger'])->group(function () {
    Route::delete('/logout', [BloggerAuthController::class, 'logout']);

    Route::prefix('/blogger')->group(function () {
        Route::get('/me/profile', [BloggerProfileController::class, 'getMyProfileInfor']);
        Route::post('/me/update-profile', [BloggerProfileController::class, 'updateBloggerProfile']);

        Route::post('/follow/{id}', [BloggerProfileController::class, 'follow']);
        Route::post('/check-follow/{id}', [BloggerProfileController::class, 'isFollowed']);
        Route::get('/me/view-following', [BloggerProfileController::class, 'viewMyFollowing']);
        Route::get('/me/view-follower', [BloggerProfileController::class, 'viewMyFollower']);

        Route::get('/me/view-notification', [BloggerProfileController::class, 'viewMyNotification']);
        Route::post('/me/change-password', [BloggerProfileController::class, 'changePassword']);
        Route::post('/me/change-email', [BloggerProfileController::class, 'changeEmail']);
    });

    Route::prefix('/posts')->group(function () {
        Route::post('/create-post', [PostController::class, 'store']);
        Route::post('/update-post/{slug}', [PostController::class, 'update']);
        Route::delete('/delete-post/{slug}', [PostController::class, 'delete']);

        Route::post('/create-post-draft', [PostController::class, 'storeDraft']);
        Route::post('/publish-draft/{id}', [PostController::class, 'publishDraft']);
        Route::get('/draft/get-my-draft', [PostController::class, 'getMyDraftPosts']);

        Route::get('/author/get-post-by-author', [PostController::class, 'getPostsByMyFollowing']);

        Route::get('/pending/get-pending-post', [PostController::class, 'getPendingPost']);
        Route::get('/published/get-published-post', [PostController::class, 'getPublishedPost']);
        
        Route::get('/draft/{id}', [PostController::class, 'getDraftPostById']);
        Route::get('/pending/{id}', [PostController::class, 'getPendingPostById']);
    });

    Route::prefix('/comment')->group(function () {
        Route::post('/{slug}', [CommentController::class, 'commentPost']);
        Route::post('/edit-comment/{id}', [CommentController::class, 'editComment']);
        Route::delete('/delete-comment/{id}', [CommentController::class, 'deleteComment']);
        Route::post('/reply-comment/{id}', [CommentController::class, 'replyComment']);
    });

    Route::prefix('/like')->group(function () {
        Route::post('/{id}', [LikeController::class, 'likePost']);
        Route::post('/check-like/{id}', [LikeController::class, 'checkLike']);
        Route::get('/get-liked-post', [LikeController::class, 'getAllLikedPosts']);

        Route::post('like-comment/{id}', [LikeController::class, 'likeComment']);
        Route::get('check-like-comment/{id}', [LikeController::class, 'checkLikeComment']);
        Route::get('count-like-comment/{id}', [LikeController::class, 'countLikeComment']);
    });

    Route::prefix('/save')->group(function () {
        Route::post('/{id}', [SavePostController::class, 'savePost']);
        Route::post('/check-save/{id}', [SavePostController::class, 'checkSave']);
        Route::get('/get-saved-post', [SavePostController::class, 'getAllSavedPosts']);
    });
});
