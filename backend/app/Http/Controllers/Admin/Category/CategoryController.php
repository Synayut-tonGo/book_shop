<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use App\Supports\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService){
        return $this->categoryService = $categoryService;
    }    
    public function index()
    {
        try{

            $category =  $this->categoryService->getAll();
        return ApiResponse::success($category , 'Get all categories successfully');
        }catch(Exception $e){

            return ApiResponse::error($e->getMessage(), 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        try{

            $validated = $request->validated();
                      
            $category = $this->categoryService->create($validated);

            return ApiResponse::success($category , 'Created category successfully');
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
            $category = $this->categoryService->getById($id);
            return ApiResponse::success($category , 'Get category successfully');
        }catch(Exception $e){
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( int $id , UpdateCategoryRequest $request)
    {
       
        try{
            $validated = $request->validated();
            $category = $this->categoryService->update($id , $validated);
            return ApiResponse::success($category , 'Updated category successfully');
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

            $category = $this->categoryService->deleteById($id);
            return ApiResponse::success($category , 'Deleted category successfully');

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
            $category = $this->categoryService->deleteMultiById($ids);
            return ApiResponse::success($category , 'Deleted multi category successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }

    public function destroyAll()
    {
        try{

            $category = $this->categoryService->deleteAll();
            return ApiResponse::success($category , 'Deleted all category successfully');

        }catch(Exception $e){
            return ApiResponse::error($e->getMessage() ,500);
        }
    }
}
