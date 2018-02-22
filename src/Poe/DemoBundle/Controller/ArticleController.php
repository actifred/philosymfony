<?php

namespace Poe\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Poe\DemoBundle\Entity\Article;
use Poe\DemoBundle\Form\ArticleType;
use Poe\DemoBundle\Form\CategoryType;

class ArticleController extends Controller
{
    public function indexAction()
    {
        return $this->render('@PoeDemo/Article/index.html.twig');
    }

    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PoeDemoBundle:Article');

        $article = $repo->find($id);

        $data['article'] = $article;
        return $this->render('@PoeDemo/Article/detail.html.twig', $data);
    }
    
    public function autoformAction(Request $request) {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            if ($form->isValid()) {
                $article->getFeaturedimage()->upload();

                // On enregistre notre objet $article dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('poe_detail_article', array('id' => $article->getId()));
            }
        }

        return $this->render('@PoeDemo/Article/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addAction(Request $request)
    {
        // On crée un objet Article
        $article = new Article();
    
        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $article);
    
        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('date',      DateType::class)
            ->add('titre',     TextType::class)
            ->add('contenu',   TextareaType::class)
            ->add('auteur',    TextType::class)
            ->add('sauver',    SubmitType::class)
        ;
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
    
        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();
  

        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                // On enregistre notre objet $article dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('poe_detail_article', array('id' => $article->getId()));
            }
        }

        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('@PoeDemo/Article/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
