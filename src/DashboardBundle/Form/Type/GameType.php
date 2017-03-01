<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GameType
 *
 * @package ${NAMESPACE}
 */
class GameType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addName($builder);
        $this->addDescription($builder);
        $this->addStartType($builder, $options);
        $this->addStartCommand($builder);
        $this->addShutdownCommand($builder);
        $this->addRunningCommand($builder);
        $this->addMaxPlayers($builder);
        $this->addLogo($builder);
        $this->addSaveButton($builder);
    }

    /**
     *
     *
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addDescription(FormBuilderInterface $builder)
    {
        $builder->add(
            'description',
            TextType::class,
            array(
                'label' => 'dashboard.game.form.description.label',
                'trim' => true,
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addName(FormBuilderInterface $builder)
    {
        $builder->add(
            'name',
            TextType::class,
            array(
//                'max_length' => 50,
                'required'   => true,
                'label'      => 'dashboard.game.form.name.label',
                'trim'       => true,
                'attr'       => array(
                    'minlength' => 2,
                )
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addStartCommand(FormBuilderInterface $builder)
    {
        $builder->add(
            'startCommand',
            TextType::class,
            array(
//                'max_length' => 100,
                'required'   => true,
                'label'      => 'dashboard.game.form.start.label',
                'trim'       => true,
                'attr'       => array(
                    'minlength' => 2,
                )
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addShutdownCommand(FormBuilderInterface $builder)
    {
        $builder->add(
            'shutdownCommand',
            TextType::class,
            array(
//                'max_length' => 100,
                'required'   => true,
                'label'      => 'dashboard.game.form.shutdown.label',
                'trim'       => true,
                'attr'       => array(
                    'minlength' => 2,
                )
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addRunningCommand(FormBuilderInterface $builder)
    {
        $builder->add(
            'runningCommand',
            TextType::class,
            array(
//                'max_length' => 100,
                'required'   => false,
                'label'      => 'dashboard.game.form.running.label',
                'trim'       => true,
                'attr'       => array(
                    'minlength' => 2,
                )
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addMaxPlayers(FormBuilderInterface $builder)
    {
        $builder->add(
            'maxPlayer',
            IntegerType::class,
            array(
//                'max_length' => 100,
                'required'   => false,
                'label'      => 'dashboard.game.form.maxplayers.label',
            )
        );
    }

    public function addStartType(FormBuilderInterface $builder, $options)
    {
        $builder->add(
            'startType',
            ChoiceType::class,
            [
                'expanded' => false,
                'multiple' => false,
                'label'   => 'dashboard.game.form.starttype.label',
                'choices' => $options['data']->getStartTypes(),
                'choice_label' => function ($value, $key, $index) {
                    return 'dashboard.game.form.starttype.'.$key;
                },
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addLogo(FormBuilderInterface $builder)
    {
        $builder->add(
            'logo',
            FileType::class,
            array(
                'required' => false,
                'label' =>  'dashboard.game.form.logo.label',
            )
        );
    }

    /**
     * Add Save Button.
     *
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addSaveButton(FormBuilderInterface $builder)
    {
        $builder->add(
            'save',
            SubmitType::class,
            array(
                'label' => 'dashboard.game.form.save.label',
            )
        );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'DashboardBundle\Entity\Game'
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'dashboard_game_form';
    }
}
