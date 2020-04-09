<?php

namespace ProductBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductVariation;
use ProductBundle\Form\ProductType;
use ProductBundle\Form\ProductVariationEditType;
use ProductBundle\Form\ProductVariationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/products", name="list_products_page")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(array(), array('id' => 'DESC'));
        return $this->render('admin/products/list.html.twig', array(
            'products' => $products,
        ));
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
        $product->setEnabled(false);
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
                $feed .= "https://www.billiorich.com/products/" . $p->getId() . "-" . $v->getId() . ";";
                $feed .= "https://www.billiorich.com/uploads/product_images/" . $v->getImages()[0] . ";";
                $feed .= "https://www.billiorich.com/uploads/product_images/" . $v->getImages()[0] . ";";
                if ($p->getCategory() == "Jeans") {
                    if ($v->getSizeJean29() == 0 && $v->getSizeJean30() == 0 && $v->getSizeJean31() == 0 && $v->getSizeJean32() == 0 && $v->getSizeJean33() == 0 && $v->getSizeJean34() == 0 && $v->getSizeJean35() == 0 && $v->getSizeJean36() == 0 && $v->getSizeJean38() == 0) {
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
                    if ($v->getS() == 0 && $v->getM() == 0 && $v->getL() == 0 && $v->getXl() == 0 && $v->getXxl() == 0) {
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