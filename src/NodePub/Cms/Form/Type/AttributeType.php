<?php

namespace NodePub\Core\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributeType extends AbstractType
{
    protected $dataClass;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('value', 'text')
            ;
    }

    public function getName()
    {
        return 'attribute';
    }
    
    // public function setDefaultOptions(OptionsResolverInterface $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'data_class' => $this->getDataClass()
    //     ));
    // }
    // 
    // public function setDataClass($className)
    // {
    //     $this->dataClass = $className;
    // }
    // 
    // protected function getDataClass()
    // {
    //     return ($this->dataClass) ? $this->dataClass : 'NodePub\Core\Model\NodeAttribute';
    // }
}