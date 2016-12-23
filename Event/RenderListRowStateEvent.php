<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;


class RenderListRowStateEvent extends ListEvent
{

    /**
     * @var mixed one list record (any entity)
     */
    private $record;

    /**
     * @var string $field the field name
     */
    private $field;

    /**
     * @var array $fieldConfiguration the field configuration
     */
    private $fieldConfiguration;

    /**
     * @param mixed $record the record (any entity)
     * @param string $field the field name
     * @param array $fieldConfiguration the field configuration
     * @param array $options list options
     */
    public function __construct($record, $field, array $fieldConfiguration, array $options)
    {
        parent::__construct($options);

        $this->record = $record;
        $this->field = $field;
        $this->fieldConfiguration = $fieldConfiguration;
    }
    
    /**
     * @return array
     */
    public function getFieldConfiguration()
    {
        return $this->fieldConfiguration;
    }

    /**
     * @param array $fieldConfiguration
     * @return RenderListRowStateEvent
     */
    public function setFieldConfiguration($fieldConfiguration)
    {
        $this->fieldConfiguration = $fieldConfiguration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecord()
    {
        return $this->record;
    }


    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}