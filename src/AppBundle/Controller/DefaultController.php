<?php

namespace AppBundle\Controller;

use CollectionBundle\Entity\Collection;
use MessageBundle\Entity\MailingList;
use MessageBundle\Entity\Message;
use MessageBundle\Form\MailingListType;
use MessageBundle\Form\MessageType;
use Psr\Log\LoggerInterface;
use OrderBundle\Entity\Devis;
use OrderBundle\Entity\DevisItem;
use OrderBundle\Entity\OrderInfo;
use OrderBundle\Form\CommandeItemEditTypeClient1;
use OrderBundle\Form\DevisType;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductVariation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_page_nolocale", defaults={"_locale":"%locale%"})
     * @Route("/{_locale}/", name="homepage", requirements={"_locale" = "%locales%"})
     */
    public function indexAction(Request $request)
    {
        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/index.html.twig', array(
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/products", name="index_products_page")
     */
    public function productAction(Request $request, LoggerInterface $logger)
    {
        $test = $request->query->get('collection');

        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            //~ Is AJAX Request
            $serializer = $this->get('jms_serializer');
            $offset = $request->getContent();
            // $logger->error($offset);
            $em = $this->getDoctrine()
                ->getEntityManager();

            $queryBuilder = $em->getRepository(Product::class)->createQueryBuilder('p');
            $queryBuilder->where('p.enabled = true')->orderBy('p.id', 'DESC');
            if($request->query->getAlnum('category')) {
                if($request->query->getAlnum('category') == 'StreetCouture') {
                    $queryBuilder->andWhere('p.category = :sweatshirt OR p.category = :sweatpants OR p.category = :couture')
                        ->setParameter('couture', 'StreeCouture')
                        ->setParameter('sweatshirt', 'SweatShirt')
                        ->setParameter('sweatpants', 'SweatPants');
                } else {
                    $queryBuilder->andWhere('p.category = :category')
                        ->setParameter('category', $request->query->getAlnum('category'));
                }
            }
            if($request->query->getAlnum('collection')) {
                $queryBuilder->leftJoin('p.collection', 'collection')
                    ->addSelect('collection')
                    ->andWhere('collection.name = :collection')
                    ->setParameter('collection', $test);
            }
            $objects = $queryBuilder->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults(6)
                ->getResult();

            return new JsonResponse($serializer->serialize($objects, 'json'));
        }

        $queryBuilder = $em->getRepository(Product::class)->createQueryBuilder('p');
        $queryBuilder->leftJoin('p.variations', 'variations')
            ->addSelect('variations');
        $queryBuilder->where('p.enabled = true');
        if($request->query->getAlnum('category')) {
            if($request->query->getAlnum('category') == 'StreetCouture') {
                $queryBuilder->andWhere('p.category = :sweatshirt OR p.category = :sweatpants OR p.category = :couture')
                    ->setParameter('couture', 'StreeCouture')
                    ->setParameter('sweatshirt', 'SweatShirt')
                    ->setParameter('sweatpants', 'SweatPants');
            } else {
                $queryBuilder->andWhere('p.category = :category')
                    ->setParameter('category', $request->query->getAlnum('category'));
            }
        }
        if($request->query->getAlnum('collection')) {
            $queryBuilder->leftJoin('p.collection', 'collection')
                ->addSelect('collection')
                ->andWhere('collection.name = :collection')
                ->setParameter('collection', $test);
        }
        $queryBuilder->orderBy('p.id', 'DESC');
        $query = $queryBuilder->getQuery();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }

        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/list_products.html.twig', array(
            'products' => $result,
            'mailing_form' => $mailing_form->createView(),
            'cartLogo' => $cartLogo,
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/products/{id}-{v_id}", name="product_details_page")
     */
    public function productDetailsAction(Request $request, $id, $v_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $variation = $em->getRepository(ProductVariation::class)->find($v_id);
        $variations = $em->getRepository(ProductVariation::class)->findBy(['product' => $product]);

        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }

        $devisItem = new DevisItem();
        $cart_form = $this->get('form.factory')->create(CommandeItemEditTypeClient1::class, $devisItem);
        if($request->isMethod('POST') && $cart_form->handleRequest($request)->isValid()) {

            $devisItem->setProduct($product);
            if($product->isPromoEnabled()) {
                $devisItem->setPromo($product->getPromoMontant());
            }
            $session = $this->get('session');

            if ($session->has('cartElements')) {
                //add to existing commande
                $devisJson = $session->get('cartElements');
                $devis = $serializer->deserialize($devisJson, Devis::class, 'json');
                $database_devis = $this->getDoctrine()->getManager()->getRepository(Devis::class)->find($devis->getId());
                $database_devis->addItem($devisItem);
                $devisItem->setDevis($database_devis);
                $em->persist($database_devis);
                $em->flush();
                $jsonDevis = $serializer->serialize($database_devis, 'json');
                //die(var_dump($jsonCommande));
                $session->set('cartElements', $jsonDevis);
            } else {
                $devis = new Devis();
                $devis->setEnabled(false);
                $devis->setArchived(false);
                $devis->setState('VIDE');
                $devis->setSaleDate(new \DateTime());
                $em->persist($devis);
                $em->flush();
                $devis->addItem($devisItem);
                $devisItem->setDevis($devis);
                $em->persist($devisItem);
                //die(var_dump($devisItem));
                $em->flush();
                $jsonDevis = $serializer->serialize($devis, 'json');
                $session->set('cartElements', $jsonDevis);
            }
            return $this->redirectToRoute('index_products_page');
        }

        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/detail_product.html.twig', array(
            'p' => $product,
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'cart_form' => $cart_form->createView(),
            'collections' => $collections,
            'variation' => $variation,
            'variations' => $variations,
        ));
    }

    /**
     * @Route("/add-to-cart/{id}-{v_id}", name="add_to_cart_page")
     */
    public function addCartAction(Request $request, $id, $v_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $variation = $em->getRepository(ProductVariation::class)->find($v_id);
        $serializer = $this->get('jms_serializer');

        $devisItem = new DevisItem();
        if($request->isMethod('POST')) {
            $devisItem->setProduct($product);
            if($product->isPromoEnabled()) {
                $devisItem->setPromo($product->getPromoMontant());
            }
            $devisItem->setVariation($variation);
            $quantity = $request->request->get('quantity');
            if($quantity != null) {
                $devisItem->setQuantity($quantity);
            }
            else {
                $devisItem->setQuantity(1);
            }

            $size = $request->request->get('size-select');
            if(($size != null) && ($size == 'S' || $size == 'M' || $size == 'L' || $size == 'XL' || $size == 'XXL' || $size == 'XXXL' ||
                $size == '29' || $size == '30' || $size == '31' || $size == '32' || $size == '33' || $size == '34' || $size == '35' || $size == '36' || $size == '38' ||
                $size == '40' || $size == '41' || $size == '42' || $size == '43' || $size == '44' || $size == '45')) {
                $devisItem->setSize($size);
            }

            $session = $this->get('session');

            if ($session->has('cartElements')) {
                //add to existing commande
                $devisJson = $session->get('cartElements');
                $devis = $serializer->deserialize($devisJson, Devis::class, 'json');
                $database_devis = $this->getDoctrine()->getManager()->getRepository(Devis::class)->find($devis->getId());
                $database_devis->addItem($devisItem);
                $devisItem->setDevis($database_devis);
                $em->persist($database_devis);
                $em->flush();
                $jsonDevis = $serializer->serialize($database_devis, 'json');
                //die(var_dump($jsonCommande));
                $session->set('cartElements', $jsonDevis);
            }
            else{
                $devis = new Devis();
                $devis->setEnabled(false);
                $devis->setArchived(false);
                $devis->setState('VIDE');
                $devis->setSaleDate(new \DateTime());
                $em->persist($devis);
                $em->flush();
                $devis->addItem($devisItem);
                $devisItem->setDevis($devis);
                $em->persist($devisItem);
                //die(var_dump($devisItem));
                $em->flush();
                $jsonDevis = $serializer->serialize($devis, 'json');
                $session->set('cartElements', $jsonDevis);
            }

            return $this->redirectToRoute('index_products_page');
        }
        return $this->redirectToRoute('index_products_page');
    }

    /**
     * @Route("/cart-page", name="cart_page")
     */
    public function cartAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        if ($session->has('cartElements')) {
            $devisJson = $session->get('cartElements');
            $devis = $serializer->deserialize($devisJson, Devis::class, 'json');
            $em = $this->getDoctrine()->getManager();
            $database_commande = $em->getRepository(Devis::class)->find($devis->getId());
            $data = $devis->getItems();
            $commande_form = $this->get('form.factory')->create(DevisType::class, $database_commande);
            if ($request->isMethod('POST') && $commande_form->handleRequest($request)->isValid()) {
                //die(dump($data));
                $em->persist($database_commande);
                $em->flush();
                $jsonDevis = $serializer->serialize($database_commande, 'json');
                $session->set('cartElements', $jsonDevis);
                return $this->redirectToRoute('checkout_page');
            }
        }
        else {
            return $this->redirectToRoute('homepage');
        }

        /*$session->clear();
        die('cleared');*/

        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }

        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        //dump($data);
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/cart-page.html.twig', array(
            'commande_form' => $commande_form->createView(),
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'items1' => $data,
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/delete-cart-item-{id}", name="delete_cart_item")
     */
    public function deleteCartItemAction($id) {

        $em = $this->getDoctrine()->getManager();
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');

        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');

            $database_commande = $this->getDoctrine()->getManager()->getRepository(Devis::class)->find($commande->getId());
            $item = $this->getDoctrine()->getManager()->getRepository(DevisItem::class)->find($id);
            $database_commande->removeItem($item);

            $em->remove($item);
            $em->flush();

            $jsonCommande = $serializer->serialize($database_commande, 'json');
            $session->set('cartElements', $jsonCommande);
        }
        return $this->redirectToRoute('cart_page');
    }

    /**
     * @Route("/checkout-page", name="checkout_page")
     */
    public function checkoutAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
            // dump($commande);
            $data = $commande->getItems();
            //dump($data);

            if ($request->isMethod('POST')) {
                $em = $this->getDoctrine()->getManager();
                $database_commande = $this->getDoctrine()->getManager()->getRepository(Devis::class)->find($commande->getId());
                $database_commande->setEnabled(true);

                $data = $database_commande->getItems();
                foreach ($data as $devis_item) {
                    $devis_item->setPrice($devis_item->getProduct()->getPrice());
                    $devis_item->setPriceDollar($devis_item->getProduct()->getPriceDollar());
                }

                $em->flush();
                foreach ($data as $devis_item) {
                    $variation = $em->getRepository(ProductVariation::class)->find(($devis_item->getVariation()->getId()));
                    switch ($devis_item->getSize()) {
                        case 'S':
                            $val = $variation->getS();
                            $val = $val - 1;
                            $variation->setS(strval($val));
                            break;
                        case 'M':
                            $val = $variation->getM();
                            $val = $val - 1;
                            $variation->setM($val);
                            break;
                        case 'L':
                            $val = $variation->getL();
                            $val = $val - 1;
                            $variation->setL($val);
                            break;
                        case 'XL':
                            $val = $variation->getXL();
                            $val = $val - 1;
                            $variation->setXL($val);
                            break;
                        case 'XXL':
                            $val = $variation->getXXL();
                            $val = $val - 1;
                            $variation->setXXL($val);
                            break;
                        case 'XXXL':
                            $val = $variation->getXXXL();
                            $val = $val - 1;
                            $variation->setXXXL($val);
                            break;
                        case '29':
                            $val = $variation->getSizeJean29();
                            $val = $val - 1;
                            $variation->setSizeJean29($val);
                            break;
                        case '30':
                            $val = $variation->getSizeJean30();
                            $val = $val - 1;
                            $variation->setSizeJean30($val);
                            break;
                        case '31':
                            $val = $variation->getSizeJean31();
                            $val = $val - 1;
                            $variation->setSizeJean31($val);
                            break;
                        case '32':
                            $val = $variation->getSizeJean32();
                            $val = $val - 1;
                            $variation->setSizeJean32($val);
                            break;
                        case '33':
                            $val = $variation->getSizeJean33();
                            $val = $val - 1;
                            $variation->setSizeJean33($val);
                            break;
                        case '34':
                            $val = $variation->getSizeJean34();
                            $val = $val - 1;
                            $variation->setSizeJean34($val);
                            break;
                        case '35':
                            $val = $variation->getSizeJean35();
                            $val = $val - 1;
                            $variation->setSizeJean35($val);
                            break;
                        case '36':
                            $val = $variation->getSizeJean36();
                            $val = $val - 1;
                            $variation->setSizeJean36($val);
                            break;
                        case '38':
                            $val = $variation->getSizeJean38();
                            $val = $val - 1;
                            $variation->setSizeJean38($val);
                            break;
                        case '40':
                            $val = $variation->getSizeJean40();
                            $val = $val - 1;
                            $variation->setSizeJean40($val);
                            break;
                        case '41':
                            $val = $variation->getSizeMoc41();
                            $val = $val - 1;
                            $variation->setSizeMoc41($val);
                            break;
                        case '42':
                            $val = $variation->getSizeMoc42();
                            $val = $val - 1;
                            $variation->setSizeMoc42($val);
                            break;
                        case '43':
                            $val = $variation->getSizeMoc43();
                            $val = $val - 1;
                            $variation->setSizeMoc43($val);
                            break;
                        case '44':
                            $val = $variation->getSizeMoc44();
                            $val = $val - 1;
                            $variation->setSizeMoc44($val);
                            break;
                        case '45':
                            $val = $variation->getSizeMoc45();
                            $val = $val - 1;
                            $variation->setSizeMoc45($val);
                            break;
                    }
                    $em->flush();
                }
                $devis_item->setVariation($variation);
                //die(dump($devis_item));
                $em->flush();
                $session->clear();
                return $this->redirectToRoute('after_checkout');
            }

        }
        else {
            return $this->redirectToRoute('homepage');
        }

        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/validate_devis.html.twig', array(
            // 'personalinfo_form' => $personalinfo_form->createView(),
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'items1' => $data,
            'commande' => $commande,
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function contactAction(Request $request)
    {
        $message = new Message();
        $message->setEnabled(true);
        $form = $this->get('form.factory')->create(MessageType::class, $message);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            return $this->redirect($request->getUri());
        }

        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }

        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        return $this->render('default/contact.html.twig', array(
            'contact_form' => $form->createView(),
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/checkout", name="after_checkout")
     */
    public function afterCheckoutAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }
        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);

        return $this->render('default/after_checkout.html.twig', array(
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/api/personal-info", name="personal_info_endpoint", methods={"POST"})),
     */
    public function setPersonalInfoAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $database_commande = $this->getDoctrine()->getManager()->getRepository(Devis::class)->find($commande->getId());

            $personal_info = new OrderInfo();
            $data_array = explode('&', $request->getContent());
            $personal_info_json = [];
            foreach ($data_array as $value) {
                $element = explode('=', $value);
                $personal_info_json[$element[0]] = urldecode(html_entity_decode($element[1]));
            }
            $personal_info->setCustomerFirstName($personal_info_json['CustFirstName']);
            $personal_info->setCustomerLastName($personal_info_json['CustLastName']);
            $personal_info->setCustomerEmail($personal_info_json['EMAIL']);
            $personal_info->setCustomerAddress($personal_info_json['CustAddress']);
            $personal_info->setCustomerCity($personal_info_json['CustCity']);
            $personal_info->setCustomerPhone($personal_info_json['CustTel']);
            $personal_info->setPays($personal_info_json['CustCountry']);
            $personal_info->setPostalCode($personal_info_json['CustZIP']);
            $personal_info->setPaymentMethod('GPG');
            if(isset($personal_info_json['promo'])) {
                $personal_info->setPromo($personal_info_json['promo']);
            }
            $personal_info->setEnabled(true);

            $database_commande->setOrderInfo($personal_info);
            // $database_commande->setEnabled(true);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new JsonResponse('command saved');
        }
        return new JsonResponse('no data');
    }

    /**
     * @Route("/api/validate-payment", name="validate-payment")
     */
    public function validatePaymentAction(Request $request)
    {
        $session = $this->get('session');
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        // $logger = $this->get('logger');
        /*$logger->error($request->request->get('TransStatus'));
        $logger->error($request->get('TransStatus'));
        $logger->error($request->get('TransStatus') == '00');*/
        if ($request->get('TransStatus') == '00') {
            // $logger->error($request->get('PAYID'));
            $id_command = $request->get('PAYID');
            $database_commande = $em->getRepository(Devis::class)->find($id_command);
            $data = $database_commande->getItems();
            $database_commande->setEnabled(true);
            $em->flush();
            // $logger->error($database_commande);
            foreach ($data as $devis_item) {
                $variation = $em->getRepository(ProductVariation::class)->find(($devis_item->getVariation()->getId()));
                switch ($devis_item->getSize()) {
                    case 'S':
                        $val = $variation->getS();
                        $val = $val - 1;
                        $variation->setS(strval($val));
                        break;
                    case 'M':
                        $val = $variation->getM();
                        $val = $val - 1;
                        $variation->setM($val);
                        break;
                    case 'L':
                        $val = $variation->getL();
                        $val = $val - 1;
                        $variation->setL($val);
                        break;
                    case 'XL':
                        $val = $variation->getXL();
                        $val = $val - 1;
                        $variation->setXL($val);
                        break;
                    case 'XXL':
                        $val = $variation->getXXL();
                        $val = $val - 1;
                        $variation->setXXL($val);
                        break;
                    case 'XXXL':
                        $val = $variation->getXXXL();
                        $val = $val - 1;
                        $variation->setXXXL($val);
                        break;
                    case '29':
                        $val = $variation->getSizeJean29();
                        $val = $val - 1;
                        $variation->setSizeJean29($val);
                        break;
                    case '30':
                        $val = $variation->getSizeJean30();
                        $val = $val - 1;
                        $variation->setSizeJean30($val);
                        break;
                    case '31':
                        $val = $variation->getSizeJean31();
                        $val = $val - 1;
                        $variation->setSizeJean31($val);
                        break;
                    case '32':
                        $val = $variation->getSizeJean32();
                        $val = $val - 1;
                        $variation->setSizeJean32($val);
                        break;
                    case '33':
                        $val = $variation->getSizeJean33();
                        $val = $val - 1;
                        $variation->setSizeJean33($val);
                        break;
                    case '34':
                        $val = $variation->getSizeJean34();
                        $val = $val - 1;
                        $variation->setSizeJean34($val);
                        break;
                    case '35':
                        $val = $variation->getSizeJean35();
                        $val = $val - 1;
                        $variation->setSizeJean35($val);
                        break;
                    case '36':
                        $val = $variation->getSizeJean36();
                        $val = $val - 1;
                        $variation->setSizeJean36($val);
                        break;
                    case '38':
                        $val = $variation->getSizeJean38();
                        $val = $val - 1;
                        $variation->setSizeJean38($val);
                        break;
                    case '40':
                        $val = $variation->getSizeJean40();
                        $val = $val - 1;
                        $variation->setSizeJean40($val);
                        break;
                    case '41':
                        $val = $variation->getSizeMoc41();
                        $val = $val - 1;
                        $variation->setSizeMoc41($val);
                        break;
                    case '42':
                        $val = $variation->getSizeMoc42();
                        $val = $val - 1;
                        $variation->setSizeMoc42($val);
                        break;
                    case '43':
                        $val = $variation->getSizeMoc43();
                        $val = $val - 1;
                        $variation->setSizeMoc43($val);
                        break;
                    case '44':
                        $val = $variation->getSizeMoc44();
                        $val = $val - 1;
                        $variation->setSizeMoc44($val);
                        break;
                    case '45':
                        $val = $variation->getSizeMoc45();
                        $val = $val - 1;
                        $variation->setSizeMoc45($val);
                        break;
                }
                $em->flush();
            }
            $em->flush();
            $session->clear();
            return new JsonResponse('command saved');
        }
        return new JsonResponse('Payment not valid');
    }

    /**
     * @Route("/about-us", name="about-us")
     */
    public function aboutUs(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/about-us.html.twig', array(
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/conditions-generales", name="conditions_gen")
     */
    public function conditions_gen(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/general_conditions.html.twig', array(
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/conditions-livraisons", name="conditions_liv")
     */
    public function conditions_liv(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $session = $this->get('session');
        $cartLogo = 0;
        if ($session->has('cartElements')) {
            $commandeJson = $session->get('cartElements');
            $commande = $serializer->deserialize($commandeJson, Devis::class, 'json');
            $cartLogo = count($commande->getItems());
        }
        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        $mailing = new MailingList();
        $mailing_form = $this->get('form.factory')->create(MailingListType::class, $mailing);
        if ($request->isMethod('POST') && $mailing_form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mailing);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/conditions_livraisons.html.twig', array(
            'cartLogo' => $cartLogo,
            'mailing_form' => $mailing_form->createView(),
            'collections' => $collections,
        ));
    }
}
