<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PostService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class ApprenantController extends AbstractController
{
    private $security;
    private $manager;
    private $serializer;
    private $encode;


    public function __construct(SerializerInterface $serializer,Security $security)
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
 *      "__api_ressource_class"=Apprenant::class,
 *      "__api_collection_operation_name"="add_users"
 * }
 * )
 */
public function addUser(Request $request, UserService $service,EntityManagerInterface $manager, ValidatorInterface $validator)

{
    $profile = "";
    $apprenant = $service->Apprenant($request, $validator);
    //dd($apprenant);
    $manager->persist($apprenant);
    $manager->flush();
    return new JsonResponse("Success", 200, [], true);

}

/**
     *
     * @Route(
     *        path = "api/admin/users/{id}",
     *      name = "putUserId",
     *      methods = {"PUT"},
     *      defaults  = {
     *
     *      "__api_resource_class"=Apprenant::class,
     *      "__api_item_operation_name"="putUserId"
     *   }
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
            if(method_exists(Apprenant::class, $setter)) {
                $user->$setter($value);
                //dd($user);
            }
        }

        $manager->flush();


        return new JsonResponse("success",200,[],true);


    }
}
