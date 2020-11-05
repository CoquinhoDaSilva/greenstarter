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

        $signal = new Signal;

        $formSignal = $this->createForm(SignalType::class, $signal);

        $formSignal->handleRequest(($request));

        if ($formSignal->isSubmitted() && $formSignal->isValid()) {

            $signal->setDate(new \DateTime('now'));
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

}