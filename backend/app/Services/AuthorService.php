<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class AuthorService
{
    /**
     * Create a new class instance.
     */
    public function getAll()
    {
        return Author::all();
    }

    public function getById(int $id)
    {   

        $author = Author::findOrFail($id);
        return $author;
    }
    public function create(array $data)
    {   

        $author = Author::create($data);
        return $author;
    }

    public function update(int $id ,array $data)
    {   
        $author = Author::findOrFail($id);
        $author->update($data);
        return $author->fresh();
    }

    public function deleteById(int $id)
    {   
        $author = Author::where('author_id',$id)->get();
        Author::delete();
        return $author;
    }
    
    public function deleteMultiById(array $id)
    {   
        $author = Author::whereIn('author_id',$id)->get();
        Author::delete();
        return $author;
    }
     
    public function deleteAll()
    {   
        $author =Author::delete();      
        return $author;
    }
    
}
