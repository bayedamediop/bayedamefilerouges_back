<?php

namespace App\Controller;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Groupes;
use  App\Entity\Promos;
use App\Entity\Referentiels;
use App\Repository\ApprenantRepository;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\GroupesRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\ReferentielsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
   public  function createPromos( Request $request){
            $json =$this->serializer->decode($request->getContent(),"json");
            //dd($json['etudiants']);
       foreach ($json as $jsons){
           if ($this->promosRepository->findOneBy(['titre'=>$json['titre']])){

               $objetjson=$this->promosRepository->findOneBy(['titre'=>$json['titre']]);
               //dd($objetjson);
               foreach ($json['referentiels'] as $referentiels){
                   if ($this->referentielsRepository->findOneBy(["libelle"=>$referentiels["libelle"]])){
                       //recuperation l' objet de referentielles
                       $objetreferentiel=$this->referentielsRepository->findOneBy(['libelle'=>$referentiels['libelle']]);
                       $objetreferentiel->addPromo($objetjson);
                       $this->em->persist($objetreferentiel);
                   }
               }
               foreach ($json['groupes'] as $groupe){
                   if ($this->groupesRepository->findOneBy(["nom"=>$groupe["nom"]])){
                       //recuperation l' objet de referentielles
                       $objetgroupes=$this->groupesRepository->findOneBy(['nom'=>$groupe['nom']]);

                       $objetjson->addGroupe($objetgroupes);
                       // eecupere l id de l etudiant

                       $this->em->persist($objetjson);
                   }
               }
                       $this->em->flush();
           }else{
               $promos = new Promos();
               $promos->setLangue($json['langue'])
                   ->setTitre($json['titre'])
                   ->setDescription($json['description'])
                   ->setLieu($json['lieu'])
                   ->setFabrique($json['fabrique'])
                   ->setDateDebut(new \DateTime())
                   ->setDateFinProvisoire( new \DateTime())
                   ->setDateFinReel(new \DateTime())
                   ->setEtat($json['etat']);
               //creation des groupes
               $objetreferentiel = new Referentiels();
             // dd($json['referentiels']);
              foreach ($json['referentiels'] as $referentiel)
               $objetreferentiel->setLibelle($referentiel["libelle"])
                   ->setPresentation($referentiel['presentation'])
                   ->setProgramme($referentiel['programme'])
                   ->setEvaluation($referentiel['evaluation'])
                   ->setAdmission($referentiel['admission']);
               $objetreferentiel->addPromo($promos);
               $this->em->persist($objetreferentiel);
               foreach ($json['groupes'] as $jsongroue){
                 //  dd($jsongroue);
                   $groupe = new Groupes();
                   $groupe->setNom($jsongroue['nom'])
                       ->setType($jsongroue['type'])
                       ->setStatut(1)
                       ->setDateCreation(new \DateTime());

                   $promos->addGroupe($groupe);

                   $this->em->persist($promos);

               }
              // $this->em->flush();
           }
           $this->em->flush();
       }
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

