<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventController extends AbstractController
{

    /**
     * @Route ("/events", name="events")
     */
    public function events(EventRepository $eventRepository) {

        $events = $eventRepository->findAll();


        return $this->render('event/events.html.twig', [
            'events'=>$events
        ]);
    }

    /**
     * @Route ("/event/insert", name="event_insert")
     */
    public function eventInsert(EntityManagerInterface $entityManager,Security $security, EventRepository $eventRepository, Request $request, SluggerInterface $slugger) {

        $user = $security->getUser();
        $event = new Event;

        $formEvent = $this->createForm(EventType::class, $event);

        $formEvent->handleRequest(($request));

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {

            $event->setUser($user);
            $picture = $formEvent->get('pic')->getData();


            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $event->setPic($newFilename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events');

        }

        return $this->render('event/event_insert.html.twig', [
            'formEvent'=>$formEvent->createView()
        ]);
    }


    /**
     * @Route("/event/update/{id}", name="event_update")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventUpdate(
        EventRepository $eventRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        $id,
        SluggerInterface $slugger) {

        $event = $eventRepository->find($id);

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {

            $picture = $formEvent->get('pic')->getData();


            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $event->setPic($newFilename);
            }


            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events', ['id'=>$event->getId()]);
        }

        return $this->render('event/event_update.html.twig', [
            'formEvent'=>$formEvent->createView(),
            'event'=>$event
        ]);
    }

    /**
     * @Route("/event/delete/{id}", name="event_delete")
     * @param ArticleRepository $articleRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleDelete(EventRepository $eventRepository,
                                  $id,
                                  EntityManagerInterface $entityManager) {

        $event = $eventRepository->find($id);

        $entityManager->remove($event);
        $entityManager->flush();


        return $this->redirectToRoute('events');
    }


}