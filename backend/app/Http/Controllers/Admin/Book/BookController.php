<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\CreateBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;
use App\Services\BookService;
use App\Supports\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $bookService;

    public function __construct(BookService $bookService){
        return $this->bookService = $bookService;
    }

    public function index()
    {
        try{

            $book =  $this->bookService->getAll();

            return ApiResponse::success($book , 'Get all books successfully');

        }catch(Exception $e){

            return ApiResponse::error($e->getMessage(), 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        try{

            $validated = $request->validated();
            
            $book = $this->bookService->create($validated);

            return ApiResponse::success($book , 'Created book successfully');
        }catch(Exception $e){
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try{
            $book = $this->bookService->getById($id);
            return ApiResponse::success($book , 'Get book successfully');
        }catch(Exception $e){
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( int $id , updateBookRequest $request)
    {
       
        try{
            $validated = $request->validated();
            $book = $this->bookService->update($id , $validated);
            return ApiResponse::success($book , 'Updated book successfully');
        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyById(int $id)
    {
        try{

            $book = $this->bookService->deleteById($id);
            return ApiResponse::success($book , 'Deleted book successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }


    public function destroyMultiById(Request $request)
    {
        try{
            $ids = $request->input('ids',[]);
            if(empty($ids)){
                return ApiResponse::error('No ids provide',400);
            }
            $book = $this->bookService->deleteMultiById($ids);
            return ApiResponse::success($book , 'Deleted Multi book successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }

    public function destroyAll()
    {
        try{

            $book = $this->bookService->deleteAll();
            return ApiResponse::success($book , 'Deleted all book successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }

}
