<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;


class RenderListRowEvent extends ListEvent
{

    /**
     * @var mixed one list record
     */
    private $record;


    /**
     * @param mixed $record
     * @param array $options
     */
    public function __construct($record, array $options)
    {
        parent::__construct($options);

        $this->record = $record;
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
}