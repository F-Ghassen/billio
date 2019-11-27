<?php

namespace OrderBundle\Controller;

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
        $order = $em->getRepository(Devis::class)->findBy(['id' => $id, 'enabed' => true]);
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

    /**
     * @Route("/admin/devis/print/{id}", name="print_devis_page")
     */
    public function printDevisAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);
        return $this->render('admin/devis/print_devis.html.twig', [
            'c' => $order
        ]);
    }

    /**
     * @Route("/admin/devis/state/{id}/{state}", name="state_devis_page")
     */
    public function updateState($id, $state)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);
        $order->setState($state);
        if($state == 'Canceled') {
            foreach ($order->getItems() as $order_item) {
                switch ($order_item->getSize()) {
                        case 'S':
                            $val = $order_item->getVariation()->getS();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setS(strval($val));
                            break;
                        case 'M':
                            $val = $order_item->getVariation()->getM();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setM($val);
                            break;
                        case 'L':
                            $val = $order_item->getVariation()->getL();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setL($val);
                            break;
                        case 'XL':
                            $val = $order_item->getVariation()->getXL();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setXL($val);
                            break;
                        case 'XXL':
                            $val = $order_item->getVariation()->getXXL();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setXXL($val);
                            break;
                        case '29':
                            $val = $order_item->getVariation()->getSizeJean29();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean29($val);
                            break;
                        case '30':
                            $val = $order_item->getVariation()->getSizeJean30();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean30($val);
                            break;
                        case '31':
                            $val = $order_item->getVariation()->getSizeJean31();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean31($val);
                            break;
                        case '32':
                            $val = $order_item->getVariation()->getSizeJean32();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean32($val);
                            break;
                        case '33':
                            $val = $order_item->getVariation()->getSizeJean33();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean33($val);
                            break;
                        case '34':
                            $val = $order_item->getVariation()->getSizeJean34();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean34($val);
                            break;
                        case '35':
                            $val = $order_item->getVariation()->getSizeJean35();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean35($val);
                            break;
                        case '36':
                            $val = $order_item->getVariation()->getSizeJean36();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean36($val);
                            break;
                        case '38':
                            $val = $order_item->getVariation()->getSizeJean38();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean38($val);
                            break;
                        case '40':
                            $val = $order_item->getVariation()->getSizeMoc40();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc40($val);
                            break;
                        case '41':
                            $val = $order_item->getVariation()->getSizeMoc41();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc41($val);
                            break;
                        case '42':
                            $val = $order_item->getVariation()->getSizeMoc42();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc42($val);
                            break;
                        case '43':
                            $val = $order_item->getVariation()->getSizeMoc43();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc43($val);
                            break;
                        case '44':
                            $val = $order_item->getVariation()->getSizeMoc44();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc44($val);
                            break;
                        case '45':
                            $val = $order_item->getVariation()->getSizeMoc45();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeMoc45($val);
                            break;
                    }
            }
        }
        $em->flush();
        return $this->redirectToRoute('list_devis_page');
    }
}
