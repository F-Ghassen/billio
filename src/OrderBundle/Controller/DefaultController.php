<?php

namespace OrderBundle\Controller;

use MessageBundle\Entity\PhoneNumber;
use MessageBundle\Form\PhoneType;
use OrderBundle\Entity\Devis;
use OrderBundle\Entity\DevisItem;
use OrderBundle\Entity\OrderInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/devis", name="list_devis_page")
     */
    public function listdevisAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository(Devis::class)->createQueryBuilder('d');
        $queryBuilder->where('d.enabled = true and d.archived = false');

        if($request->query->getAlnum('search')) {
            $queryBuilder
                ->leftJoin('d.orderInfo', 'info')
                ->addSelect('info')
                ->andWhere('d.id like :search')
                ->orWhere('info.customerFirstName like :search')
                ->orWhere('info.customerLastName like :search')
                ->orWhere('info.customerPhone like :search')
                ->orWhere('info.customerEmail like :search')
                ->orWhere('info.customerCity like :search')
                ->orWhere('info.customerAddress like :search')
                ->orWhere('info.postalCode like :search')
                ->orWhere('info.promo like :search')
                ->orWhere('info.pays like :search')
                ->setParameter('search', $request->query->getAlnum('search'));
        }

        if($request->query->getAlnum('state')) {
            if($request->query->getAlnum('state') == 'Livr') {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->like('d.state', ':state'))
                    ->setParameter('state', 'Livré');
            } else {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->like('d.state', ':state'))
                    ->setParameter('state', $request->query->getAlnum('state'));
            }
        }

        $queryBuilder->orderBy('d.id', 'DESC');

        $query = $queryBuilder->getQuery();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            24
        );
        // $commandes = $this->getDoctrine()->getManager()->getRepository(Devis::class)
            // ->findBy(array('enabled' => true), array('id' => 'desc'));
        return $this->render('admin/devis/list.html.twig', array(
            'commandes' => $result,
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
        $order->setArchived(true);
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
    public function updateState($id, $state, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);

        if ($state == 'Reported') {
            $data = $request->getContent();
            $order->setDeliveryDate(preg_split('/=/', $data)[1]);
        }
        if ($state == 'Livré') {
            $order->setDeliveryDate(date('Y-m-d h:i'));
        }
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
                        case 'XXXL':
                            $val = $order_item->getVariation()->getXXXL();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setXXXL($val);
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
                            $val = $order_item->getVariation()->getSizeJean40();
                            $val = $val + $order_item->getQuantity();
                            $order_item->getVariation()->setSizeJean40($val);
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

    /**
     * @Route("/admin/devis/remarque/{id}", name="remarque_devis_page")
     */
    public function updateRq($id, Request $request)
    {
        $remarque = $request->request->get('remarque');
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Devis::class)->find($id);
        if($remarque == '') {
            $order->setRemarque(null);
        } else {
            $order->setRemarque($remarque);
        }

        $em->flush();
        return $this->redirectToRoute('list_devis_page');
    }

    /**
     * @Route("/admin/devis/type/{id}/{type}", name="type_devis_item_page", options = { "expose" = true })
     */
    public function updateDevisItemType($id, $type, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order_item = $em->getRepository(DevisItem::class)->find($id);
        $order_item->setType($type);
        $em->flush();
        return $this->redirectToRoute('list_devis_page');
    }

    /**
     * @Route("/admin/phone-list", name="list_phones_page")
     */
    public function mailingAction()
    {
        $originals = $this->getDoctrine()->getManager()->getRepository(PhoneNumber::class)->findBy(['enabled'=> true]);
        $phones = $this->getDoctrine()->getManager()->getRepository(OrderInfo::class)->findPhones();
        return $this->render('admin/messages/phones.html.twig', array(
            'phones' => $phones,
            'originals' => $originals,
        ));
    }

    /**
     * @Route("/admin/phone-list/add", name="add_phone_page")
     */
    public function addAction(Request $request)
    {
        $phone = new PhoneNumber();
        $form = $this->get('form.factory')->create(PhoneType::class, $phone);
        $phone->setEnabled(true);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();
            return $this->redirectToRoute('list_phones_page');
        }
        return $this->render('admin/messages/add-phone.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/phone-list/import", name="import_phone_page")
     */
    public function importAction(Request $request)
    {
        $form = $this->get('form.factory')->create();
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $file = $request->files->get('import');

            $row = 1;
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    $row++;
                    for ($c=0; $c < $num; $c++) {
                        // dump($data[$c]);
                        $phone = new PhoneNumber();
                        $phone->setPhone($data[$c]);
                        $phone->setEnabled(true);
                        $em->persist($phone);
                    }
                }
                fclose($handle);
            }
            $em->flush();
            return $this->redirectToRoute('list_phones_page');
        }
        return $this->render('admin/messages/import-phone.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/phone-list/delete/{id}", name="delete_phone_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository(PhoneNumber::class)->find($id);
        $phone->setEnabled(false);
        $em->flush();
        return $this->redirectToRoute('list_phones_page');
    }

    /**
     * @Route("/admin/personal-info/delete/{phone}", name="delete_personalinfo_page")
     */
    public function deletePersonalInfoAction($phone)
    {
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository(OrderInfo::class)->findBy(['customerPhone' => $phone]);
        foreach ($info as $i){
            $i->setEnabled(false);
        }
        $em->flush();
        return $this->redirectToRoute('list_phones_page');
    }
}
