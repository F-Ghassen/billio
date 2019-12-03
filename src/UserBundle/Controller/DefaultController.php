<?php

namespace UserBundle\Controller;

use MessageBundle\Entity\Message;
use OrderBundle\Entity\DevisItem;
use PromoCodeBundle\Entity\PromoCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_admin_page")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository(DevisItem::class)->countprods();
        $count_messages = $em->getRepository(Message::class)->countMessages();
        $promo_codes = $em->getRepository(PromoCode::class)->findBy(['enabled' => true]);
        dump($promo_codes);
        $code_stats = array();
        foreach ($promo_codes as $value) {
            $items = $em->getRepository(DevisItem::class)->getItemsByCode($value->getCode());
            //$i[] = $value;
            //$i[] = $items;

            $json['value'] = $value;
            $json['items'] = $items;
            //$i[] = $json;

            $code_stats[] = $json;
        }
        dump($code_stats);

        return $this->render('admin/index.html.twig', array(
            'list' => $list,
            'count_messages' => $count_messages,
            'codes' => $code_stats,
        ));
    }
}
