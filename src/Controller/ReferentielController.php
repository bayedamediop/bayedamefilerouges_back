<?php

namespace App\Controller;
use App\Entity\Referentiels;

use App\Service\PostService;
use Doctrine\ORM\EntityManager;
use App\Repository\NiveauRepository;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\ReferentielController;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{

    private $validator;
    private $manage;
    private $referentielsRepository;
    private $competence;
    private $niveau;
    private $serializer;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $manage,SerializerInterface $serializer,
                                ReferentielsRepository $referentielsRepository,CompetenceRepository $competence, NiveauRepository $niveau)
    {
        $this->validator = $validator;
        $this->manage = $manage;
        $this->referentielsRepository = $referentielsRepository;
        $this->competence = $competence;
        $this->niveau = $niveau;
        $this->serializer = $serializer;
    }
    
    /**
     * @Route("/referentiel", name="referentiel")
     */
    public function index(): Response
    {
        return $this->render('referentiel/index.html.twig', [
            'controller_name' => 'ReferentielController',
        ]);
    }
        /**
         * @Route (
         *     name="creatReferentiels",
         *      path="/api/admin/referentieles",
         *      methods={"POST"},
         *     defaults={
         *           "__controller"="App\Controller\ReferentielController::creatReferentiels",
         *           "__api_ressource_class"=Referentiels::class,
         *           "__api_collection_operation_name"="add_referentiel"
         *         }
         * )
         */
    public function creatReferentiels(Request $request,GroupeCompetenceRepository $grpeRepository){
    // $json = json_decode($request->getContent());
       $json = $request->request->all();
    //    $json= $this->serializer->denormalize($jsons);
    //     dd($json);

    //    $referentiel= $this->referentielsRepository->find();
    //      dd($referentiel);
       $ref = new Referentiels();
       $ref->setLibelle($json['libelle']);
       $ref->setPresentation($json['presentation']);
       $ref->setEvaluation($json['evaluation']);
       $ref->setAdmission($json['admission']);
           if( $request->files->get('programme')){
            $programme= $request->files->get('programme');
            $programme = fopen($programme->getRealPath(),'rb');
            $ref->setProgramme($programme);
        }
        if ($json['grpecompetence']) {
           // dd($competencerepo->findBy(['id'=>(int)$json['competence']]));
            $cmop = explode(' ',$json['grpecompetence']);
            for ($i=0; $i < count($cmop); $i++) { 
               if($grpeRepository->find((int)$cmop[$i])){
                  $objet=($grpeRepository->find((int)$cmop[$i]));
                 $ref->setGrpeCompetence($objet);
                 
               }
              
            }
        }

        $manage = $this->getDoctrine()->getManager();
        $manage->persist($ref);
       $manage->flush();

       return $this->json("success",201);

   }
   /**
         * @Route (
         *     name="editReferentiels",
         *      path="/api/admin/referentieles/{id}",
         *      methods={"PUT"},
         *     defaults={
         *           "__controller"="App\Controller\ReferentielController::editReferentiels",
         *           "__api_ressource_class"=Referentiels::class,
         *           "__api_collection_operation_name"="edit_referentiel"
         *         }
         * )
         */
       // public function editReferentiels(Request $request, $id,GroupeCompetenceRepository $grpeRepository){
        public function editReferentiels($id,PostService $service, Request $request, EntityManagerInterface $manager,SerializerInterface $serializer)
        {
            $userForm= $service->PutUser($request, 'programme');
           //dd($userForm);
            $userUpdate = $service->PutUser($request, 'programme');
           // dd($userUpdate);
            $user = $this->referentielsRepository->find($id);
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
