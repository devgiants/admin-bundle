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

class RenderListRowCellEvent extends Event
{

    /**
     * @var mixed one list record
     */
    private $record;

    /**
     * @var string $field
     */
    private $field;

    /**
     * @var string $value
     */
    private $value;
    /**
     * @var string the list options
     */
    private $options;

    /**
     * @param mixed $record
     * @param string $field
     * @param string $value
     * @param array $options
     */
    public function __construct($record, $field, $value, array $options)
    {
        $this->record = $record;
        $this->field = $field;
        $this->value = $value;
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
     * @return RenderListRowCellEvent
     */
    public function setRecord($record)
    {
        $this->record = $record;
        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return RenderListRowCellEvent
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return RenderListRowCellEvent
     */
    public function setValue($value)
    {
        $this->value = $value;
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
     * @return RenderListRowCellEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}