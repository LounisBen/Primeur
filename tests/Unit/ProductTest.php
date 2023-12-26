<?php

namespace App\Tests\Unit;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    public function getEntity(): Product
    {
        return (new Product())
            ->setName('Product #1')
            ->setDescription('Description #1')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $product = $this->getEntity();

        $errors = $container->get('validator')->validate($product);

        $this->assertCount(0, $errors);
    }
    
    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $product = $this->getEntity();
        $product->setName('');

        $errors = $container->get('validator')->validate($product);
        $this->assertCount(1, $errors);
    }
}
