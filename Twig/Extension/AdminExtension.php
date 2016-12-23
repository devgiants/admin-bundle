<?php

namespace devgiants\AdminBundle\Twig\Extension;

use devgiants\AdminBundle\Event\RenderListRowStateEvent;
use devgiants\AdminBundle\Exception\MissingGetterException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use devgiants\AdminBundle\Event\AdminEvents;
use devgiants\AdminBundle\Event\RenderListEvent;
use devgiants\AdminBundle\Event\RenderListEventAfter;
use devgiants\AdminBundle\Event\RenderListEventBefore;
use devgiants\AdminBundle\Event\RenderListHeaderEvent;
use devgiants\AdminBundle\Event\RenderListRowActionsEvent;
use devgiants\AdminBundle\Event\RenderListRowCellEvent;
use devgiants\AdminBundle\Event\RenderListRowEvent;
use devgiants\AdminBundle\Event\RenderPreHeaderEvent;
use devgiants\AdminBundle\Exception\MissingOptionException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AdminExtension
 */
class AdminExtension extends \Twig_Extension
{

    private static $mandatoryOptions = [
        'baseRoute' => 'used to start routes for meta-actions on list',
        'fields' => 'fields to display on list'
    ];
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var TraceableEventDispatcher
     */
    private $dispatcher;

    /**
     * @var TokenInterface $tokenStorage
     */
    private $tokenStorage;

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var PropertyAccess
     */
    private $propertyAccessor;

    public function __construct(EntityManager $entityManager, Session $session, \Twig_Environment $twig, EventDispatcherInterface $dispatcher, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->em = $entityManager;
        $this->session = $session;
        $this->twig = $twig;
        $this->dispatcher = $dispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        // TODO add function.filter to check entity class (eq. to instanceof)
        return array(
            'renderPreHeader' => new \Twig_SimpleFunction(
                'renderPreHeader',
                array($this, 'renderPreHeader'),
                array('is_safe' => array('html'))
            ),
            'renderList' => new \Twig_SimpleFunction(
                'renderList',
                array($this, 'renderList'),
                array('is_safe' => array('html'))
            ),
            'renderHeader' => new \Twig_SimpleFunction(
                'renderHeader',
                array($this, 'renderHeader'),
                array('is_safe' => array('html'))
            ),
            'renderRow' => new \Twig_SimpleFunction(
                'renderRow',
                array($this, 'renderRow'),
                array('is_safe' => array('html'))
            ),
            'renderCell' => new \Twig_SimpleFunction(
                'renderCell',
                array($this, 'renderCell'),
                array('is_safe' => array('html'))
            ),
            'renderActions' => new \Twig_SimpleFunction(
                'renderActions',
                array($this, 'renderActions'),
                array('is_safe' => array('html'))
            ),
            'renderBeforeList' => new \Twig_SimpleFunction(
                'renderBeforeList',
                array($this, 'renderBeforeList'),
                array('is_safe' => array('html'))
            ),
            'renderAfterList' => new \Twig_SimpleFunction(
                'renderAfterList',
                array($this, 'renderAfterList'),
                array('is_safe' => array('html'))
            ),
        );
    }



    /**
     * Render an entity list
     * @param PaginationInterface $records
     * @param array $options
     * @return string
     */
    public function renderList(PaginationInterface $records, array $options) {

        // Check for mandatory options in list
        foreach(self::$mandatoryOptions as $option => $explanation) {
            if(!isset($options[$option])) {
                throw new MissingOptionException("Option '{$option}' should be filled in renderList options array ({$explanation})");
            }
        }
        $listEvent = new RenderListEvent($records, $options, $this->tokenStorage->getToken());
        // Dispatch render list event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_LIST_PREFIX . $options['entity']['name'], $listEvent
        );
        // Bring back options as array not updated by reference
        $options = $listEvent->getOptions();


        return $this->twig->render("devgiantsAdminBundle:List:table.html.twig", ['records' => $records, "options" => $options]);
    }

    /**
     * Render table header
     * @param PaginationInterface $records
     * @param array $options
     * @return string
     */
    public function renderHeader(PaginationInterface $records, array $options) {

        $headerEvent = new RenderListHeaderEvent($records, $options);
        // Dispatch render list event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_HEADER_PREFIX . $options['entity']['name'], $headerEvent
        );
        // Bring back options as array not updated by reference
        $options = $headerEvent->getOptions();

        return $this->twig->render("devgiantsAdminBundle:List:header.html.twig", ['records' => $records, "options" => $options]);
    }

    /**
     * Render an entity row
     * @param object $record
     * @param array $options
     * @return string
     */
    public function renderRow($record, array $options) {

        $rowEvent = new RenderListRowEvent($record, $options);
        // Dispatch render list row event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_ROW_PREFIX . $options['entity']['name'], $rowEvent
        );
        // Bring back options as array not updated by reference
        $options = $rowEvent->getOptions();

        return $this->twig->render("devgiantsAdminBundle:List:row.html.twig", ['record' => $record, "options" => $options]);
    }

    /**
     * Render an cell in a row
     * @param mixed $record the concerned record
     * @param string $field the field name
     * @param array $fieldConfiguration the field configuration
     * @param array $options the list options
     * @return string
     */
    public function renderCell($record, $field, $fieldConfiguration, array $options) {

        
        // Classic fields
        // TODO remove ugly hard-coded fields names
        if($field != 'stateFields') {

            // Handle value computation/alteration
//            $cellEvent = $this->computeRecordValue($record, $field, $fieldConfiguration, $options);


            // Check field existance and readability
            $this->checkField($record, $field);

            // Get current value
            $cellValue = $this->propertyAccessor->getValue($record, $field);

            $cellEvent = new RenderListRowCellEvent($record, $field, $fieldConfiguration, $cellValue, $options);
            // Dispatch render list row cell event
            $this->dispatcher->dispatch(
                AdminEvents::RENDER_CELL_PREFIX . $options['entity']['name'], $cellEvent
            );
            
            return $this->twig->render("devgiantsAdminBundle:List:cell.html.twig", [
                    'field' => $field,
                    'value' => $cellEvent->getValue(),
                    'options' => $cellEvent->getOptions()
                ]
            );
        }
        else {
            $mergedValue = "";
            foreach($fieldConfiguration['fields'] as $fieldName => $fieldParams) {

                $this->checkField($record, $fieldName);

                // Get current value
                $stateValue = $this->propertyAccessor->getValue($record, $fieldName);

                $stateEvent = new RenderListRowStateEvent($record, $field, $fieldConfiguration, $options);
                // Dispatch render list row cell event
                $this->dispatcher->dispatch(
                    AdminEvents::RENDER_ROW_STATE_PREFIX . $options['entity']['name'], $stateEvent
                );

                // TODO set constants for all fields names. Find a cool way to use Twig with
                $mergedValue .= $this->twig->render("devgiantsAdminBundle:List:state.html.twig", ['stateValue' => $stateEvent->getFieldConfiguration(), 'fieldParams' => $fieldParams]);
//                if(!isset($fieldParams['logic']) || isset($fieldParams['logic']) && $fieldParams['logic'] ) {
//                    if($stateValue) {
//                        $mergedValue .= "<span class='label label-success'>{$this->translator->trans($fieldParams['active_label'])}</span> ";
//                    } else {
//                        $mergedValue .= "<span class='label label-danger'>{$this->translator->trans($fieldParams['inactive_label'])}</span> ";
//                    }

//                } else {
//                    if(!$stateValue) {
//                        $mergedValue .= "<span class='label label-success'>{$this->translator->trans($fieldParams['active_label'])}</span> ";
//                    } else {
//                        $mergedValue .= "<span class='label label-danger'>{$this->translator->trans($fieldParams['inactive_label'])}</span> ";
//                    }
//                }

            }
            return $this->twig->render("devgiantsAdminBundle:List:cell.html.twig", ['field' => $field, 'value' => $mergedValue, "options" => $options]);
        }
    }

    /**
     * Render actions for entity row
     * @param object $record
     * @param array $options
     * @return string
     */
    public function renderActions($record, array $options) {

        // Dispatch render list row cell event
        $actionEvent = new RenderListRowActionsEvent($record, $options, $this->tokenStorage->getToken());
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_ACTIONS_PREFIX . $options['entity']['name'], $actionEvent
        );
        // Bring back options as array not updated by reference
        $options = $actionEvent->getOptions();

        return $this->twig->render("devgiantsAdminBundle:List:actions.html.twig", ['record' => $record, "options" => $options, 'actions' => $actionEvent->getActions()]);
    }


    /**
     * @param PaginationInterface $records
     * @param array $options
     * @return string
     */
    public function renderBeforeList(PaginationInterface $records, array $options) {
        $beforeListEvent = new RenderListEventBefore($records, $options);
        // Dispatch render list event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_LIST_BEFORE_PREFIX . $options['entity']['name'], $beforeListEvent
        );
        // Bring back options as array not updated by reference
        $options = $beforeListEvent->getOptions();

        // TODO find a more industrial way to display additional stuff
        return $beforeListEvent->getOutput();
    }

    /**
     * @param PaginationInterface $records
     * @param array $options
     * @return string
     */
    public function renderPreHeader(PaginationInterface $records, array $options) {
        $preHeaderEvent = new RenderPreHeaderEvent($records, $options);
        // Dispatch specific render list event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_PRE_HEADER_PREFIX . $options['entity']['name'], $preHeaderEvent
        );
        // Bring back options as array not updated by reference
        $options = $preHeaderEvent->getOptions();

        // TODO find a more industrial way to display additional stuff
        return $preHeaderEvent->getOutput();
    }

    /**
     * @param PaginationInterface $records
     * @param array $options
     * @return string
     */
    public function renderAfterList(PaginationInterface $records, array $options) {
        $afterListEvent = new RenderListEventAfter($records, $options);
        // Dispatch render list event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_LIST_AFTER_PREFIX . $options['entity']['name'], $afterListEvent
        );
        // Bring back options as array not updated by reference
        $options = $afterListEvent->getOptions();

        // TODO find a more industrial way to display additional stuff
        return $afterListEvent->getOutput();
    }

    /**
     * @param $object
     * @param $field
     */
    private function checkField($object, $field) {
        if(!$this->propertyAccessor->isReadable($object, $field)) {
            throw new MissingGetterException("{$field} doesn't exist on object");
        }
    }

    /**
     * @param mixed $record the concerned record
     * @param string $field the field name
     * @param array $fieldConfiguration the field configuration
     * @param array $options the list options
     * @return RenderListRowCellEvent
     */
    private function computeRecordValue($record, $field, $fieldConfiguration, $options) {

        // Check field existance and readability
        $this->checkField($record, $field);

        // Get current value
        $cellValue = $this->propertyAccessor->getValue($record, $field);

        $cellEvent = new RenderListRowCellEvent($record, $field, $fieldConfiguration, $cellValue, $options);
        // Dispatch render list row cell event
        $this->dispatcher->dispatch(
            AdminEvents::RENDER_CELL_PREFIX . $options['entity']['name'], $cellEvent
        );

        return $cellEvent;
    }
}