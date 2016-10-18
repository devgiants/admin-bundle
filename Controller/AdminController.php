<?php

namespace devgiants\AdminBundle\Controller;

use devgiants\AdminBundle\Form\ArchiveType;
use devgiants\AdminBundle\Form\UnarchiveType;
use devgiants\AdminBundle\Form\DeleteType;
use devgiants\MultisiteBundle\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    /**
     * @var Site
     */
    protected $currentSite;


    /**
     * @param Request $request
     */
    protected function initializeSite(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->currentSite = $em->getRepository('LCHMultisiteBundle:Site')->findOneBy([
            'id' => $request->getSession()->get('currentSite'),
        ]);
    }

    /**
     * TODO Find a way to do this with an annotation.
     *
     * @param object $entity
     */
    protected function controlSite($entity)
    {
        if (property_exists($entity, 'site') && $entity->getSite() !== $this->currentSite) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Build a form form archive action.
     *
     * @param string $route
     * @param array  $param
     *
     * @return Form
     */
    protected function getArchiveForm($route, $param = [])
    {
        return $this->createForm(ArchiveType::class, null, [
            'action' => $this->generateUrl($route, $param),
        ]);
    }

    /**
     * @param Form $archiveForm
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getArchiveModal(Form $archiveForm)
    {
        return $this->render('LCHAdminBundle:Modal:admin_archive_modal_content.html.twig', array(
            'archiveForm' => $archiveForm->createView(),
        ));
    }

    /**
     * Build a form form unarchive action.
     *
     * @param string $route
     * @param array  $param
     *
     * @return Form
     */
    protected function getUnarchiveForm($route, $param = [])
    {
        return $this->createForm(UnarchiveType::class, null, [
            'action' => $this->generateUrl($route, $param),
        ]);
    }

    protected function getDeleteForm($route, $param = [])
    {
        return $this->createForm(DeleteType::class, null, [
            'action' => $this->generateUrl($route, $param),
        ]);
    }

    /**
     * @param Form $deleteForm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getDeleteModal(Form $deleteForm)
    {
        return $this->render('LCHAdminBundle:Modal:admin_delete_modal_content.html.twig', array(
            'deleteForm' => $deleteForm->createView(),
        ));
    }

    /**
     * Add a success flash message.
     *
     * @param string $message
     */
    protected function addSuccessFLashMessage($message)
    {
        $this->addFlashMessage('success', $message);
    }

    /**
     * Add a error flash message.
     *
     * @param $message
     */
    protected function addErrorFlashMessage($message)
    {
        $this->addFlashMessage('error', $message);
    }

    /**
     * Main method to add a flash message.
     *
     * @param string $type
     * @param string $message
     */
    private function addFlashMessage($type, $message)
    {
        $this->get('session')->getFlashBag()->add(
            $type,
            $this->get('translator')->trans($message)
        );
    }
}
