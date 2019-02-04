<?php

namespace App\Api\Form\Sales\Order;

use App\Api\Entity\Sales\Order\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_code')
            ->add('product_name')
            ->add('product_brand')
            ->add('quantity')
            ->add('subtotal')
            ->add('order')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
