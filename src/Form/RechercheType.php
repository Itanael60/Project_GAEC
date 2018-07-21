<?php

namespace App\Form;

use App\Entity\Produit;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
        ->add('recherche', TextType::class)
        ->add('rechercher', SubmitType::class, ['attr' => [
            'class' => 'btn btn-primary'
        ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }

    // public function getName()
    // {
    //     return $this->redirectToRoute('recherche');
    // }
}