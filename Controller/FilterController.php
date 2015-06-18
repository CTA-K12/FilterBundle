<?php

namespace Mesd\FilterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\FilterBundle\Entity\Filter;
use Mesd\FilterBundle\Form\FilterType;

/**
 * Filter controller.
 *
 */
class FilterController extends Controller
{

    /**
     * Lists all Filter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MesdFilterBundle:Filter')->findAll();

        return $this->render('MesdFilterBundle:Filter:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Filter entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Filter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('filter_show', array('id' => $entity->getId())));
        }

        return $this->render('MesdFilterBundle:Filter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Filter entity.
     *
     * @param Filter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Filter $entity)
    {
        $form = $this->createForm(new FilterType(), $entity, array(
            'action' => $this->generateUrl('filter_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Filter entity.
     *
     */
    public function newAction()
    {
        $entity = new Filter();
        $form   = $this->createCreateForm($entity);

        return $this->render('MesdFilterBundle:Filter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Filter entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdFilterBundle:Filter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdFilterBundle:Filter:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Filter entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdFilterBundle:Filter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filter entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MesdFilterBundle:Filter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Filter entity.
    *
    * @param Filter $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Filter $entity)
    {
        $form = $this->createForm(new FilterType(), $entity, array(
            'action' => $this->generateUrl('filter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Filter entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MesdFilterBundle:Filter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('filter_edit', array('id' => $id)));
        }

        return $this->render('MesdFilterBundle:Filter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Filter entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MesdFilterBundle:Filter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Filter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('filter'));
    }

    /**
     * Creates a form to delete a Filter entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('filter_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
