<?php

namespace devgiants\AdminBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use devgiants\AppBundle\Form\SearchType as BaseType;

class searchAdminType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $datas = $this->container->getParameter('devgiants_app.searchEngine.back');

        $choicesList = [];
        foreach ($datas as $entity => $config) {
            $formatName = explode('\\', $entity);
            $formatedName = $formatName[sizeof($formatName) - 1];
            $choicesList['searchAdminForm.'.$formatedName] = $formatedName;
        }

        $builder->add('type', ChoiceType::class, [
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices_as_values' => true,
            'choices' => $choicesList,
            'placeholder' => 'admin.search.allCat',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }
}
