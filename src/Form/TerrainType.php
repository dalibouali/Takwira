<?php

namespace App\Form;

use App\Entity\Terrain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tarif')
            ->add('unite')
            ->add('nom')
            ->add('jour_dispo',ChoiceType::class,[
            'choices'  => [
        'Lundi' => 1,
        'Mardi' => 2,
        'Mercredi' => 3,
                'Jeudi'=>4,
                'Vendredi'=>5,
                'Samedi'=>6,
                'Dimanche'=>0,
    ],
                'multiple' => true,
                'expanded' => false,
])
            ->add('heure_deb',TimeType::class,[
                'widget'=>'single_text',
                'html5'=>true])
            ->add('heure_fin',TimeType::class,[
                'widget'=>'single_text',
                'html5'=>true])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
