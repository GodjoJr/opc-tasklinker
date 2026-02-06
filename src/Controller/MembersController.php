<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\MemberType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/members', name: 'app_members_')]
final class MembersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {

        $members = $em->getRepository('App\Entity\Users')->findAll();

        return $this->render('members/index.html.twig', [
            'controller_name' => 'MembersController',
            'members' => $members,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {

        /** @var Users $user */
        $user = $this->getUser();
        $member = $em->getRepository(Users::class)->find($id);

        if (!$member) {
            throw $this->createNotFoundException('Membre ' . $id . ' non trouvé.');
        }

        if (in_array('ROLE_ADMIN', $member->getRoles(), true) && $user->getId() !== $member->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier un administrateur.');
        }

        if ($user->getId() !== $member->getId() && (!in_array('ROLE_MANAGER', $user->getRoles(), true) || $member->getRoles() !== ['ROLE_USER'])) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour modifer ce membre.');
        }

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

    #[IsGranted('ROLE_USER')]
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, EntityManagerInterface $em, Security $security): Response
    {
        /** @var Users $user */
        $user = $this->getUser();

        $member = $em->getRepository(Users::class)->find($id);

        if (!$member) {
            throw $this->createNotFoundException('Membre ' . $id . ' non trouvé.');
        }

        if (in_array('ROLE_ADMIN', $member->getRoles(), true)) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer un administrateur.');
        }

        if ($user->getId() !== $member->getId() && (!in_array('ROLE_MANAGER', $user->getRoles(), true) || $member->getRoles() !== ['ROLE_USER'])) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour supprimer ce membre.');
        }

        if ($user->getId() === $member->getId()) {
            $security->logout(false);
        }

        $em->remove($member);
        $em->flush();



        return $this->redirectToRoute('app_members_index');
    }

}
