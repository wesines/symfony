<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/api/user", name="app_user_all",methods={"GET"})
     */
    public function index(SerializerInterface $serializer, UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();

        $data = $serializer->serialize($user, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
    /**
     * @Route("/api/user/{id}", name="app_user_one",methods={"GET"})
     */
    public function showUser($id, SerializerInterface $serializer, UserRepository $userRepository): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneById($id);

        // $user = $userRepository->findOneBy($id);

        $data = $serializer->serialize($user, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
    /**
     * @Route("/api/user", name="app_user_create",methods={"POST"})
     */
    public function create(UserPasswordHasherInterface $passwordHasher, Request $request, ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        try {
            $jsonrecup = $request->getContent();
            $user = $serializer->deserialize($jsonrecup, User::class, "json");

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

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

    /**
     * @Route("/api/user/{id}", name="app_user_update",methods={"PATCH"})
     */
    public function updateUser($id, Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->findOneById($id);

            $data = json_decode($request->getContent(), true);

            empty($data['city']) ? true : $user->setCity($data['city']);
            empty($data['username']) ? true : $user->setUsername($data['username']);
            empty($data['lat']) ? true : $user->setLat($data['lat']);
            empty($data['lng']) ? true : $user->setLng($data['lng']);
            empty($data['password']) ? true : $user->setPassword($data['password']);
            empty($data['roles']) ? true : $user->setRoles($data['roles']);



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