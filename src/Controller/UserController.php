<?php

namespace App\Controller;




use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\PostService;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    private $security;
    private $manager;
    private $serialize;
    private $profil;
    private $encoder;
    private $encode;
    private $attente;
    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    public function __construct(SerializerInterface $serialize,Security $security,
                                ProfileRepository $profileRepository,UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->serialize= $serialize;
        $this->profileRepository = $profileRepository ;
        $this->encoder =$encoder;
        $this->manager =$manager;


    }
/**
     * @Route(
     *  name = "addUser",
     *  path = "/api/admin/users",
     *  methods = {"POST"},
     *  defaults  = {
     *      "__controller"="App\Controller\UserController::addUser",
     *      "__api_ressource_class"=User::class,
     *      "__api_collection_operation_name"="add_users"
     * }
     * )
     */
     public function addUser(Request $request ,UserService $service , ValidatorInterface $validator)

     {
         $user = $request->request->all() ;
        // dd($user);
         //get profil
         $profil = $user["profiles"] ;

         if($profil == "ADMIN") {
             $users = $this->serialize->denormalize($user, "App\Entity\User");
         } elseif ($profil =="APPRENANT") {
             $users = $this->serialize->denormalize($user, "App\Entity\Apprenant");
             $users->setAttente('1');
         } elseif ($profil =="FORMATEUR") {
             $users = $this->serialize->denormalize($user, "App\Entity\Formateur");
         }elseif ($profil =="CM") {
             $users = $this->serialize->denormalize($user, "App\Entity\Cm");
         }
         //recupération de l'image
         $photo = $request->files->get("avatar");
         //specify entity
        //dd($photo);
         if(!$photo)
         {
             return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
         }
         //$base64 = base64_decode($imagedata);
         $photoBlob = fopen($photo->getRealPath(),"rb");
         //$users = $this->serialize->denormalize($user,true);
         $password = $users->getPassword();
         $users->setAvatar($photoBlob);

         $users->setPassword($this->encoder->encodePassword($users,$password));
         $users->setIsdelate("1");


         $users->setProfile($this->profileRepository->findOneBy(['libelle'=>$profil])) ;

//         $errors = $validator->validate($users);
//         if (count($errors)){
//             $errors = $this->serialize->serialize($errors,"json");
//             return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
//         }
         $em = $this->getDoctrine()->getManager();
         $em->persist($users);
         $em->flush();

         return $this->json("success",201);

     }
/**
         * @Route(
         *  name = "update",
         *  path = "/api/admin/users/{id}",
         *  methods = {"PUT"},
         *  defaults  = {
         *      "__controller"="App\Controller\UserController::update",
         *      "__api_ressource_class"=User::class,
         *      "__api_collection_operation_name"="update_users"
         * }
         * )
         */
    public function update($id,PostService $service, Request $request, EntityManagerInterface $manager,SerializerInterface $serializer,UserRepository $u)
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
            if(method_exists(User::class, $setter)) {
                $user->$setter($value);
                //dd($user);
            }
        }
        $manager->flush();
        return new JsonResponse("success",200,[],true);
    }

}
