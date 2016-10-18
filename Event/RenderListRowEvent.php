<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\Event;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class RenderListRowEvent extends Event
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
     * @param mixed $record
     * @param array $options
     */
    public function __construct($record, array $options)
    {
        $this->record = $record;
        $this->options = $options;
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
     * @return RenderListRowEvent
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
     * @return RenderListRowEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}