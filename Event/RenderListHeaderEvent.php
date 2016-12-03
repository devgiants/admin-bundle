<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;

use Knp\Component\Pager\Pagination\PaginationInterface;

class RenderListHeaderEvent extends ListEvent
{
    /**
     * @var PaginationInterface The list records
     */
    private $records;


    /**
     * @param PaginationInterface $records
     * @param array $options
     */
    public function __construct(PaginationInterface $records, array $options)
    {
        $this->records = $records;
        parent::__construct($options);
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
}