<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
         Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});

// Public Course Routes
Route::prefix('courses')->group(function () {
    Route::get('/published', [App\Http\Controllers\API\CourseController::class, 'published']);
    Route::get('/educator/{userId}', [App\Http\Controllers\API\CourseController::class, 'educator']);
    Route::get('/category/{category}', [App\Http\Controllers\API\CourseController::class, 'category']);
    Route::get('/level/{level}', [App\Http\Controllers\API\CourseController::class, 'level']);
    Route::get('/{identifier}', [App\Http\Controllers\API\CourseController::class, 'show']);
});

// Public Lesson Routes
Route::prefix('lessons')->group(function () {
    Route::get('/course/{courseId}', [App\Http\Controllers\API\LessonController::class, 'byCourse']);
    Route::get('/course/{courseId}/preview', [App\Http\Controllers\API\LessonController::class, 'preview']);
    Route::get('/{identifier}', [App\Http\Controllers\API\LessonController::class, 'show']);
});

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Course Management
    Route::prefix('courses')->group(function () {
        Route::get('/', [App\Http\Controllers\API\CourseController::class, 'index']);
        Route::post('/', [App\Http\Controllers\API\CourseController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\API\CourseController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\API\CourseController::class, 'destroy']);
        Route::post('/{id}/publish', [App\Http\Controllers\API\CourseController::class, 'publish']);
        Route::post('/{id}/unpublish', [App\Http\Controllers\API\CourseController::class, 'unpublish']);
    });

    // Lesson Management
    Route::prefix('lessons')->group(function () {
        Route::get('/', [App\Http\Controllers\API\LessonController::class, 'index']);
        Route::post('/', [App\Http\Controllers\API\LessonController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\API\LessonController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\API\LessonController::class, 'destroy']);
        Route::post('/course/{courseId}/reorder', [App\Http\Controllers\API\LessonController::class, 'reorder']);
        Route::get('/enrolled/course/{courseId}', [App\Http\Controllers\API\LessonController::class, 'enrolledCourseLessons']);
    });

    // Enrollment Management
    Route::prefix('enrollments')->group(function () {
        Route::post('/enroll', [App\Http\Controllers\API\EnrollmentController::class, 'enroll']);
        Route::delete('/courses/{courseId}', [App\Http\Controllers\API\EnrollmentController::class, 'unenroll']);
        Route::get('/my-courses', [App\Http\Controllers\API\EnrollmentController::class, 'myEnrollments']);
        Route::get('/courses/{courseId}/students', [App\Http\Controllers\API\EnrollmentController::class, 'courseStudents']);
        Route::put('/courses/{courseId}/progress', [App\Http\Controllers\API\EnrollmentController::class, 'updateProgress']);
    });

    // Favorites Management
    Route::prefix('favorites')->group(function () {
        Route::post('/courses/{courseId}', [App\Http\Controllers\API\EnrollmentController::class, 'addToFavorites']);
        Route::delete('/courses/{courseId}', [App\Http\Controllers\API\EnrollmentController::class, 'removeFromFavorites']);
        Route::get('/my-favorites', [App\Http\Controllers\API\EnrollmentController::class, 'myFavorites']);
    });

    // Ratings Management
    Route::prefix('ratings')->group(function () {
        Route::post('/courses/{courseId}', [App\Http\Controllers\API\RatingController::class, 'rate']);
        Route::delete('/courses/{courseId}', [App\Http\Controllers\API\RatingController::class, 'delete']);
        Route::get('/courses/{courseId}/my-rating', [App\Http\Controllers\API\RatingController::class, 'myRating']);
    });

    // Comments Management
    Route::prefix('comments')->group(function () {
        Route::post('/courses/{courseId}', [App\Http\Controllers\API\CommentController::class, 'store']);
        Route::put('/{commentId}', [App\Http\Controllers\API\CommentController::class, 'update']);
        Route::delete('/{commentId}', [App\Http\Controllers\API\CommentController::class, 'delete']);
        Route::get('/my-comments', [App\Http\Controllers\API\CommentController::class, 'myComments']);
        Route::post('/{commentId}/like', [App\Http\Controllers\API\CommentController::class, 'like']);
        Route::delete('/{commentId}/like', [App\Http\Controllers\API\CommentController::class, 'unlike']);
    });

    Route::prefix('blogs')->group(function () {
        Route::get('/', [App\Http\Controllers\API\BlogController::class, 'index']);
        Route::post('/', [App\Http\Controllers\API\BlogController::class, 'store']);
        Route::get('/{uuid}', [App\Http\Controllers\API\BlogController::class, 'show']);
        Route::put('/{uuid}', [App\Http\Controllers\API\BlogController::class, 'update']);
        Route::delete('/{uuid}', [App\Http\Controllers\API\BlogController::class, 'destroy']);
    });
});

// Public Rating Routes
Route::get('/ratings/courses/{courseId}', [App\Http\Controllers\API\RatingController::class, 'courseRatings']);

// Public Comment Routes
Route::get('/comments/courses/{courseId}', [App\Http\Controllers\API\CommentController::class, 'courseComments']);