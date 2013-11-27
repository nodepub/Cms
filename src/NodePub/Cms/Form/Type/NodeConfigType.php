<?php

namespace NodePub\Cms\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use NodePub\Cms\Form\Type\AttributeType;

class NodeConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('slug', 'text')
            ->add('attributes', 'collection', array('type' => new AttributeType()));
            //->add('site', 'hidden')
            ;
    }

    public function getName()
    {
        return 'node_config';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NodePub\Core\Model\Node',
        ));
    }
}