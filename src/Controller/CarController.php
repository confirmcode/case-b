<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    /**
     * @Route("/car", name="app_car")
     */
    public function index(Request $request, CarRepository $car_repository): Response
    {
        $cars = $car_repository->findAll();
        return $this->renderForm('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }
    /**
     * @Route("/car/new", name="app_car_new")
     */
    public function add_car(Request $request, CarRepository $car_repository): Response
    {
        $car = new Car();
        $form_car = $this->createForm(CarType::class, $car);
        $form_car->handleRequest($request);
        $message = "";
        if ($form_car->isSubmitted() && $form_car->isValid()) {
            $exists = $car_repository->findOneBy(['license_plate' => $car->getLicensePlate()]);
            if ( !$exists ) {
                $car->setUserId(0);
                $car->setVisible(true);
                $car_repository->add($car, true);
                $this->addFlash(
                    'notice',
                    'Машина добавлена!'
                );
                return $this->redirectToRoute('app_car');
            } else {
                $message = "Ошибка: Машина с таким номером уже добавлена.";
            }
        }
        return $this->renderForm('car/form.html.twig', [
            'message' => $message,
            'form_car' => $form_car,
        ]);
    }
}
