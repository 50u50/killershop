<?php

namespace App\Api\Form\Catalog\Product;

use App\Api\Entity\Catalog\Product\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currency')
            ->add('base_price')
            ->add('product')
            ->add('discount')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
