<?php
namespace App\Test\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class BookTest extends TestCase
{
    private $book;

    protected function setUp()
    {
        $this->book = new Book();
    }

    public function testGetId()
    {
        self::assertEquals(null, $this->book->getId());
    }

    public function testGetSetCategory()
    {
        self::assertEquals(null,$this->book->getCategory());
        $this->book->setCategory(Book::FICTION_CATEGORY);
        self::assertEquals(Book::FICTION_CATEGORY,$this->book->getCategory());
    }

    public function testGetSetName()
    {
        self::assertEquals(null,$this->book->getName());
        $this->book->setName('Learn React Js');
        self::assertEquals('Learn React Js',$this->book->getName());
    }

    public function testGetSetAuthor()
    {
        self::assertEquals(null,$this->book->getAuthor());
        $this->book->setAuthor('Charith Dissanayaka');
        self::assertEquals('Charith Dissanayaka',$this->book->getAuthor());
    }

    public function testGetSetUnitPrice()
    {
        self::assertEquals(null,$this->book->getUnitPrice());
        $this->book->setUnitPrice(1200);
        self::assertEquals(1200,$this->book->getUnitPrice());
    }

    public function testGetSetDescription()
    {
        self::assertEquals(null,$this->book->getDescription());
        $this->book->setDescription("Good for new features for react beginners");
        self::assertEquals("Good for new features for react beginners",$this->book->getDescription());
    }

    public function testGetSetCreatedAt()
    {
        self::assertEquals(null,$this->book->getCreatedAt());
        $this->book->setCreatedAt(new \DateTime("2019-12-13 16:29:37"));
        self::assertEquals(new \DateTime("2019-12-13 16:29:37"),$this->book->getCreatedAt());
    }

    public function testGetSetUpdatedAt()
    {
        self::assertEquals(null,$this->book->getUpdatedAt());
        $this->book->setUpdatedAt(new \DateTime("2019-12-13 16:29:37"));
        self::assertEquals(new \DateTime("2019-12-13 16:29:37"),$this->book->getUpdatedAt());
    }

    public function testBookName()
    {
        $this->book->setCategory(Book::FICTION_CATEGORY);
        self::assertEquals('Fiction',$this->book->getCategoryName());
        $this->book->setCategory(Book::CHILDREN_CATEGORY);
        self::assertEquals('Children',$this->book->getCategoryName());
    }

    public function testBookCount()
    {
      $array =  [
             [
                 'id' => 1,
                 'name' => 'Guru geethaya',
                 'author' => 'Nimal',
                 'unit_price' => 1200,
                 'category' => Book::FICTION_CATEGORY ,
                 'price' => 1200 ,
                 'qty' => 2
             ],
             [
                 'id' => 2,
                 'name' => 'Dagaya',
                 'author' => 'Nimal',
                 'unit_price' => 1200,
                 'category' => Book::FICTION_CATEGORY ,
                 'price' => 1200 ,
                 'qty' => 1
             ]
        ];
        $session = new Session(new MockFileSessionStorage());
        $session->set('cart',$array);
        self::assertEquals(count($array),$this->book->getBookCount($session));
    }
}