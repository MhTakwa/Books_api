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

final class EditBookAction
{
    public function __invoke(Request $request,SluggerInterface $slugger,CategoryRepository $rep): Book
    {
        throw new BadRequestHttpException($request->files->get('image'));
        $book =$request->get('data');
      /*  $book->setTitle($request->get('data')->getTitle());
        $book->setAuthor($request->get('data')->getAuthor());
        $book->setPrice($request->get('data')->getPrice());*/
        
      //  $categories=$request->get('data')->getCategories();
      //  throw new BadRequestHttpException($request->getContent());
     /*  foreach($categories as $cat){
          $book->addCategory($rep->find($cat));
        }*/
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