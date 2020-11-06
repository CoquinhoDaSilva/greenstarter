<?php


namespace App\Controller;


use App\Entity\Signal;
use App\Form\SignalType;
use App\Repository\SignalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class SignalController extends AbstractController
{

    /**
     * @Route ("/signals", name="signals")
     */
    public function signals(SignalRepository $signalRepository) {

        $signals = $signalRepository->findAll();


        return $this->render('signals/signals.html.twig', [
            'signals'=>$signals
        ]);
    }

    /**
     * @Route ("/signal/insert", name="signal_insert")
     */
    public function signalInsert(EntityManagerInterface $entityManager,Security $security, SignalRepository $signalRepository, Request $request, SluggerInterface $slugger) {


        $user = $security->getUser();
        $signal = new Signal;


        $formSignal = $this->createForm(SignalType::class, $signal);

        $formSignal->handleRequest(($request));

        if ($formSignal->isSubmitted() && $formSignal->isValid()) {

            $signal->setDate(new \DateTime('now'));
            $signal->setUser($user);
            $picture = $formSignal->get('pic')->getData();

            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $signal->setPic($newFilename);
            }

            $entityManager->persist($signal);
            $entityManager->flush();

            return $this->redirectToRoute('signals');

        }

        return $this->render('signals/signal_insert.html.twig', [
            'formSignal'=>$formSignal->createView()
        ]);
    }


    /**
     * @Route("/signal/update/{id}", name="signal_update")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signalUpdate(
        SignalRepository $signalRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        $id,
        SluggerInterface $slugger) {

        $signal = $signalRepository->find($id);

        $formSignal = $this->createForm(SignalType::class, $signal);
        $formSignal->handleRequest($request);

        if ($formSignal->isSubmitted() && $formSignal->isValid()) {

            $picture = $formSignal->get('pic')->getData();


            if ($picture) {

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$picture->guessExtension();

                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $signal->setPic($newFilename);
            }


            $entityManager->persist($signal);
            $entityManager->flush();

            return $this->redirectToRoute('signals');
        }

        return $this->render('signals/signal_update.html.twig', [
            'formSignal'=>$formSignal->createView(),
            'signal'=>$signal
        ]);
    }

    /**
     * @Route("/signal/delete/{id}", name="signal_delete")
     * @param ArticleRepository $articleRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signaltDelete(SignalRepository $signalRepository,
                                  $id,
                                  EntityManagerInterface $entityManager) {

        $signal = $signalRepository->find($id);

        $entityManager->remove($signal);
        $entityManager->flush();


        return $this->redirectToRoute('signals');
    }

}