<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 22/05/15
 * Time: 12:00.
 */

namespace devgiants\AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DoubleListTypeExtension extends AbstractTypeExtension
{
    const MASTER_DETAIL = 'double_list';

    /**
     * Send back the extended form type.
     *
     * @return string The extended form type name
     */
    public function getExtendedType()
    {
        return EntityType::class;
    }

    /**
     * Add master_detail option.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(self::MASTER_DETAIL));
    }

    /**
     * Give master_detail option to view.
     *
     * @param \Symfony\Component\Form\FormView      $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array                                 $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists(self::MASTER_DETAIL, $options)) {
            //            $parentData = $form->getParent()->getData();
//
//            if (null !== $parentData) {
//                $accessor = PropertyAccess::createPropertyAccessor();
//                $masterDetail = $accessor->getValue($parentData, $options[self::MASTER_DETAIL]);
//            } else {
//                $masterDetail = false;
//            }

            // définit une variable "image_url" qui sera disponible à l'affichage du champ
            $view->vars[self::MASTER_DETAIL] = $options[self::MASTER_DETAIL];
        }
        parent::buildView($view, $form, $options);
    }
}
