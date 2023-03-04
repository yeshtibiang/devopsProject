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

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shortDescription', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Description courte',
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'label' => 'Description',
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
                'query_builder' => fn (UserRepository $er) => $er->createQueryBuilder('u')
                    ->where('u.email = :email')
                    ->setParameter('email', $options['user']->getEmail())
                    ->orderBy('u.lastName', 'ASC')
                    ->addOrderBy('u.firstName', 'ASC'),
            ])
            ->add('assignedTo', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => User::class,
                'label' => 'Assigné à',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'user' => null,
        ]);
    }


}
