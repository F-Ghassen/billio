<?php

namespace OrderBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use OrderBundle\Entity\Commande;
use OrderBundle\Entity\Devis;
use OrderBundle\Form\FullCommande;
use PromoCodeBundle\Entity\PromoCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/devis", name="list_devis_page")
     */
    public function listdevisAction()
    {
        $commandes = $this->getDoctrine()->getManager()->getRepository(Devis::class)
            ->findBy(array('enabled' => true), array('id' => 'desc'));
        return $this->render('admin/devis/list.html.twig', array(
            'commandes' => $commandes,
        ));
    }

    /**
     * @Route("/admin/devis/enable/{id}", name="enable_devis_page")
     */
    public function enabledevisAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);
        if($order->isEnabled())
        {
            $order->setEnabled(false);
        }
        $em->flush();
        return $this->redirectToRoute('list_devis_page');
    }

    /**
     * @Route("/admin/devis/archive/{id}", name="archive_devis_page")
     */
    public function archiveDevisAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);
        if($order->isEnabled())
        {
            $order->setArchived(false);
        } else {
            $order->setArchived(true);
        }
        $em->flush();
        return $this->redirectToRoute('list_devis_page');
    }
}
