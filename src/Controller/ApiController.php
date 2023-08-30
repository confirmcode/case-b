<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiController extends AbstractController
{
    // /**
    //  * @Route("/api/v1/cars", methods={"GET"})
    //  */
    // public function car_list(CarRepository $car_repository): JsonResponse
    // {
    //     return $this->json($car_repository->getListOfAvailableCars(), 200);
    // }

    // /**
    //  * @Route("/api/v1/users", methods={"GET"})
    //  */
    // public function user_list(UserRepository $user_repository): JsonResponse
    // {
    //     return $this->json($user_repository->getListActive(), 200);
    // }

    /**
     * @Route("/api/v1/bookingcar", methods={"POST"}) 
     */
    public function booking_car(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(null, Response::HTTP_UNAUTHORIZED);
        }

        $car_e = $entityManager->getRepository(Car::class)->findOneBy([
            'id' => $request->request->get('car'), 
            'userId' => 0,
        ]);
        $user_e = $entityManager->getRepository(User::class)->findOneBy([
            'id' => $user->getId(),
            'carId' => 0
        ]);

        if ( !$user_e ) {
            $message = 'Вы не можете бронировать больше одной машины (забронирована '.$user->getCarId().').';
            return $this->json(['message'=>$message], Response::HTTP_BAD_REQUEST);
        }
        
        if ( $car_e ) {
            $car_e->setUserId( $user_e->getId() );
            $user_e->setCarId( $car_e->getId() );
            $entityManager->flush();
            $message = 'Машина '.$car_e->getId().' забронированна для пользователя '.$user_e->getId();
            return $this->json(['message'=>$message], Response::HTTP_OK);
        }
        return $this->json([
            'car_found' => $car_e ? $car_e->getId() : '0',
            'user_found' => $user_e ? $user_e->getId() : '0',
        ], Response::HTTP_BAD_REQUEST);
        
    }

    /**
     * @Route("/api/v1/releasecar", methods={"POST"}) 
     */
    public function release_car(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(null, Response::HTTP_UNAUTHORIZED);
        }

        $car_e = $entityManager->getRepository(Car::class)->findOneBy([
            'id' => $user->getCarId(), 
            'userId' => $user->getId(),
        ]);
        
        if ( !$car_e ) {
            $message = 'Ошибка 1: забронированная машина не найдена';
            return $this->json(['message'=>$message], Response::HTTP_BAD_REQUEST);
        }
        $user_e = $entityManager->getRepository(User::class)->findOneBy([
            'id' => $user->getId(),
            'carId' => $user->getCarId(),
        ]);
        if ( !$user_e ) {
            $message = 'Ошибка 2: забронированная машина не найдена';
            return $this->json(['message'=>$message], Response::HTTP_BAD_REQUEST);
        }
        
        $car_e->setUserId(0);
        $user_e->setCarId(0);
        $entityManager->flush();
        $message = 'Пользователь '.$user_e->getId().' освободил машину '.$car_e->getId();
        return $this->json(['message'=>$message], Response::HTTP_OK);
    }
}
