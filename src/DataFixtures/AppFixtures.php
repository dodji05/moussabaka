<?php

namespace App\DataFixtures;

use App\Entity\Candidat;

use App\Repository\CategorieCandidatsRepository;
use App\Repository\EcoledeProvenanceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;
use Faker\Provider\Image;

class AppFixtures extends Fixture
{
    public function __construct(CategorieCandidatsRepository $categorieCandidatsRepository,EcoledeProvenanceRepository $ecoledeProvenanceRepository)
    {
        $this->categorieCandidatsRepository=$categorieCandidatsRepository;
        $this->ecoledeProvenanceRepository=$ecoledeProvenanceRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $faker->addProvider(new Image($faker));
        $naba=$this->categorieCandidatsRepository->findOneBy([
            'id'=>1
        ]);
        $ank=$this->categorieCandidatsRepository->findOneBy([
            'id'=>1
        ]);
        $moulk=$this->categorieCandidatsRepository->findOneBy([
            'id'=>2
        ]);
        $moujadala=$this->categorieCandidatsRepository->findOneBy([
            'id'=>3
        ]);
        $ahqaf=$this->categorieCandidatsRepository->findOneBy([
            'id'=>4
        ]);
        $yassin=$this->categorieCandidatsRepository->findOneBy([
            'id'=>5
        ]);
        $ankabout=$this->categorieCandidatsRepository->findOneBy([
            'id'=>6
        ]);
        $nisf=$this->categorieCandidatsRepository->findOneBy([
            'id'=>7
        ]);
        $tawba=$this->categorieCandidatsRepository->findOneBy([
            'id'=>8
        ]);
        $kamil=$this->categorieCandidatsRepository->findOneBy([
            'id'=>9
        ]);

        $ecole1=$this->ecoledeProvenanceRepository->findOneBy([
            'id'=>1
        ]);


// Générer un chemin de fichier pour une image aléatoire
        $imagePath = $faker->image('public/uploads/candidats/photo', 400, 300, 'cats', false);

        for ($i = 0; $i < 20; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($naba);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

             }

        for ($i = 0; $i < 24; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($moulk);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        for ($i = 0; $i < 29; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($moujadala);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        for ($i = 0; $i < 37; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($ahqaf);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        for ($i = 0; $i < 13; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($yassin);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        for ($i = 0; $i <17; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($ankabout);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        for ($i = 0; $i < 32; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($nisf);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }
        for ($i = 0; $i < 26; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($tawba);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }
        for ($i = 0; $i < 11; $i++) {


            $candidat = new Candidat();
            $candidat->setPhoto($imagePath);
            $candidat->setCategorie($kamil);
            $candidat->setMom($faker->name);
            $candidat->setPrenom($faker->userName);
            $candidat->setLocalite($faker->city);
            $candidat->setEcoledeProvenance($ecole1);
            $candidat->setAge($faker->numberBetween(15,45));
            $manager->persist($candidat);

        }

        $manager->flush();
    }



}
