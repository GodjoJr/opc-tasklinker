<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, ['label' => 'Nom', 'required' => true])
            ->add('firstname', TextType::class, ['label' => 'Prénom', 'required' => true])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => true])
            ->add('entry_date', DateType::class, ['label' => 'Date d\'entrée', 'widget' => 'single_text', 'required' => true])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Stagiaire' => 'Stagiaire',
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
