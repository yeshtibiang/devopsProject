<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Ticket1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shortDescription', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Description courte',
                'disabled' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'label' => 'Description',
                'disabled' => true,
            ])
            ->add('status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'Reçu' => 'Reçu',
                    'En cours' => 'En cours',
                    'En attente' => 'En attente',
                    'Ne pas traiter' => 'Ne pas traiter',
                    'Terminé' => 'Terminé',
                    'Clôturé' => 'Clôturé'
                ],
                'label' => 'Statut',
            ])
            ->add('createdBy', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => User::class,
                'label' => 'Créé par',
                'disabled' => true,
            ])
        ;

        // if user is role_user desactivate status field
        if ($options['user']->getRoles()[0] === 'ROLE_USER') {
            $builder->get('status')->setDisabled(true);
        }



        if ($options['user']->getRoles()[0] === 'ROLE_ADMIN') {
            $builder->add('assignedTo', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => User::class,
                'label' => 'Assigné à',
                'query_builder' => fn (UserRepository $er) => $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :role or u.roles LIKE :role2')
                    ->setParameter('role', '%ROLE_AGENT%')
                    ->setParameter('role2', '%ROLE_ADMIN%')
                    ->orderBy('u.lastName', 'ASC')
                    ->addOrderBy('u.firstName', 'ASC'),
            ]);
        } else {
            $builder->add('assignedTo', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => User::class,
                'label' => 'Assigné à',
                'query_builder' => fn (UserRepository $er) => $er->createQueryBuilder('u')
                    ->where('u.email = :email')
                    ->setParameter('email', $options['user']->getEmail())
                    ->orderBy('u.lastName', 'ASC')
                    ->addOrderBy('u.firstName', 'ASC'),
            ]);

            // if user has role_agent or role_admin and status is not Reçu desactivate status field
            if ($options['user']->getRoles()[0] === 'ROLE_AGENT' or $options['user']->getRoles()[0] === 'ROLE_ADMIN') {
                if ($builder->getData()->getStatus() !== 'Reçu') {
                    $builder->get('assignedTo')->setDisabled(true);
                }
            }

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'user' => null,
        ]);
    }
}
