<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     */
    public function home(ArticleRepository $articleRepository, EventRepository  $eventRepository, ProjectRepository $projectRepository) {

        $lastArticles = $articleRepository->findBy([], ['date'=>'DESC'], 4, 0);
        $lastEvents = $eventRepository->findBy([], ['date'=>'DESC'], 4, 0);
        $lastProjects = $projectRepository->findBy([], ['date'=>'DESC'], 3, 0);


        return $this->render('pages/index.html.twig', [
            'lastArticles'=>$lastArticles,
            'lastEvents'=>$lastEvents,
            'lastProjects'=>$lastProjects
        ]);
    }

}