<?php

namespace App\Controller;

use App\Entity\Cm;
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

class CmController extends AbstractController
{
    private $security;
    private $manager;
    private $serializer;
    private $encoder;


    public function __construct(SerializerInterface $serializer,Security $security )
    {
        $this->security = $security;
        $this->serializer= $serializer;

    }
    /**
     * @Route(
     *  name = "addUser",
     *  path = "/api/admin/users",
     *  methods = {"POST"},
     *  defaults  = {
     *      "__controller"="App\Controller\UserController::addUser",
     *      "__api_ressource_class"=Cm::class,
     *      "__api_collection_operation_name"="add_users"
     * }
     * )
     */
    public function addUser(Request $request, PostService $postService,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)

    {
        $users=$postService->createUser($request,"Cm");
        //dd($user);
        $users= $this->serializer->denormalize($users,"App\Entity\Cm");
        $password = $users->getPassword();
        $users->setPassword($encoder->encodePassword($users,$password));
        $manager->persist($users);
        $manager->flush();
        return new JsonResponse('cool',Response::HTTP_OK,[],true);
    }
    /**
     * @Route(
     *     path="api/admin/users/{id}",
     *      name="putUserId",
     *     methods={"PUT"},
     *     defaults={
     *      "_api_resource_class"=Cm::class,
     *      "_api_item_operation_name"="putUserId"
     *     }
     *     )
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
            if(method_exists(Profile::class, $setter)) {
                $user->$setter($value);
                //dd($user);
            }
        }

        $manager->flush();


        return new JsonResponse("success",200,[],true);


    }
}