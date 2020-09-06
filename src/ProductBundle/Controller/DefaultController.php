<?php

namespace ProductBundle\Controller;

use CollectionBundle\Entity\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductVariation;
use ProductBundle\Form\ProductType;
use ProductBundle\Form\ProductVariationEditType;
use ProductBundle\Form\ProductVariationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/products", name="list_products_page")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        foreach ($products as $product) {
            foreach ($product->getVariations() as $variation) {
                if ($product->getCategory() == 'Jeans') {
                    if($variation->getSizeJean29() == null)
                        $variation->setSizeJean29(0);
                    if($variation->getSizeJean30() == null)
                        $variation->setSizeJean30(0);
                    if($variation->getSizeJean31() == null)
                        $variation->setSizeJean31(0);
                    if($variation->getSizeJean32() == null)
                        $variation->setSizeJean32(0);
                    if($variation->getSizeJean33() == null)
                        $variation->setSizeJean33(0);
                    if($variation->getSizeJean34() == null)
                        $variation->setSizeJean34(0);
                    if($variation->getSizeJean35() == null)
                        $variation->setSizeJean35(0);
                    if($variation->getSizeJean36() == null)
                        $variation->setSizeJean36(0);
                    if($variation->getSizeJean38() == null)
                        $variation->setSizeJean38(0);
                    if($variation->getSizeJean40() == null)
                        $variation->setSizeJean40(0);
                } else if ($product->getCategory() == 'Mocassin') {
                    if($variation->getSizeMoc40() == null)
                        $variation->setSizeMoc40(0);
                    if($variation->getSizeMoc41() == null)
                        $variation->setSizeMoc41(0);
                    if($variation->getSizeMoc42() == null)
                        $variation->setSizeMoc42(0);
                    if($variation->getSizeMoc43() == null)
                        $variation->setSizeMoc43(0);
                    if($variation->getSizeMoc44() == null)
                        $variation->setSizeMoc44(0);
                    if($variation->getSizeMoc45() == null)
                        $variation->setSizeMoc45(0);
                } else {
                    if($variation->getS() == null)
                        $variation->setS(0);
                    if($variation->getM() == null)
                        $variation->setM(0);
                    if($variation->getL() == null)
                        $variation->setL(0);
                    if($variation->getXL() == null)
                        $variation->setXL(0);
                    if($variation->getXXL() == null)
                        $variation->setXXL(0);
                    if($variation->getXXXL() == null)
                        $variation->setXXXL(0);
                }
                $em->flush();
            }
        }

        $queryBuilder = $em->getRepository(Product::class)->createQueryBuilder('p');
        $queryBuilder->where('p.deleted = false');

        if($request->query->getAlnum('category')) {
            if($request->query->getAlnum('category') == 'StreetCouture') {
                $queryBuilder->andWhere('p.category = :sweatshirt OR p.category = :sweatpants OR p.category = :veste')
                    ->setParameter('sweatshirt', 'SweatShirt')
                    ->setParameter('sweatpants', 'SweatPants')
                    ->setParameter('veste', 'Veste');
            } else {
                $queryBuilder->andWhere('p.category = :category')
                    ->setParameter('category', $request->query->getAlnum('category'));
            }
        }

        if($request->query->getAlnum('collection')) {
            $queryBuilder->leftJoin('p.collection', 'collection')
                ->addSelect('collection')
                ->andWhere('collection.name = :collection')
                ->setParameter('collection', $request->query->getAlnum('collection'));
        }

        $queryBuilder->orderBy('p.id', 'DESC');
        $query = $queryBuilder->getQuery();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            25
        );

        $collections = $this->getDoctrine()->getManager()->getRepository(Collection::class)->findBy(['enabled' => true]);
        // $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(array(), array('id' => 'DESC'));
        return $this->render('admin/products/list.html.twig', array(
            'products' => $result,
            'collections' => $collections,
        ));
    }

    /**
     * @Route("/admin/export", name="export_page")
     */
    public function exportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository(Product::class)->createQueryBuilder('p');
        $queryBuilder->where('p.deleted = false');

        if($request->query->getAlnum('category')) {
            if($request->query->getAlnum('category') == 'StreetCouture') {
                $queryBuilder->andWhere('p.category = :sweatshirt OR p.category = :sweatpants OR p.category = :veste')
                    ->setParameter('sweatshirt', 'SweatShirt')
                    ->setParameter('sweatpants', 'SweatPants')
                    ->setParameter('veste', 'Veste');
            } else {
                $queryBuilder->andWhere('p.category = :category')
                    ->setParameter('category', $request->query->getAlnum('category'));
            }
        }

        if($request->query->getAlnum('collection')) {
            $queryBuilder->leftJoin('p.collection', 'collection')
                ->addSelect('collection')
                ->andWhere('collection.name = :collection')
                ->setParameter('collection', $request->query->getAlnum('collection'));
        }
        $queryBuilder->orderBy('p.id', 'DESC');
        $export_data = $queryBuilder->getQuery()->getResult();

        $fileName = "export_stock_" . date("d_m_Y") . ".csv";
        $writer = $this->container->get('egyg33k.csv.writer');
        $csv = $writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['id', 'name', 'color', 'Stock S', 'Stock M', 'Stock L', 'Stock XL', 'Stock XXL', 'Stock XXXL',
            'Stock 29', 'Stock 30', 'Stock 31', 'Stock 32', 'Stock 33', 'Stock 34', 'Stock 35', 'Stock 36', 'Stock 38', 'Stock 40'
        ]);

        foreach ($export_data as $p) {
            $json = array();
            foreach ($p->getVariations() as $v) {
                $json['id'] = $p->getId().' - '.$v->getId();
                $json['name'] = $p->getName();
                $json['color'] = $v->getColor();
                $json['s'] = $v->getS();
                $json['m'] = $v->getM();
                $json['l'] = $v->getL();
                $json['xl'] = $v->getXL();
                $json['xxl'] = $v->getXXL();
                $json['xxxl'] = $v->getXXXL();
                $json['size29'] = $v->getSizeJean29();
                $json['size30'] = $v->getSizeJean30();
                $json['size31'] = $v->getSizeJean31();
                $json['size32'] = $v->getSizeJean32();
                $json['size33'] = $v->getSizeJean33();
                $json['size34'] = $v->getSizeJean34();
                $json['size35'] = $v->getSizeJean35();
                $json['size36'] = $v->getSizeJean36();
                $json['size38'] = $v->getSizeJean38();
                $json['size40'] = $v->getSizeJean40();
                $csv->insertOne($json);
                // $json_variation['category'] = $p->getCategory();
                /*switch ($p->getCategory()) {
                    case 'Jeans':
                        $json['size29'] = $v->getSizeJean29();
                        $json['size30'] = $v->getSizeJean30();
                        $json['size31'] = $v->getSizeJean31();
                        $json['size32'] = $v->getSizeJean32();
                        $json['size33'] = $v->getSizeJean33();
                        $json['size34'] = $v->getSizeJean34();
                        $json['size35'] = $v->getSizeJean35();
                        $json['size36'] = $v->getSizeJean36();
                        $json['size38'] = $v->getSizeJean38();
                        $json['size40'] = $v->getSizeJean40();
                        break;
                    case 'Mocassin':
                        $json['size40'] = $v->getSizeMoc40();
                        $json['size41'] = $v->getSizeMoc41();
                        $json['size42'] = $v->getSizeMoc42();
                        $json['size43'] = $v->getSizeMoc43();
                        $json['size44'] = $v->getSizeMoc44();
                        $json['size45'] = $v->getSizeMoc45();
                        break;
                    default:
                        $json['s'] = $v->getS();
                        $json['m'] = $v->getM();
                        $json['l'] = $v->getL();
                        $json['xl'] = $v->getXL();
                        $json['xxl'] = $v->getXXL();
                        $json['xxxl'] = $v->getXXXL();
                }*/
            }
            $export[] = $json;
        }

        $csv->output($fileName);
        exit;
        //dump($export_data);
        //return $this->render('base.html.twig');
    }

    /**
     * @Route("/admin/products/add", name="add_product_page")
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $product->setEnabled(false);
        $form = $this->get('form.factory')->create(ProductType::class, $product);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            foreach ($product->getVariations() as $variation){
                foreach ($variation->getImages() as $img)
                    $img->setProduct($product);
            }
            $em->flush();
            return $this->redirectToRoute('list_products_page');
        }
        return $this->render('admin/products/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/products/edit/{id}", name="edit_products_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        /*$originalImages = new ArrayCollection();
        foreach ($product->getVariations() as $variation) {
            foreach ($variation->getImages() as $image)
            {
                $originalImages->add($image);
            }
        }*/

        $form = $this->createForm(ProductType::class, $product);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
           /* foreach ($originalImages as $image)
            {
                if(false === $product->getImages()->contains($image))
                {
                    $em->remove($image);
                }
            }*/
            $em->flush();
            return $this->redirectToRoute('list_products_page');
        }
        return $this->render('admin/products/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/products/delete/{id}", name="delete_product_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $product->setDeleted(true);
        $em->flush();
        return $this->redirectToRoute('list_products_page');
    }

    /**
     * @Route("/admin/products/toggle/{id}", name="toggle_product_page")
     */
    public function toggleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        if($product->isEnabled()) {
            $product->setEnabled(false);
        } else {
            $product->setEnabled(true);
        }
        $em->flush();
        return $this->redirectToRoute('list_products_page');
    }

    /**
     * @Route("/admin/product-variations/{id}", name="list_variations_page")
     */
    public function indexVariationsAction($id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);
        $variations = $this->getDoctrine()->getManager()->getRepository(ProductVariation::class)->findBy(array('product' => $product));
        return $this->render('admin/products/list_var.html.twig', array(
            'variations' => $variations,
            'p' => $product
        ));
    }

    /**
     * @Route("/admin/product-variations/delete/{id}", name="delete_product_variations_page")
     */
    public function deleteVariationsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $productVariation = $em->getRepository(ProductVariation::class)->find($id);
        $em->remove($productVariation);
        $em->flush();
        return $this->redirectToRoute('list_variations_page', [
            'id' => $productVariation->getProduct()->getId(),
        ]);
    }

    /**
     * @Route("/admin/products/product-variation/edit/{id}", name="edit_product_variation_page")
     */
    public function editVariationAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productVariation = $em->getRepository(ProductVariation::class)->find($id);
        $form = $this->createForm(ProductVariationType::class, $productVariation)
        ->add('save', SubmitType::class);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('list_variations_page', [
                'id' => $productVariation->getProduct()->getId(),
            ]);
        }
        return $this->render('admin/products/edit_var.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/products/product-variation/edit-images/{id}", name="edit_product_variation_images_page")
     */
    public function editVariationImagesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productVariation = $em->getRepository(ProductVariation::class)->find($id);
        $originalImages = new ArrayCollection();
        foreach ($productVariation->getImages() as $image)
        {
            $originalImages->add($image);
        }

        $form = $this->createForm(ProductVariationEditType::class, $productVariation)
            ->add('save', SubmitType::class);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            foreach ($originalImages as $image)
            {
                if(false === $productVariation->getImages()->contains($image))
                {
                    $em->remove($image);
                }
            }
            $em->flush();
            return $this->redirectToRoute('list_variations_page', [
                'id' => $productVariation->getProduct()->getId(),
            ]);
        }
        return $this->render('admin/products/edit_images.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/products-feed", name="product_feed")
     */
    public function feed()
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(['enabled' =>  true]);
        $feed = "id;title;description;google_product_category;link;image_link;additional_image_link;availability;price;sale_price;color;shipping\n";
        foreach ($products as $p) {
            foreach ($p->getVariations() as $v) {
                // dump($p);
                $feed .= $p->getId()."-".$v->getId() . ";";
                $feed .= $p->getName() . ";";
                $feed .= $p->getDescription() . ";";
                $feed .= $p->getCategory() . ";";
                $feed .= "https://billiorich.com/products/" . $p->getId() . "-" . $v->getId() . ";";
                $feed .= "https://billiorich.com/uploads/product_images/" . $v->getImages()[0]->getImage() . ";";
                $feed .= "https://billiorich.com/uploads/product_images/" . $v->getImages()[0]->getImage() . ";";
                if ($p->getCategory() == "Jeans") {
                    if ($v->getSizeJean29() == 0 && $v->getSizeJean30() == 0 && $v->getSizeJean31() == 0 && $v->getSizeJean32() == 0 && $v->getSizeJean33() == 0 && $v->getSizeJean34() == 0 && $v->getSizeJean35() == 0 && $v->getSizeJean36() == 0 && $v->getSizeJean38() == 0 && $v->getSizeJean40() == 0) {
                        $feed .= "Out of stock;";
                    } else {
                        $feed .= "In stock;";
                    }
                } else if ($p->getCategory() == "Mocassin") {
                    if ($v->getSizeMoc40() == 0 && $v->getSizeMoc41() == 0 && $v->getSizeMoc42() == 0 && $v->getSizeMoc43() == 0 && $v->getSizeMoc44() == 0 && $v->getSizeMoc45() == 0) {
                        $feed .= "Out of stock;";
                    } else {
                        $feed .= "In stock;";
                    }
                } else {
                    if ($v->getS() == 0 && $v->getM() == 0 && $v->getL() == 0 && $v->getXl() == 0 && $v->getXxl() == 0 && $v->getXxxl() == 0) {
                        $feed .= "Out of stock;";
                    } else {
                        $feed .= "In stock;";
                    }
                }
                $feed .= number_format($p->getPrice(), 2, '.', '') . " USD;";
                if ($p->isPromoEnabled()) {
                    $feed .= number_format(($p->getPrice() - $p->getPrice()*($p->getPromoMontant() / 100)), 2, '.', '') . " USD;";
                } else {
                    $feed .= number_format($p->getPrice(), 2, '.', '') . " USD;";
                }
                $feed .= $v->getColor() . ";";
                $feed .= "INTERNATIONAL::Standard:10.00 USD\n";
            }
        }
        // $feed = str_replace(' ', '&nbsp', $feed);
        // $feed = str_replace('-', '&#8209;', $feed);
        return $this->render('admin/products/product-feed.html.twig', array(
            'flux' => $feed
        ));
    }
}