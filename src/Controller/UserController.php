<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserGroup;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends ApiController
{
    /**
     * @Route("/getAllGroups")
     */
    public function getAllGroups()
    {
        $repository = $this->getDoctrine()->getRepository(UserGroup::class);
        $user_groups = $repository->findAll();
        return new JsonResponse($user_groups);
    }

    /**
     * @Route("/getAllUsers")
     */
    public function getAllUsers()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user_groups = $repository->findAll();
        return new JsonResponse($user_groups);
    }



    /**
     * @Route("/addUser", methods={"POST"})
     */
    public function addUser(Request $request,ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName($data['name']);
        $entityManager->persist($user);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }else {
            $entityManager->flush();
            return new JsonResponse( 'User saved successfully ' . json_encode($user));
        }
    }

    /**
     * @Route("/deleteUser/{user_id}")
     */
    public function deleteUser(int $user_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $user = $repository->find($user_id);
        if($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            return new JsonResponse('Removed user ' . $user_id);
        }else{
            return new JsonResponse('User not found with id ' . $user_id);
        }
    }

    /**
     * @Route("/addGroup", methods={"POST"})
     */
    public function addGroup(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        $user_group = new UserGroup();
        $user_group->setName($data['name']);
        $entityManager->persist($user_group);
        $errors = $validator->validate($user_group);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }else {
            $entityManager->flush();
            return new JsonResponse( 'User group saved successfully ' . json_encode($user_group));
        }
    }

    /**
     * @Route("/addUserToGroup/{group_name}",  methods={"POST"})
     */
    public function addUserToGroup(Request $request, $group_name)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($data['id']);

        if(!$user){
            $user = new User();
            $user->setName($data['name']);
            $entityManager->persist($user);
        }

        $repository = $this->getDoctrine()->getRepository(UserGroup::class);
        $criteria = array('name' => $group_name);
        $ug = $repository->findOneBy($criteria);
        if($ug) {
            $ug->addUser($user);
            $entityManager->flush();
            return new JsonResponse('User added to the group : ' . $group_name);
        }else{
            return new JsonResponse('User group not found ' . $group_name);
        }
    }

    /**
     * @Route("/removeUserFromGroup/{group_name}",  methods={"POST"})
     */
    public function removeUserFromGroup(Request $request, $group_name)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($data['id']);

        if(!$user){
            return new JsonResponse('User  not found ' . $user->getId());
        }

        $repository = $this->getDoctrine()->getRepository(UserGroup::class);
        $criteria = array('name' => $group_name);
        $ug = $repository->findOneBy($criteria);
        if($ug) {
            $ug->removeUser($user);
            $entityManager->flush();
            return new JsonResponse('User removed from the group : ' . $group_name);
        }else{
            return new JsonResponse('User group not found ' . $group_name);
        }
    }



}