<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks', name: 'app_tasks_')]
final class TasksController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {

        $task = $em->getRepository(Tasks::class)->find($id);
        $form = $this->createForm(TaskType::class, $task, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('tasks/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(int $id, Request $request, EntityManagerInterface $em): Response
    {

        $project = $em->getRepository(Projects::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet' . $id . 'non trouvé.');
        }

        $task = new Tasks();
        $task->setProjects($project);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('app_index');

        }

        return $this->render('tasks/add.html.twig', [
            'controller_name' => 'TasksController',
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {

        $task = $em->getRepository(Tasks::class)->find($id);
        if(!$task) {
            throw $this->createNotFoundException('Tâche' . $id . 'non trouvée.');
        }
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('app_index');
    }


}
