<?php

namespace PromoCodeBundle\Controller;

use PromoCodeBundle\Entity\PromoCode;
use PromoCodeBundle\Form\PromoCodeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
 * @Route("/admin/code", name="list_code_page")
 */
    public function indexAction()
    {
        $codes = $this->getDoctrine()->getManager()->getRepository(PromoCode::class)->findAll();
        return $this->render('admin/promo/list.html.twig', array(
            'codes' => $codes
        ));
    }

    /**
     * @Route("/admin/code/check/{code}", name="check_code_page", options = { "expose" = true })
     */
    public function checkAction($code)
    {
        $promo = $this->getDoctrine()->getManager()->getRepository(PromoCode::class)->findOneBy(['code' => $code, 'enabled' => true]);
        if($promo != null) {
            if($promo->isEnabled()) {
                $serializer = $this->get('jms_serializer');
                $data = $serializer->serialize($promo, 'json');
                return new JsonResponse($data);
            } else {
                return new JsonResponse("false");
            }
        } else {
            return new JsonResponse("false");
        }
    }

    /**
     * @Route("/admin/code/{code}", name="get_code_page", options = { "expose" = true })
     */
    public function getAction($code)
    {
        $promo = $this->getDoctrine()->getManager()->getRepository(PromoCode::class)->findOneBy(['code' => $code, 'enabled' => true]);
        if($promo != null) {
            if($promo->isEnabled()) {
                $serializer = $this->get('jms_serializer');
                $data = $serializer->serialize($promo, 'json');
                return new JsonResponse($data);
            } else {
                return new JsonResponse("null");
            }
        } else {
            return new JsonResponse("null");
        }
    }

    /**
     * @Route("/admin/code/add", name="add_code_page")
     */
    public function addAction(Request $request)
    {
        $code = new PromoCode();
        $form = $this->get('form.factory')->create(PromoCodeType::class, $code);
        $code->setEnabled(true);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($code);
            $em->flush();
            return $this->redirectToRoute('list_code_page');
        }
        return $this->render('admin/promo/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/code/edit/{id}", name="edit_code_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $code = $em->getRepository(PromoCode::class)->find($id);

        $form = $this->createForm(PromoCodeType::class, $code);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('list_code_page');
        }
        return $this->render('admin/promo/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/code/delete/{id}", name="delete_code_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $code = $em->getRepository(PromoCode::class)->find($id);
        $em->remove($code);
        $em->flush();
        return $this->redirectToRoute('list_code_page');
    }

    /**
     * @Route("/admin/code/enable/{id}", name="enable_code_page")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $code = $em->getRepository(PromoCode::class)->find($id);
        if($code->isEnabled()) {
            $code->setEnabled(false);
        } else {
            $code->setEnabled(true);
        }
        $em->flush();
        return $this->redirectToRoute('list_code_page');
    }
}
