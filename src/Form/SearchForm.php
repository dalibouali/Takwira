<?php

namespace App\Form;

use App\data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ville',ChoiceType::class,[
            'choices'=>[ 'Tunis' =>'Tunis',
                         'Ariana'=>'Ariana', 'BEJA'=>'BEJA',
                         'BEN AROUS'=> 'BEN AROUS',
                         'BIZERTE'=> 'BIZERTE',
                         'GABES'=>'GABES',
                         'GAFSA'=> 'GAFSA',
                         'JENDOUBA'=> 'JENDOUBA',
																		 'KAIROUAN'=>'KAIROUAN',
                                                                         'KASSERINE'=>'KASSERINE',
																		 'KEBILI'=> 'KEBILI',
																		 'KEF'=>'KEF',
																		 'MAHDIA'=> 'MAHDIA',
																		 'MANOUBA'=> 'MANOUBA',
																		 'MEDENINE'=> 'MEDENINE',
																		 'MONASTIR'=> 'MONASTIR',
																		 'NABEUL'=>'NABEUL',
																		 'SFAX'=> 'SFAX',
																		 'SIDI BOUZID'=>'SIDI BOUZID',
																		 'SILIANA'=>'SILIANA',
																		 'SOUSSE'=>	 'SOUSSE',
																		 'TATAOUINE'=>'TATAOUINE',
																		 'TOZEUR'=>'TOZEUR'],








        'attr'=>[ 'id' => 'load',
              'class'=>'dropdown-item']]);
    }


}