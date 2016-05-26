<?php

namespace My\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class)
                ->add('description', TextType::class)
                ->add('save', SubmitType::class);                              
    }
    
    public function getName(){
        return 'task';
    }
}
