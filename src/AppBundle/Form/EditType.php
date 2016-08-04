<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',HiddenType::class)
            ->add('username',TextType::class,array('label'=>false))
            ->add('password',PasswordType::class,array('label'=>false))
            ->add('firstname',TextType::class,array('label'=>false))
            ->add('lastname',TextType::class,array('label'=>false))
            ->add('email',EmailType::class,array('label'=>false))
            ->add('birthday',DateType::class,array('label'=>false))
            ->add('address',TextType::class,array('label'=>false))
            ->add('homephone',TextType::class,array('label'=>false))
            ->add('image',UrlType::class,array('label'=>false))
            ->add('role',TextType::class,array('label'=>false));
    }
    /**
     * Returns the default options/class for this form.
     * @param array $options
     * @return array The default options
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AppBundle\Entity\User'
        );
    }

}