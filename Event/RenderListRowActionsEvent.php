<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;

use Knp\Component\Pager\Pagination\PaginationInterface;
use devgiants\AdminBundle\Model\Action;
use Symfony\Component\EventDispatcher\Event;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RenderListRowActionsEvent extends Event
{

    /**
     * @var mixed one list record
     */
    private $record;

    /**
     * @var string the list options
     */
    private $options;
    /**
     * @var array[Actions]
     */
    private $actions;
    /**
     * @var TokenInterface $token
     */
    private $token;

    /**
     * @param mixed $record
     * @param array $options
     * @param TokenInterface $token
     */
    public function __construct($record, array $options, TokenInterface $token)
    {
        $this->record = $record;
        $this->options = $options;
        $this->token = $token;
        $this->actions = [];
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param TokenInterface $token
     * @return RenderListRowActionsEvent
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return RenderListRowActionsEvent
     */
    public function setActions(array $actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @param Action $action
     */
    public function addAction(Action $action) {
        if(!in_array($action, $this->actions)) {
            $this->actions[$action->getName()] = $action;
        }
    }

    /**
     * @param Action $action
     */
    public function removeAction(Action $action) {
        if(in_array($action, $this->actions)) {
            unset($this->actions[$action->getName()]);
        }
    }

    /**
     * @return mixed
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param mixed $record
     * @return RenderListRowActionsEvent
     */
    public function setRecord($record)
    {
        $this->record = $record;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $options
     * @return RenderListRowActionsEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}