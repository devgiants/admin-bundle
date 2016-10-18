<?php

namespace devgiants\AdminBundle\Event\Subscriber\Sortable;

use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SimpleArraySubscriber implements EventSubscriberInterface
{
    /**
     * @var string the field used to sort current object array list
     */
    private $currentSortingFieldGetter;

    /**
     * @var string the sorting direction
     */
    private $sortDirection;

    public function items(ItemsEvent $event)
    {
        if (is_array($event->target) && count($event->target) > 1) {
            if (isset($_GET[$event->options['sortFieldParameterName']])) {
                $this->sortDirection = isset($_GET[$event->options['sortDirectionParameterName']]) && strtolower($_GET[$event->options['sortDirectionParameterName']]) === 'asc' ? 'asc' : 'desc';

                $sortFieldParameterName = explode('.', $_GET[$event->options['sortFieldParameterName']]);

                if (isset($sortFieldParameterName[1])) {
                    // Capitalize first letter in order to prepare getter construction
                    $sortFieldName = ucfirst($sortFieldParameterName[1]);

                    // Getter detection
                    $class = new \ReflectionClass(get_class($event->target[0]));
                    if ($class->hasMethod($sortFieldName)) {
                        $this->currentSortingFieldGetter = $sortFieldName;
                    } elseif ($class->hasMethod('get'.$sortFieldName)) {
                        $this->currentSortingFieldGetter = "get{$sortFieldName}";
                    }

                    if (isset($this->currentSortingFieldGetter) && null !== $this->currentSortingFieldGetter) {
                        usort($event->target, array($this, 'sort'.ucfirst($this->sortDirection)));
                    }
                }
            }
        }
    }

    /**
     * @param object $object1
     * @param object $object2
     *
     * @return int
     */
    private function sortAsc($object1, $object2)
    {
        $fieldValue1 = $object1->{$this->currentSortingFieldGetter}();
        $fieldValue2 = $object2->{$this->currentSortingFieldGetter}();

        if (is_array($fieldValue1)) {
            $fieldValue1 = implode(',', $fieldValue1);
        }

        if (is_array($fieldValue2)) {
            $fieldValue2 = implode(',', $fieldValue2);
        }

        $fieldValue1 = strtolower($fieldValue1);
        $fieldValue2 = strtolower($fieldValue2);

        if ($fieldValue1 == $fieldValue2) {
            return 0;
        }

        return ($fieldValue1 > $fieldValue2) ? +1 : -1;
    }

    /**
     * @param object $object1
     * @param object $object2
     *
     * @return int
     */
    private function sortDesc($object1, $object2)
    {
        $fieldValue1 = $object1->{$this->currentSortingFieldGetter}();
        $fieldValue2 = $object2->{$this->currentSortingFieldGetter}();

        if (is_array($fieldValue1)) {
            $fieldValue1 = implode(',', $fieldValue1);
        }

        if (is_array($fieldValue2)) {
            $fieldValue2 = implode(',', $fieldValue2);
        }

        $fieldValue1 = strtolower($fieldValue1);
        $fieldValue2 = strtolower($fieldValue2);

        if ($fieldValue1 == $fieldValue2) {
            return 0;
        }

        return ($fieldValue1 > $fieldValue2) ? -1 : +1;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 1),
        );
    }
}
