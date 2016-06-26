<?php

namespace My\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('description', TextType::class, ['label' => 'Description'])
                ->add('save', SubmitType::class, ['label' => 'Save']);                              
    }
    
    public function getName(){
        return 'task';
    }
}
