<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{

    /**
     * @Route ("/blog", name="blog")
     */
    public function blog(ArticleRepository $articleRepository) {

        $articles = $articleRepository->findAll();


        return $this->render('article/blog.html.twig', [
            'articles'=>$articles
        ]);
    }


    /**
     * @Route ("/blog/view/{id}", name="article")
     */
    public function article(ArticleRepository $articleRepository, $id) {

        $article = $articleRepository->find($id);


        return $this->render('article/article.html.twig', [
            'article'=>$article
        ]);
    }


    /**
     * @Route ("/blog/insert", name="article_insert")
     */
    public function articleInsert(EntityManagerInterface $entityManager,Security $security, ArticleRepository $articleRepository, Request $request, SluggerInterface $slugger) {

        $article = new Article;

        $formArticle = $this->createForm(ArticleType::class, $article);

        $formArticle->handleRequest(($request));

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {

            $article->setDate(new \DateTime('now'));
            $picture = $formArticle->get('pic')->getData();

            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $article->setPic($newFilename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a bien été ajouté !');

            return $this->redirectToRoute('article', ['id'=>$article->getId()]);

        }





        return $this->render('article/article_insert.html.twig', [
            'formArticle'=>$formArticle->createView()
        ]);
    }
}