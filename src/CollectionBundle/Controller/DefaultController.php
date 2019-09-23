<?php

namespace CollectionBundle\Controller;

use CollectionBundle\Entity\Collection;
use CollectionBundle\Form\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/collections", name="list_collections_page")
     */
    public function indexAction()
    {
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findAll();
        return $this->render('admin/collections/list.html.twig', array(
            'collections' => $collections
        ));
    }

    /**
     * @Route("/admin/collections/add", name="add_collection_page")
     */
    public function addAction(Request $request)
    {
        $collection = new Collection();
        $form = $this->get('form.factory')->create(CollectionType::class, $collection);
        $collection->setEnabled(true);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();
            return $this->redirectToRoute('list_collections_page');
        }
        return $this->render('admin/collections/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/collections/delete/{id}", name="delete_collections_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository(Collection::class)->find($id);
        $em->remove($collection);
        $em->flush();
        return $this->redirectToRoute('list_collections_page');
    }

    /**
     * @Route("/admin/collections/enable/{id}", name="enable_collections_page")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository(Collection::class)->find($id);
        if($collection->isEnabled()) {
            $collection->setEnabled(false);
        } else {
            $collection->setEnabled(true);
        }
        $em->flush();
        return $this->redirectToRoute('list_collections_page');
    }
}
