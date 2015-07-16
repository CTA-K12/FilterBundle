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
            $filterRow = $entity->getFilterRow();
            
            $description = '';
            $n = $filterRow->count();
            for ($i = 0; $i < $n; $i++) {
                if (0 < $i) {
                    $description .= ', ';
                    if (($i + 1) === $n) {
                        $description .= 'or ';
                    }
                }
                $description .= '(' . $filterRow[$i]->getDescription() . ')';
            }
            
            $entity->setDescription($description);
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
        $form = $this->createForm('mesd_filterbundle_filter', $entity, array(
            'action' => $this->generateUrl('filter_create'),
            'method' => 'POST',
            'om'     => 'default',
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
            'form'        => $editForm->createView(),
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
        $form = $this->createForm('mesd_filterbundle_filter', $entity, array(
            'action' => $this->generateUrl('filter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'om'     => 'default',
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
            'form'        => $editForm->createView(),
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
            $filterCells = $entityManager->getRepository('MesdFilterBundle:FilterCell')->findByFilterAssociation($id);
            $cells = array();
            foreach ($filterCells as $filterCell) {
                $cells[] = array(
                    'solvent'     => $filterCell->getSolvent(),
                    'description' => $filterCell->getDescription(),
                );
            }
            $name = $filterAssociation->getName();
            $code = str_replace(' ', '-', strtolower($name));
            $trailEntity = $filterAssociation->getTrailEntity();
            $entities = $entityManager->getRepository($trailEntity->getName())->findAll();
            $values = array();
            foreach ($entities as $entity) {
                $values[] = array(
                    'id'   => $entity->getId(),
                    'name' => $entity->__toString(),
                );
            }
            $entityDataUrl = $this->generateUrl(
                'MesdUserBundle_filter_entity_data',
                array(
                    'id' => $trailEntity->getId(),
                )
            );
            $data['associations'][$code] = array(
                'cells'         => $cells,
                'code'          => $code,
                'associationId' => $id,
                'name'          => $name,
                'trailEntityId' => $trailEntity->getId(),
                'values'        => $values,
                'entityDataUrl' => $entityDataUrl,
            );
        }

        $response = new JsonResponse();

        $response->setContent(
            json_encode($data)
        );

        return $response;
    }
    
    public function entityDataAction(Request $request, $id)
    {
        $searchTerm = $request->query->get('searchTerm');

        $entityManager = $this->getDoctrine()->getManager();
        $filterEntity = $entityManager->getRepository('MesdFilterBundle:FilterEntity')->find($id);
        $repository = $entityManager->getRepository($filterEntity->getName());
        $entities = $repository->findAll();
        
        $data = array();
        $data['items'] = array();
        $data['total_count'] = count($entities);
        foreach ($entities as $entity) {
            $text = $entity->__toString();
            if (($searchTerm === '') || (strpos(strtolower($text), strtolower($searchTerm)) !== false)) {
                $data['items'][] = array('id' => $entity->getId(), 'text' => $text);
            }
        }

        $response = new JsonResponse();

        $response->setContent(
            json_encode($data)
        );

        return $response;
    }
}
