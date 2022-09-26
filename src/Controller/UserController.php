<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="app_user",methods={"GET"}))
     */
    public function index(SerializerInterface $serializer, UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();

        $data = $serializer->serialize($user, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/user", name="app_user_create",methods={"POST"}))
     */
    public function create(Request $request, ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        try {
            $jsonrecup = $request->getContent();
            //dd($jsonrecup);
            $user = $serializer->deserialize($jsonrecup, User::class, "json");
            //dd($user);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($user);
            $em->flush();
            return $this->json($user, 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
}