<?php

namespace App\Services;

use App\Models\Categories;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Create a new class instance.
     */
    public function getAll(){
        return Categories::all();
    }

    public function create(array $data) {
        return Categories::create($data);
    }

    public function getById(int $id){
        return Categories::findOrFail($id);
    }

    public function update(int $id , array $data){
        $category = Categories::findOrFail($id);
        $category->update($data);       
        return $category->fresh();
    }

    public function deleteById(int $id){

        $category = Categories::findOrFail($id);
        $category->delete();
        return $category;
    }

    public function deleteMultiById(array $id){

        $category = Categories::whereIn('category_id',$id)->get();
        Categories::whereIn('category_id',$id)->delete();

        return $category;
    }

    public function deleteAll(){

        $category = Categories::all();
        $category->delete();
        return true;
    }


}
