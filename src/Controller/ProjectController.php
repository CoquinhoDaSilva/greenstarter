<?php


namespace App\Controller;


use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectController extends AbstractController
{

    /**
     * @Route ("/projects", name="projects")
     */
    public function projects(ProjectRepository $projectRepository) {

        $projects = $projectRepository->findAll();


        return $this->render('projects/projects.html.twig', [
            'projects'=>$projects
        ]);
    }


    /**
     * @Route ("/project/view/{id}", name="project")
     */
    public function project(ProjectRepository $projectRepository, $id) {

        $project = $projectRepository->find($id);


        return $this->render('projects/project.html.twig', [
            'project'=>$project
        ]);
    }


    /**
     * @Route ("/project/insert", name="project_insert")
     */
    public function projectInsert(EntityManagerInterface $entityManager,Security $security, ProjectRepository $projectRepository, Request $request, SluggerInterface $slugger) {


        $user = $security->getUser();
        $project = new Project;

        $formProject = $this->createForm(ProjectType::class, $project);

        $formProject->handleRequest(($request));

        if ($formProject->isSubmitted() && $formProject->isValid()) {

            $project->setDate(new \DateTime('now'));
            $project->setUser($user);
            $picture = $formProject->get('pic')->getData();

            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $project->setPic($newFilename);
            }

            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'Le projet a bien été ajouté !');

            return $this->redirectToRoute('project', ['id'=>$project->getId()]);

        }





        return $this->render('projects/project_insert.html.twig', [
            'formProject'=>$formProject->createView()
        ]);
    }



    /**
     * @Route("/project/update/{id}", name="project_update")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectUpdate(
        ProjectRepository $projectRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        $id,
        SluggerInterface $slugger) {

        $project = $projectRepository->find($id);

        $formProject = $this->createForm(ProjectType::class, $project);
        $formProject->handleRequest($request);

        if ($formProject->isSubmitted() && $formProject->isValid()) {

            $picture = $formProject->get('pic')->getData();


            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $project->setPic($newFilename);
            }


            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project', ['id'=>$project->getId()]);
        }

        return $this->render('projects/project_update.html.twig', [
            'formProject'=>$formProject->createView(),
            'project'=>$project
        ]);
    }

    /**
     * @Route("/project/delete/{id}", name="project_delete")
     * @param ArticleRepository $articleRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectDelete(ProjectRepository $projectRepository,
                                  $id,
                                  EntityManagerInterface $entityManager) {

        $project = $projectRepository->find($id);

        $entityManager->remove($project);
        $entityManager->flush();


        return $this->redirectToRoute('projects');
    }

}