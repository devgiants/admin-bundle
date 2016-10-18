<?php

namespace devgiants\AdminBundle\Menu;

use Knp\Menu\ItemInterface;
use devgiants\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use devgiants\AdminBundle\Event\AdminEvents;
use devgiants\AdminBundle\Event\GenerateMenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const DATA_MENU_ROOT = "data-menu-root";

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TokenStorage
     */
    private $token;

    /**
     * @param FactoryInterface $factory
     * @param TokenStorage $token
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, TokenStorage $token, ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->token = $token;
        $this->setContainer($container);
    }

    /**
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface the menu items nested
     */
    public function adminMainMenu(array $options)
    {

        $user = $this->token->getToken()->getUser();

        // Options passed to ensure bootstrap tabs
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav nav-tabs',
            ),
        ));

        // If necessary, add "hard-coded" items BEFORE generated main menu here

        // SA menu
        if($user->hasRole(User::ROLE_SUPER_ADMIN)) {
            // TODO set cache for menu generation
            $this->container->get('event_dispatcher')->dispatch(
                AdminEvents::GENERATE_SUPER_ADMIN_MENU, new GenerateMenuEvent($this->factory, $menu)
            );
        }

        // Admin menu
        else if($user->hasRole(User::ROLE_ADMIN)) {
            $menu->setUri($this->container->get('request')->getRequestUri());
            $menu->addChild($this->container->get('translator')->trans('admin.dashboard.menu'), array(
                'route' => 'devgiants_admin_dashboard',
            ));

            // TODO set cache for menu generation
            $this->container->get('event_dispatcher')->dispatch(
                AdminEvents::GENERATE_ADMIN_MENU, new GenerateMenuEvent($this->factory, $menu)
            );

            $this->iterate($menu);
        }
        else if($user->hasRole(User::ROLE_PRODUCT_MANAGER)) {
            // TODO set cache for menu generation
            $this->container->get('event_dispatcher')->dispatch(
                AdminEvents::GENERATE_PRODUCT_MANAGER_MENU, new GenerateMenuEvent($this->factory, $menu)
            );

        }

        // If necessary, add "hard-coded" items AFTER generated main menu here
//        $this->iterate($menu);

        return $menu;
    }

//    /**
//     * @param FactoryInterface $factory used to create KNP menu
//     *
//     * @return \Knp\Menu\ItemInterface the menu items nested
//     */
//    public function superAdminMainMenu(FactoryInterface $factory)
//    {
//
//        // Options passed to ensure bootstrap tabs
//        $menu = $factory->createItem('root', array(
//            'childrenAttributes' => array(
//                'class' => 'nav nav-tabs',
//            ),
//        ));
//
//        // If necessary, add "hard-coded" items BEFORE generated main menu here
//
//        // TODO set cache for menu generation
//        $this->container->get('event_dispatcher')->dispatch(
//            AdminEvents::GENERATE_SUPER_ADMIN_MENU, new GenerateMenuEvent($factory, $menu)
//        );
//
//        // If necessary, add "hard-coded" items AFTER generated main menu here
////        $this->iterate($menu);
//
//        return $menu;
//    }
//
//    /**
//     * @param FactoryInterface $factory used to create KNP menu
//     *
//     * @return \Knp\Menu\ItemInterface the menu items nested
//     */
//    public function adminMainMenu(FactoryInterface $factory)
//    {
//        // Options passed to ensure bootstrap tabs
//        $menu = $factory->createItem('root', array(
//            'childrenAttributes' => array(
//                'class' => 'nav nav-tabs',
//            ),
//        ));
//
//        $menu->setUri($this->container->get('request')->getRequestUri());
//        $menu->addChild($this->container->get('translator')->trans('admin.dashboard.menu'), array(
//            'route' => 'devgiants_admin_dashboard',
//        ));
//
//        // TODO set cache for menu generation
//        $this->container->get('event_dispatcher')->dispatch(
//            AdminEvents::GENERATE_ADMIN_MENU, new GenerateMenuEvent($factory, $menu)
//        );
//
//        $this->iterate($menu);
//
//        return $menu;
//    }

    /**
     * Used to iterate on all child items in order to add common stuff or item-specific.
     *
     * @param $menu ItemInterface the menu to iterate
     */
    private function iterate($menu)
    {

        // Add common stuff if needed
        foreach ($menu as $child) {
            // Set role for Bootstrap
            $child->setAttribute('role', 'presentation');

            // Look for route parts in order to set current tag
            $childRouteParts = explode('_', $child->getExtras()['routes'][0]['route']);
            $currentRouteParts = explode('_', $this->container->get('request')->get('_route'));
//            array_pop($childRouteParts);
//            array_pop($currentRouteParts);

            if ($childRouteParts === $currentRouteParts) {
                $child->setCurrent(true);
            }
        }
    }
}
