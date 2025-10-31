<?php

namespace App\DataFixtures;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $user = new Users();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setEmail($faker->email);
            $user->setEntryDate($faker->dateTimeBetween('-6 months'));
            $user->setStatus('CDI');

            $manager->persist($user);

            $users[] = $user;
        }



        $task1 = new Tasks();
        $task1->setTitle('Gestion des droits d\'accès');
        $task1->setDescription('Un employé ne peut accéder qu\'à ses projets');
        $task1->setDate($faker->dateTimeBetween('-2 months'));
        $task1->setStatus('To Do');
        $task1->addMember($faker->randomElement($users));

        $manager->persist($task1);

        $task2 = new Tasks();
        $task2->setTitle('Développement de la page employé');
        $task2->setDescription('Page employé avec liste des employés, édition, modification, suppression et création des employés');
        $task2->setDate($faker->dateTimeBetween('-2 months'));
        $task2->setStatus('Doing');
        $task2->addMember($faker->randomElement($users));

        $manager->persist($task2);

        $task3 = new Tasks();
        $task3->setTitle('Développement de la structure globale');
        $task3->setDescription('Intégrer les maquettes');
        $task3->setDate($faker->dateTimeBetween('-2 months'));
        $task3->setStatus('Done');
        $task3->addMember($faker->randomElement($users));

        $manager->persist($task3);

        $task4 = new Tasks();
        $task4->setTitle('Développement de la page projet');
        $task4->setDescription('Page projet avec liste des tâches, édition, modification, suppression et création des tâches');
        $task4->setDate($faker->dateTimeBetween('-2 months'));
        $task4->setStatus('Done');
        $task4->addMember($faker->randomElement($users));

        $manager->persist($task4);

        $task5 = new Tasks();
        $task5->setTitle('Titre fictif');
        $task5->setDescription('Description fictive');
        $task5->setDate($faker->dateTimeBetween('-2 months'));
        $task5->setStatus('To Do');
        $task5->addMember($faker->randomElement($users));

        $manager->persist($task5);

        $project1 = new Projects();
        $project1->setTitle('TaskLinker');
        $project1->setIsArchived(false);
        $project1->addMember($user);
        $project1->addTask($task1);
        $project1->addTask($task2);
        $project1->addTask($task3);
        $project1->addTask($task4);
        $manager->persist($project1);

        $project2 = new Projects();
        $project2->setTitle('Vehiloc');
        $project2->setIsArchived(false);
        $project2->addMember($user);
        $project2->addTask($task5);
        $manager->persist($project2);

        $manager->flush();
    }
}
