<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class FormateurController extends AbstractController
{
    private $security;
    private $manager;
    private $serializer;
    private $encoder;


    public function __construct(SerializerInterface $serializer,Security $security)
    {
        $this->security = $security;
        $this->serializer= $serializer;

    }
    /**
     * @Route (
     * name = "putUserId",
     *  path = "/api/admin/users/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\UserController::putUserId",
     *      "__api_ressource_class"=Formateur::class,
     *      "__api_collection_operation_name"="put_users"
     * }
     * )
     */
    public function addUser(Request $request, PostService $postService,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder )

    {
        $users=$postService->createUser($request,"Formateur");
      // dd($users);
        $users= $this->serializer->denormalize($users,"App\Entity\Formateur");
        $password = $users->getPassword();
        $users->setPassword($encoder->encodePassword($users,$password));
        $manager->persist($users);
        $manager->flush();
        return new JsonResponse('cool',Response::HTTP_OK,[],true);
    }

    /**
     * name = "putUserId",
     *  path = "/api/admin/users/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\UserController::putUserId",
     *      "__api_ressource_class"=Formateur::class,
     *      "__api_collection_operation_name"="put_users"
     * }
     * )
     * @param PostService $service
     * @param Request $request
     * @return JsonResponse
     */
    public function putUserId($id,PostService $service, Request $request, EntityManagerInterface $manager,SerializerInterface $serializer,UserRepository $u)
    {
        $userForm= $service->PutUser($request);
        // dd($userForm);
        $userUpdate = $service->PutUser($request, 'avatar');
        $user = $u->find($id);
        foreach ($userForm as $key => $value) {
            if($key === 'profile'){
                $value = $serializer->denormalize($value, Profile::class);
            }
            $setter = 'set'.ucfirst(trim(strtolower($key)));
            //dd($setter);
            if(method_exists(Formateur::class, $setter)) {
                $user->$setter($value);
                //dd($user);
            }
        }

        $manager->flush();


        return new JsonResponse("success",200,[],true);


    }
}
