<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CmFixtures extends Fixture
{
private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){

    $this->encoder=$encoder;
}
public function load(ObjectManager $manager)
{
    $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
