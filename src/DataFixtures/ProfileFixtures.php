<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {$profils =["ADMIN" ,"FORMATEUR" ,"APPRENANT" ,"CM"];
        for ($i=0; $i < 4; $i++) { 
        $profil =new Profile() ;
        $profil->setLibelle ($profils[$i]);
        $profil->setArchive("1");
        $this->addReference($i,$profil);

        $manager ->persist ($profil);
       
        }
        $manager ->flush();
    }
}
