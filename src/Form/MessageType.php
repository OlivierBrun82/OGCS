<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder
            ->add('recipient', EntityType::class, [
                'class' => User::class,
                'label' => 'Destinataire',
                'choice_label' => 'email',
                'placeholder' => '—',
                'attr' => ['class' => 'form-select'],
                'query_builder' => function (UserRepository $repo) use ($currentUser) {
                    $qb = $repo->createQueryBuilder('u')->orderBy('u.email', 'ASC');
                    if ($currentUser instanceof User && $currentUser->getId() !== null) {
                        $qb->andWhere('u.id != :me')->setParameter('me', $currentUser->getId());
                    }

                    return $qb;
                },
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 6, 'class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'current_user' => null,
        ]);
        $resolver->setAllowedTypes('current_user', [User::class, 'null']);
    }
}
