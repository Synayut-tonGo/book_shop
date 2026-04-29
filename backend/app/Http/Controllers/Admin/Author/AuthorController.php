<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\CreateAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Services\AuthorService;
use App\Supports\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    protected $authorService;

    public function __construct(AuthorService $authorService){
        return $this->authorService = $authorService;
    }    
    public function index()
    {
        try{

            $author =  $this->authorService->getAll();
        return ApiResponse::success($author , 'Get all authors successfully');
        }catch(Exception $e){

            return ApiResponse::error($e->getMessage(), 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAuthorRequest $request)
    {
        try{
            
            $validated = $request->validated();
            
            $author = $this->authorService->create($validated);

            return ApiResponse::success($author , 'Created author successfully');
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
            $author = $this->authorService->getById($id);
            return ApiResponse::success($author , 'Get author successfully');
        }catch(Exception $e){
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( int $id , UpdateAuthorRequest $request)
    {
       
        try{
            $validated = $request->validated();
            $author = $this->authorService->update($id , $validated);
            return ApiResponse::success($author , 'Updated author successfully');
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

            $author = $this->authorService->deleteById($id);
            return ApiResponse::success($author , 'Deleted author successfully');

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
            $author = $this->authorService->deleteMultiById($ids);
            return ApiResponse::success($author , 'Deleted Multi author successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }

    public function destroyAll()
    {
        try{

            $author = $this->authorService->deleteAll();
            return ApiResponse::success($author , 'Deleted all author successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }
}
