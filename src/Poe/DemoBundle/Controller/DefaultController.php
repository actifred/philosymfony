<?php

namespace Poe\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Poe\DemoBundle\Entity\Article;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles_repo = $em->getRepository('PoeDemoBundle:Article');

        $articles = $articles_repo->findAll();

        $liste_articles = array();
        foreach ($articles as $article) {
            $url = $this->generateUrl('poe_detail_article', array('id' => $article->getId()));
            $liste_articles[$url] = $article;
        }

        $data = array('is_home' => true, 'articles' => $liste_articles);
        return $this->render('@PoeDemo/Default/index.html.twig', $data);
    }

}
