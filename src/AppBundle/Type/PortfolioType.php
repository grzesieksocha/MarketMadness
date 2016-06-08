<?php


namespace AppBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cashAmount', ChoiceType::class, array(
                'choices' => array(
                    10000 => '10,000 $',
                    20000 => '20,000 $',
                    50000 => '50,000 $',
                ),
                'placeholder' => 'Choose your portfolio value'
            ))
            ->add('difficulty', ChoiceType::class, array(
                'choices' => array(
                    'easy' => 'Easy',
                    'medium' => 'Medium',
                    'hard' => 'Hard',
                ),
                'placeholder' => 'Choose difficulty'
            ))
            ->add('Create', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Portfolio'
        ));
    }

}