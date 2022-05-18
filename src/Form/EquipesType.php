<?php

namespace App\Form;

use App\Entity\Equipes;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EquipesType extends AbstractType
{
    private UrlGeneratorInterface $url;

    public function __construct( UrlGeneratorInterface $url )
    {
        $this->url = $url;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('membres',EntityType::class,[
                'class' => User::class,
                'choice_label' => "pseudo",
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                },
                'multiple' => true,
                'expanded' => false,
                'attr'=>[
                    'class'=>'js-example-basic-multiple'
                ]

            ])
            /*->add('members',SerchableUserType::class,[
                 'class' => User::class,
                 'search'=>$this->url->generate('users'),
                 'label'=>'members',
                 'value_property'=>'id',

                 ])*/
        ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipes::class,
        ]);
    }
}