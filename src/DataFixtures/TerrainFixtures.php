<?php

namespace App\DataFixtures;

use App\Entity\Complex;
use App\Entity\Terrain;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class TerrainFixtures extends  Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 3; $i <= 20; $i++) {
        $comp= new Complex();
        $comp->setNom('Complex maracana'.$i);
        $comp->setAdress('23 Rue foulen el fouleni');
        if ($i/2 ==0){$comp->setVille('Tunis');}else{$comp->setVille('Ariana');}

        $manager->persist($comp);}

       for ($i = 1; $i <= 5; $i++) {
        $Terrain= new Terrain();
        $Terrain->setNom("Terrain {$Terrain->getid()}");
        $Terrain->setTarif(70);
        $Terrain->setUnite(1);
        $Terrain->setComplex($comp);
            $manager->persist($Terrain);
           }
        $manager->flush();

    }
}
