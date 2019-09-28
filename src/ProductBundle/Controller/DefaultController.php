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
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();
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
        $product->setEnabled(true);
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
     * @Route("/admin/product-variations/{id}", name="list_variations_page")
     */
    public function indexVariationsAction($id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);
        $variations = $this->getDoctrine()->getManager()->getRepository(ProductVariation::class)->findBy(array('product' => $product));
        return $this->render('admin/products/list_var.html.twig', array(
            'variations' => $variations,
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
}