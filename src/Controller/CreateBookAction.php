<?php
// api/src/Controller/CreateBookAction.php

namespace App\Controller;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateBookAction
{
    public function __invoke(Request $request,SluggerInterface $slugger,CategoryRepository $rep,BookRepository $bookrep,Book $book=null): Book
    {
        $book=new Book();
        $book->setTitle($request->get('title'));
        $book->setAuthor($request->get('author'));
        $book->setPrice($request->get('price'));
        
        $categories=json_decode($request->get('categories'));
        //throw new BadRequestHttpException($this->storage->resolveUri($categories[0]));
       foreach($categories as $cat){
          $book->addCategory($rep->find($cat));
        }
        $uploadedFile = $request->files->get('image');
       if ($uploadedFile) {
        $book->setImageName($this->uploadImage($uploadedFile,$slugger));  
           /// throw new BadRequestHttpException('"image" is required');
        }
        return $book;
    }
    private function uploadImage($image, SluggerInterface $slugger){ 
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                'public/uploads/images',
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}