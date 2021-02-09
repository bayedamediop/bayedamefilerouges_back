<?php

namespace App\Controller;
use  App\Entity\Promos;
use App\Entity\Groupes;
use App\Entity\Referentiels;
use App\Service\PostService;
use App\Repository\UserRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\GroupesRepository;
use App\Repository\ApprenantRepository;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{
    private $validator;
    private $em;
    private $promosRepository;
    private $groupesRepository;
    private $referentielsRepository;
    private $serializer;
    private $userRepository;
    private $apprenantRepository;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,SerializerInterface $serializer,
                                PromosRepository $promosRepository,GroupesRepository $groupesRepository ,
                                ReferentielsRepository $referentielsRepository, UserRepository $userRepository, ApprenantRepository $apprenantRepository)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->promosRepository = $promosRepository;
        $this->referentielsRepository = $referentielsRepository;
        $this->groupesRepository = $groupesRepository;

        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->apprenantRepository = $apprenantRepository;
    }


    /**
 *
 * @Route (
 *     name="createPromos",
 *      path="/api/admin/promos",
 *      methods={"POST"},
 *     defaults={
 *           "__controller"="App\Controller\PromoController::createPromos",
 *           "__api_ressource_class"=Promos::class,
 *           "__api_collection_operation_name"="add_promo"
 *         }
 * )
 */
   public  function createPromos( Request $request,PostService $service){
            $json = $request->request->all();
            //dd($json);
            //dd($json['etudiants']);
             $photo = $request->files->get("avatar");
             if(!$photo)
            {
                return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
            }
            $base64 = base64_decode($photo);
            $photoBlob = fopen($photo->getRealPath(),"rb");
        $promos = new Promos();
               $promos->setLangue($json['langue'])
                   ->setTitre($json['titre'])
                   ->setDescription($json['description'])
                   ->setLieu($json['lieu'])
                   ->setFabrique($json['fabrique'])
                   ->setAvatar($photoBlob)
                   ->setDateDebut(new \DateTime())
                   ->setDateFinProvisoire( new \DateTime())
                   ->setDateFinReel(new \DateTime())
                   ->setEtat($json['etat']);
               if ($json['referentiels']){
                    if($this->referentielsRepository->find((int)$json['referentiels'])){
                       $objet=($this->referentielsRepository->find((int)$json['referentiels']));
                      $promos->setReferentiels($objet);                     
                    }
                   }
               
             if ($json['apprenents']) {
                 //dd($json['apprenents']['profile']);
               $compte = explode(' ',$json['apprenents']);
               for ($i=0; $i < count($compte) ; $i++) { 
                //dd($compte[$i])
                   $aprenant = $this->apprenantRepository->find((int)$compte[$i]);
                $promos->setApprenant($aprenant);
               }

             }
               $this->em->persist($promos);   
           $this->em->flush();
       return $this->json("valider");
   }

    /**
     *
     * @Route (
     *     name="editpromoreferentiel",
     *      path="/api/admin/promos/{id}/referentiels",
     *      methods={"PUT"},
     *     defaults={
     *           "__controller"="App\Controller\PromoController::editpromoreferentiel",
     *           "__api_ressource_class"=Promos::class,
     *           "__api_collection_operation_name"="edit_promreref"
     *         }
     * )
     */
    public function editpromoreferentiel(Request $request ,$id){
        $json = json_decode($request->getContent());
        $promo=$this->promosRepository->find($id);
        if (isset($promo)){
            dd($promo);
        }else{
            dd("amoul");
        }



    }
}

