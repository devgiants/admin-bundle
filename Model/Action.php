<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 15/09/16
 * Time: 17:06
 */

namespace devgiants\AdminBundle\Model;


use devgiants\AdminBundle\Exception\ActionTypeNotValidException;

class Action
{
    const BUTTON = "button";
    const LINK = "link";
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $label
     */
    private $label;
    /**
     * @var string $route
     */
    private $route;
    /**
     * @var array $routeParameters
     */
    private $routeParameters;
    /**
     * @var array $attributes
     */
    private $attributes;
    /**
     * @var string $icon
     */
    private $icon;
    /**
     * @var string $type link type
     */
    private $type;


    public function __construct($type = self::LINK) {
        $this->setType($type);
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Action
     */
    public function setType($type)
    {
        $this->checkType($type);
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return Action
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return Action
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Action
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Action
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param array $routeParameters
     * @return Action
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;
        return $this;
    }

    private function checkType($type) {
        if($type !== self::BUTTON && $type !== self::LINK) {
            throw new ActionTypeNotValidException('The action type is not valid : either LINK or BUTTON accepted');
        }
    }
}