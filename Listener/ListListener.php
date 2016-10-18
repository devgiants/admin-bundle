<?php
/**
 * Created by PhpStorm.
 * User: nbonniot
 * Date: 16/09/16
 * Time: 15:36
 */

namespace devgiants\AdminBundle\Listener;

use Knp\Component\Pager\Pagination\PaginationInterface;

use devgiants\AdminBundle\Event\RenderListRowActionsEvent;
use devgiants\AdminBundle\Model\AdminIcons;
use devgiants\AdminBundle\Model\Action;

abstract class ListListener
{
    /**
     * Add common actions to all lists
     * TODO : externalisze this in config.yml as parameters
     * @param RenderListRowActionsEvent $actionsEvent
     * @return array[Action] $actions
     */
    protected function addCommonActions(RenderListRowActionsEvent $actionsEvent) {

        $options = $actionsEvent->getOptions();

        /************
         * Edit action
         */
        $editAction = new Action();
        $editAction
            ->setName('edit')
            ->setLabel($this->translator->trans('app.edit'))
            ->setRoute("{$options['baseRoute']}edit")
            ->setRouteParameters(['id' => $actionsEvent->getRecord()->getId()])
            ->setAttributes(['class' => 'btn btn-primary'])
            ->setIcon(AdminIcons::EDIT)
        ;
        $actionsEvent->addAction($editAction);

    }
}