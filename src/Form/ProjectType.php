<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre du projet'])
            ->add('members', EntityType::class, [
                'class' => Users::class,
                'choice_label' => function ($user) {
                    return $user->getLastname() . ' ' . $user->getFirstname();
                },
                'multiple' => true,
                'label' => 'Membres',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
