<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;


class RenderListRowCellEvent extends ListEvent
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
     * @var string $value the field initial value
     */

    private $value;

    /**
     * @param mixed $record the record (any entity)
     * @param string $field the field name
     * @param array $fieldConfiguration the field configuration
     * @param string $value the field initial value
     * @param array $options list options
     */
    public function __construct($record, $field, array $fieldConfiguration, $value, array $options)
    {
        parent::__construct($options);

        $this->record = $record;
        $this->field = $field;
        $this->fieldConfiguration = $fieldConfiguration;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getFieldConfiguration()
    {
        return $this->fieldConfiguration;
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
}