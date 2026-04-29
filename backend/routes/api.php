<?php

use App\Http\Controllers\Admin\Author\AuthorController;
use App\Http\Controllers\Admin\Book\BookController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\User\AssignRoleController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);
Route::get('/me' , [AuthController::class , 'fetchUser']);

Route::middleware('jwt.auth')->group(function () {
    Route::prefix('admin')->group(function () {

    // users
    
        Route::prefix('users')->middleware('permission:manage-users')->group(function () {
            
            Route::get('getAll',[UserController::class , 'index']);
            Route::get('getById/{user}',[UserController::class , 'show']);
            Route::post('createUser',[UserController::class , 'store']);
            Route::put('updateUser/{user}',[UserController::class , 'update']);
            Route::delete('deleteById/{user}',[UserController::class , 'destroy']);
            Route::delete('deleteMultiById',[UserController::class , 'destroyMulti']);        
            Route::delete('deleteAll',[UserController::class , 'destroyAll']);
            Route::post('{user}/assign-role' , [AssignRoleController::class , 'assignRole']);

        });

    // books

        Route::prefix('books')->middleware('permission:create-books')->group(function () {            
            Route::post('createBook',[BookController::class , 'store']);
        });

        Route::prefix('books')->middleware('permission:edit-books')->group(function () {            
            Route::put('updateBook/{user}',[BookController::class , 'update']);
        });
        Route::prefix('books')->group(function () {            
            Route::get('getAll',[BookController::class , 'index']);
        });
        Route::prefix('books')->group(function () {            
            Route::put('getById/{id}',[BookController::class , 'show']);
        });
        Route::prefix('books')->group(function () {            
            Route::put('getAll',[BookController::class , 'index']);
        });

        Route::prefix('books')->middleware('permission:delete-books')->group(function () {            
            Route::delete('deleteById/{id}',[BookController::class , 'destroyById']);
            Route::delete('deleteMultiById',[BookController::class , 'destroyMulti']);        
            Route::delete('deleteAll',[BookController::class , 'destroyAll']);
        });        

    // authors

        // Route::prefix('authors')->middleware('permission:create-authors')->group(function () {            
        //     Route::post('createAuthor',[AuthorController::class , 'store']);
        // });

        Route::prefix('authors')->group(function () {            
            Route::post('createAuthor',[AuthorController::class , 'store']);
        });        

        Route::prefix('authors')->middleware('permission:edit-authors')->group(function () {            
            Route::put('updateAuthor/{user}',[AuthorController::class , 'update']);
        });
        Route::prefix('authors')->group(function () {            
            Route::get('getAll',[AuthorController::class , 'index']);
        });
        Route::prefix('authors')->group(function () {            
            Route::put('getById/{id}',[AuthorController::class , 'show']);
        });
        Route::prefix('authors')->group(function () {            
            Route::put('getAll',[AuthorController::class , 'index']);
        });

        Route::prefix('authors')->middleware('permission:delete-authors')->group(function () {            
            Route::delete('deleteById/{id}',[AuthorController::class , 'destroyById']);
            Route::delete('deleteMultiById',[AuthorController::class , 'destroyMulti']);        
            Route::delete('deleteAll',[AuthorController::class , 'destroyAll']);
        }); 


    // category 

        Route::prefix('categories')->middleware('permission:create-books')->group(function () {            
            Route::post('createCategory',[CategoryController::class , 'store']);
        });

        Route::prefix('categories')->middleware('permission:edit-categories')->group(function () {            
            Route::put('updateCategory/{user}',[CategoryController::class , 'update']);
        });
        Route::prefix('categories')->group(function () {            
            Route::get('getAll',[CategoryController::class , 'index']);
        });
        Route::prefix('categories')->group(function () {            
            Route::put('getById/{id}',[CategoryController::class , 'show']);
        });
        Route::prefix('categories')->group(function () {            
            Route::put('getAll',[CategoryController::class , 'index']);
        });

        Route::prefix('categories')->middleware('permission:delete-categories')->group(function () {            
            Route::delete('deleteById/{id}',[CategoryController::class , 'destroyById']);
            Route::delete('deleteMultiById',[CategoryController::class , 'destroyMulti']);        
            Route::delete('deleteAll',[CategoryController::class , 'destroyAll']);
        }); 
    });


    Route::post('/logout' , [AuthController::class , 'logout']);
});
