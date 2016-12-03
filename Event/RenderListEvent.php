<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 18/05/15
 * Time: 16:01
 */
namespace devgiants\AdminBundle\Event;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RenderListEvent extends ListEvent
{

    /**
     * @var PaginationInterface The list records
     */
    private $records;

    /**
     * @var TokenInterface $token
     */
    private $token;

    /**
     * @param PaginationInterface $records
     * @param array $options
     * @param TokenInterface $token
     */
    public function __construct(PaginationInterface $records, array $options, TokenInterface $token)
    {
        $this->records = $records;
        $this->token = $token;

        parent::__construct($options);
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
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
     * @return RenderListEvent
     */
    public function setRecords($records)
    {
        $this->records = $records;
        return $this;
    }
}