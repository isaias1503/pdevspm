<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

           ->add('name')
           ->add('description')
           ->add('assignedTo', 'entity', [
                'class' => 'AppBundle\Entity\User',
                'property' => 'username',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('estimatedTime')
            ->add('dueDate')
            ->add('createdAt')
            ->add('closedDate')
            //->add('discussionId')
            ->add('startDate')
            ->add('progress')
            //->add('ticket')
            ->add('taskStatus', 'entity',   ['class' => 'AppBundle\Entity\TaskStatus', 'property' => 'name'])
            ->add('taskPriority', 'entity', ['class' => 'AppBundle\Entity\TaskPriority', 'property' => 'name'])
            ->add('taskType', 'entity',     ['class' => 'AppBundle\Entity\TaskType', 'property' => 'name'])
            ->add('taskLabel', 'entity',    ['class' => 'AppBundle\Entity\TaskLabel', 'property' => 'name'])
            ->add('taskGroup', 'entity',    ['class' => 'AppBundle\Entity\TaskGroup', 'property' => 'name'])
            //->add('projectPhase')
            //->add('versions')
            ->add('createdBy', 'entity',   ['class' => 'AppBundle\Entity\User', 'property' => 'username'])
            ->add('progress', 'choice', array(
                'choices' => array('5%' => '5%', '10%' => '10%', '15%' => '15%', '20%' => '20%', '25%' => '25%',
                                   '30%' => '30%', '35%' => '35%', '40%' => '40%', '45%' => '45%', '50%' => '50%', '55%' => '55%',
                                   '60%' => '60%', '65%' => '65%', '70%' => '70%', '75%' => '75%', '80%' => '80%', '85%' => '85%', '90%' => '90%',
                                   '95%' => '95%', '100%' => '100%',
                ), ));
            //TODO: In this moment this entity CRUD its in process to build. Ignore this comments. If they work.
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'task';
    }
}
