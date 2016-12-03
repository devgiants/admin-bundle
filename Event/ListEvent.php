<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 03/12/16
 * Time: 09:41
 */

namespace devgiants\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

abstract class ListEvent extends Event
{
    /**
     * @var array $options
     */
    protected $options;

    public function __construct(array $options) {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return ListEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}