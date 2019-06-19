<?php

namespace UserBundle\Controller;

use MessageBundle\Entity\Message;
use OrderBundle\Entity\DevisItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_admin_page")
     */
    public function indexAction()
    {
        $list = $this->getDoctrine()->getManager()->getRepository(DevisItem::class)->countprods();
        $count_messages = $this->getDoctrine()->getManager()->getRepository(Message::class)->countMessages();

        return $this->render('admin/index.html.twig', array(
            'list' => $list,
            'count_messages' => $count_messages,
        ));
    }
}
