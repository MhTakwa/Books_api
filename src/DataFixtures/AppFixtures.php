<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fantazy =new Category();
        $fantazy->setLabel('Fantazy');
        $drama=new Category();
        $drama->setlabel("Drama");
        $book =new Book();
        $book->setTitle("Never look away");
        $book->setPrice(3,5);
        $book->setAuthor("Linwood Barkley");
        $book->addCategory($fantazy);
        $book->addCategory($drama);
        $manager->persist($book);
        $book =new Book();
        $book->setTitle("Harry Poter");
        $book->setPrice(4);
        $book->setAuthor("J. K. Rowling");
        $book->addCategory($drama);
        $manager->persist($book);
        $book =new Book();
        $book->setTitle("Divergent");
        $book->setPrice(2);
        $book->setAuthor("Veronice Roth");
        $book->addCategory($drama);
        $manager->persist($book);
        $manager->flush();
    }
}
