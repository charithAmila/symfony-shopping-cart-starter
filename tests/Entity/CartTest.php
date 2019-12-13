<?php
namespace App\Test\Entity;

use App\Entity\Book;
use App\Entity\Cart;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    private $cart;

    protected function setUp()
    {
        $this->cart = new Cart();
    }

    public function testGetId()
    {
        self::assertEquals(null, $this->cart->getId());
    }

    public function testGetSetQty()
    {
        self::assertEquals(null,$this->cart->getQty());
        $this->cart->setQty(12);
        self::assertEquals(12,$this->cart->getQty());
    }

    public function testGetSetSubTotal()
    {
        self::assertEquals(null,$this->cart->getSubTotal());
        $this->cart->setSubTotal(120);
        self::assertEquals(120,$this->cart->getSubTotal());
    }

    public function testGetSetDiscount()
    {
        self::assertEquals(null,$this->cart->getDiscount());
        $this->cart->setDiscount(12);
        self::assertEquals(12,$this->cart->getDiscount());
    }

    public function testGetSetTotal()
    {
        self::assertEquals(null,$this->cart->getTotal());
        $this->cart->setTotal(12);
        self::assertEquals(12,$this->cart->getTotal());
    }

    public function testGetSetCreatedAt()
    {
        self::assertEquals(null,$this->cart->getCreatedAt());
        $this->cart->setCreatedAt(new \DateTime("2019-12-13 16:29:37"));
        self::assertEquals(new \DateTime("2019-12-13 16:29:37"),$this->cart->getCreatedAt());
    }

    public function testGetSetCoupon()
    {
        self::assertEquals(null,$this->cart->getCoupon());
        $this->cart->setCoupon('ABC');
        self::assertEquals('ABC',$this->cart->getCoupon());
    }

    public function testTotalDiscountBooksLessThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::FICTION_CATEGORY ,
                'price' => 200 ,
                'qty' => 2
            ]
        ];
        self::assertEquals(0,$this->cart->totalDiscount($array));
    }

    public function testTotalDiscountBooksLessThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::FICTION_CATEGORY,
                'price' => 200 ,
                'qty' => 2
            ]
        ];
        self::assertEquals(30,$this->cart->totalDiscount($array,'SAMPLE_COUPON'));
    }

    public function testTotalDiscountWhenChildrenBooksGreaterThanFiveAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::CHILDREN_CATEGORY,
                'price' => 600 ,
                'qty' => 6
            ]
        ];
        self::assertEquals(60,$this->cart->totalDiscount($array));
    }

    public function testTotalDiscountWhenChildrenBooksGreaterThanFiveAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::CHILDREN_CATEGORY,
                'price' => 500 ,
                'qty' => 5
            ]
        ];
        self::assertEquals(75,$this->cart->totalDiscount($array,'ASSS'));
    }

    public function testTotalDiscountWhenBothCategoryGraterThanTenAndWithoutCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::CHILDREN_CATEGORY,
                'price' => 1000 ,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Dangaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::FICTION_CATEGORY,
                'price' => 1000 ,
                'qty' => 10
            ]
        ];
        self::assertEquals(200,$this->cart->totalDiscount($array));
    }


    public function testTotalDiscountWhenBothCategoryGraterThanTenAndWithCoupon()
    {
        $array =  [
            [
                'id' => 1,
                'name' => 'Guru geethaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::CHILDREN_CATEGORY,
                'price' => 1000 ,
                'qty' => 10
            ],
            [
                'id' => 2,
                'name' => 'Dangaya',
                'author' => 'Nimal',
                'unit_price' => 100,
                'category' => Book::FICTION_CATEGORY,
                'price' => 1000 ,
                'qty' => 10
            ]
        ];
        self::assertEquals(300,$this->cart->totalDiscount($array,'SSSSSS'));
    }

}