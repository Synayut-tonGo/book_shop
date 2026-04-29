<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\ImageBook;
use App\Http\Requests\Book\ProfileImageBook;
use App\Models\Book;
use App\Models\BookImage;
use App\Services\ImageBookService;
use App\Supports\ApiResponse;
use Illuminate\Http\Request;

class ImageBookController extends Controller
{

    protected $imageBookService;

    protected function __construct(ImageBookService $imageBookService)
    {
        return $this->imageBookService = $imageBookService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $imageBook = $this->imageBookService->getAll();

            return ApiResponse::success($imageBook,'Get all image book successfully');

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCover(ImageBook $request)
    {
        try{
            $validated = $request->validated();
            $image = $request->file('image');
            $coverImageBook = $this->imageBookService->createCoverImage( $validated['book_id'], $image );

            return ApiResponse::success($coverImageBook,'Create cover image book successfully');

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), 500);
        }       
    }

    public function storeProfileOrUpdate(ProfileImageBook $request)
    {
        try{
            $validated = $request->validated();
            $image = $request->file('image');
            $coverImageBook = $this->imageBookService->createOrUpdateProfileImage( $validated['book_id'], $image );

            return ApiResponse::success($coverImageBook,'Create or update profile image book successfully');

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), 500);
        }       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
