<?php

namespace App\Services;

use App\Models\BookImage;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageBookService
{
    /**
     * Create a new class instance.
     */
    public function getAll()
    {
        return BookImage::all();
    }


    public function getImageProfile($bookId){

        $image = BookImage::profile()->where('book_id', $bookId)->get();
        return $image;
    }

    public function getImageCover($bookId){

        $image = BookImage::cover()->where('book_id', $bookId)->get();
        return $image;
    }

    public function createOrUpdateProfileImage($bookId , $image){

        $imageProfile = null;
        
        if(!$image instanceof UploadedFile){
            return null;
        }
        
        $profile = BookImage::where('book_id' , $bookId)->where('type' , 'profile')->first();

        if($profile) {
            Storage::disk('cover-profiles')->delete($profile->image);

            $profile->delete();
        }

        $path = $image->store('cover-profiles','public');

        $imageProfile = BookImage::create([
            'book_id' => $bookId,
            'image' => $path,
            'type' => 'profile',
        ]);

        return $imageProfile;

    }

    public function createCoverImage ($bookId, array $images){

            $uploadedImage = [];

            $existingCount = BookImage::where('book_id',$bookId)->where('type','cover')->count();

            // 🔹 Count new images
            $newCount = count($images);

            if (($existingCount + $newCount) > 5) {
                throw new \Exception('Maximum 5 cover images allowed');
            }

            foreach ($images as $image) {
                if($image instanceof UploadedFile){
                    $path = $image->store('cover-books' , 'public');
                    $bookImage = BookImage::create([
                        'book_id' => $bookId,
                        'image' => $path,
                        'type' => 'cover',
                    ]);

                    $uploadedImage[] = $bookImage;
                }
            }
            return $uploadedImage;
    }

    public function updateCoverImage ($bookId , array $newImage = [] , array $deleteIds = []){

            $imageToDelete = BookImage::where('book_image_id',$deleteIds)->get();

            foreach ($imageToDelete as $img){
                Storage::disk('cover-books')->delete($img->image);
                $img->delete();
                
            }

            return $this->createCoverImage($bookId , $newImage);

    }
    

}
