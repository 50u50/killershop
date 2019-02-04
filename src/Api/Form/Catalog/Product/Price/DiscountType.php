<?php

namespace App\Api\Form\Catalog\Product\Price;

use App\Api\Entity\Catalog\Product\Price\Discount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rule')
            ->add('value')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
