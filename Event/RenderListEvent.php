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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RenderListEvent extends Event
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
     * @var TokenInterface $token
     */
    private $token;

    /**
     * @param PaginationInterface $records
     * @param array $options
     */
    public function __construct(PaginationInterface $records, array $options, TokenInterface $token)
    {
        $this->records = $records;
        $this->options = $options;
        $this->token = $token;
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

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $options
     * @return RenderListEvent
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}