<?php

namespace App\Controller;

use App\Form\BookingCarType;
use App\Form\ReleaseCarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingCarController extends AbstractController
{
    /**
     * @Route("/booking_car", name="app_booking_car")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $message = 'Для бронирования нужно авторизоваться';
            return $this->render('booking_car/index.html.twig', ['message'=>$message]);
        }
        if ($user->getCarId()) {
            $message = 'Бронировать можно только одну машину (забронирована машина '.$user->getCarId().')';
            $form = $this->createForm(ReleaseCarType::class)->createView();
            return $this->render('booking_car/index.html.twig', ['form'=>$form,'message'=>$message]);
        }
        $form = $this->createForm(BookingCarType::class)->createView();
        return $this->render('booking_car/index.html.twig', ['form'=>$form]);
    }
}
