<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\UserRepository;
use App\Controller\ProfileController;
use App\Repository\ProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route(
     *  name = "getprofilesdunuser",
     *  path = "/api/admin/profiles/{id}/users",
     *  methods = {"GET"},
     *  defaults  = {
     *      "__controller"="App\Controller\ProfileController::getprofilesdunuser",
     *      "__api_ressource_class"=Profile::class,
     *      "__api_collection_operation_name"="getprofilesdunuser"
     * }
     * )
     */
    public function getprofilesdunuser(ProfileRepository $ProfileRepository,UserRepository $user, int $id)
    {
        $profile = $ProfileRepository->find($id);
        if (!empty($profile)) {
           foreach ($profile->getUsers() as $key => $utilisateur) {
               $tab []=[
                   "user"=>$user->findOnBy(['id'->$utilisateur->getId()])
               ]; 
           }
           return $this->json($tab,200,[],["groups"=>["profile"]]);
        }
    }
}
