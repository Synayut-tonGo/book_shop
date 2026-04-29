<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class BookService
{
    /**
     * Create a new class instance.
     */

    public function getAll(){
        return Book::all();
    }

    public function create(array $data) {
        return Book::create($data);
    }

    public function getById(int $id){
        $book = Book::findOrFail($id);
        return $book->load('imagesBook');
    }

    public function update(int $id , array $data){
        $book = Book::findOrFail($id);
        $book->update($data);       
        return $book->fresh();
    }

    public function deleteById(int $id){

        $book = Book::findOrFail($id);
        $book->delete();
        return $book;
    }

    public function deleteMultiById(array $id){

        $book = Book::whereIn('book_id',$id)->get();
        Book::whereIn('book_id',$id)->delete();

        return $book;
    }

    public function deleteAll(){

        $book = Book::all();
        $book->delete();
        return true;
    }


}
