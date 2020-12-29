<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
    
    $this->encoder=$encoder;
}
public function load(ObjectManager $manager)
{
    $faker = Factory::create('fr_FR');
    for ($i=0; $i <4; $i++) {
        
    $user = new User() ; 
    $user ->setEmail($faker->email);
    $user ->setNom($faker->lastname);
    $user ->setPrenom($faker->firstname);
    $user ->setIsdelate('0');
    $user ->setTelephone($faker->phonenumber);
    $user ->setAdresse($faker->city);
    $password = $this ->encoder ->encodePassword ($user,'diop');
    $user ->setPassword($password);
    $user ->setProfile ($this->getReference($i));
    $manager ->persist ($user);
}

for ($i=0; $i <4; $i++) {
    $formateur = new Formateur() ;
    $formateur ->setEmail($faker->email);
    $formateur ->setNom($faker->lastname);
    $formateur ->setPrenom($faker->firstname);
    $formateur ->setIsdelate('0');
    $formateur ->setTelephone($faker->phonenumber);
    $formateur ->setAdresse($faker->city);
    $password = $this ->encoder ->encodePassword ($formateur,'diop');
    $formateur ->setPassword($password);
    $formateur ->setProfile ($this->getReference($i));
    $manager ->persist ($formateur);
}

    for ($i=0; $i <4; $i++) {
        $apprenant = new Apprenant() ;
        $apprenant ->setEmail($faker->email);
        $apprenant ->setNom($faker->lastname);
        $apprenant ->setPrenom($faker->firstname);
        $apprenant ->setAdresse($faker->city);
        $apprenant ->setTelephone($faker->phonenumber);

        $apprenant ->setIsdelate('0');
        $password = $this ->encoder ->encodePassword ($apprenant,'diop');
        $apprenant ->setPassword($password);
        $apprenant ->setProfile ($this->getReference($i));
        $manager ->persist ($apprenant);
    }


        for ($i=0; $i <4; $i++) {
            $cm = new Cm() ;
            $cm ->setEmail($faker->email);
            $cm ->setNom($faker->lastname);
            $cm ->setPrenom($faker->firstname);
            $cm ->setIsdelate('0');
            $cm ->setTelephone($faker->phonenumber);
            $cm ->setAdresse($faker->city);
            $password = $this ->encoder ->encodePassword ($cm,'diop');
            $cm ->setPassword($password);
            $cm ->setProfile ($this->getReference($i));

            $manager ->persist ($cm);
        }
            $manager ->flush();


}
}
