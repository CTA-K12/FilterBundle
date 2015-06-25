<?php

namespace Mesd\FilterBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mesd\FilterBundle\Entity\Filter;
use Mesd\FilterBundle\Entity\FilterCell;
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
        $entityManager = $this->getDoctrine()->getManager();

        $entities = $entityManager->getRepository('MesdFilterBundle:Filter')->findAll();

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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

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
            'form'   => $form->createView()
        ));
    }

    /**
     * Finds and displays a Filter entity.
     *
     */
    public function showAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository('MesdFilterBundle:Filter')->find($id);

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
        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository('MesdFilterBundle:Filter')->find($id);

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
        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository('MesdFilterBundle:Filter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entityManager->flush();

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
            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository('MesdFilterBundle:Filter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Filter entity.');
            }

            $entityManager->remove($entity);
            $entityManager->flush();
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

    public function categoryDataAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository('MesdFilterBundle:FilterCategory')->find($id);
        $data = array(
            'url'          => $this->generateUrl('MesdUserBundle_filter_create_cell'),
            'associations' => array(),
        );
        foreach ($entity->getFilterAssociation() as $filterAssociation) {
            $id = $filterAssociation->getId();
            $cells = $entityManager->getRepository('MesdFilterBundle:FilterCell')->findByFilterAssociation($id);
            $name = $filterAssociation->getName();
            $code = str_replace(' ', '-', strtolower($name));
            $trailEntity = $filterAssociation->getTrailEntity();
            $entities = $entityManager->getRepository($trailEntity->getName())->findAll();
            $values = array();
            foreach($entities as $entity) {
                $values[] = array(
                    'id'   => $entity->getId(),
                    'name' => $entity->__toString(),
                );
            }
            $data['associations'][$code] = array(
                'cells'         => $cells,
                'code'          => $code,
                'associationId' => $id,
                'name'          => $name,
                'trailEntityId' => $trailEntity->getId(),
                'values'        => $values,
            );
        }

        $response = new JsonResponse();

        $response->setContent(
            json_encode($data)
        );

        return $response;
    }
    
    public function createCellAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cellJoin = $request->request->get('cell-join');

        if ('-1' === $cellJoin) {
            $filterCell = new FilterCell();
            $associationId = $request->request->get('association-id');
            $filterAssociation = $entityManager->getRepository('MesdFilterBundle:FilterAssociation')->find($associationId);
            $filterCell->setFilterAssociation($filterAssociation);
            $newCell = $request->request->get('new-cell');
            $trailEntityId = $request->request->get('trail-entity-id');
            $filterEntity = $entityManager->getRepository('MesdFilterBundle:FilterEntity')->find($trailEntityId);
            $entities = $entityManager->getRepository($filterEntity->getName())->findById($newCell);
            $description = $filterAssociation->getName() . ' is ';
            $value = '';
            $length = count($entities);
            for ($i = 0; $i < $length; $i++) {
                if (0 < $i) {
                    $description .= ', ';
                    $value .= ',';
                }
                if ($length === ($i + 1)) {
                    $description .= 'or ';
                }
                $description .= $entities[$i]->__toString();
                $value .= $entities[$i]->getId();
            }
            $filterCell->setDescription($description);
            $filterCell->setValue($value);
            $entityManager->persist($filterCell);
            $entityManager->flush($filterCell);
        } else {
            $filterCell = $entityManager->getRepository('MesdFilterBundle:FilterCell')->find($cellJoin);
        }

        $data = array(
            'id'    => $filterCell->getId(),
            'name'  => $filterCell->getDescription(),
            'value' => $filterCell->getValue(),
        );
        $response = new JsonResponse();

        $response->setContent(
            json_encode($data)
        );

        return $response;
    }
}
