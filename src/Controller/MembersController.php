<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\MemberType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/members', name: 'app_members_')]
final class MembersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {

        $members = $em->getRepository('App\Entity\Users')->findAll();

        return $this->render('members/index.html.twig', [
            'controller_name' => 'MembersController',
            'members' => $members
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {

        $member = $em->getRepository(Users::class)->find($id);
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($member);
            $em->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('members/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $member = $em->getRepository(Users::class)->find($id);

        if(!$member) {
            throw $this->createNotFoundException('Membre' . $id . 'non trouvé.');
        }
        $em->remove($member);
        $em->flush();
        return $this->redirectToRoute('app_members_index');
    }
}
