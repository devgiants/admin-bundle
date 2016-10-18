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

class RenderListHeaderEvent extends Event
{
    /**
     * @var PaginationInterface The list records
     */
    private $records;
    /**
     * @var string the list options
     */
    private $options;

    /**
     * @param PaginationInterface $records
     * @param array $options
     */
    public function __construct(PaginationInterface $records, array $options)
    {
        $this->records = $records;
        $this->options = $options;
    }

    /**
     * @return PaginationInterface
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param PaginationInterface $records
     * @return RenderListHeaderEvent
     */
    public function setRecords($records)
    {
        $this->records = $records;
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
     * @return RenderListHeaderEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}