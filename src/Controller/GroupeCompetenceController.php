<?php

namespace App\Controller;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Competence;
use App\Entity\GroupeCompetence;

use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeCompetenceController extends AbstractController
{
    private $validator;
    private $em;
    private $groupe;
    private $competence;
    private $niveau;
    private $serializer;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,SerializerInterface $serializer,
                                GroupeCompetenceRepository $groupe,CompetenceRepository $competence, NiveauRepository $niveau)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->groupe = $groupe;
        $this->competence = $competence;
        $this->niveau = $niveau;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/groupe/competence", name="groupe_competence")
     */
    public function index(): Response
    {
        return $this->render('groupe_competence/index.html.twig', [
            'controller_name' => 'GroupeCompetenceController',
        ]);
    }
    /**
     * @Route (
     *     name="creatGroupeCompetence",
     *      path="/api/adminssss/grpecompetences",
     *      methods={"POST"},
     *     defaults={
     *           "__controller"="App\Controller\GroupeCompetenceController::creatGroupeCompetence",
     *           "__api_ressource_class"=GroupeCompetence::class,
     *           "__api_collection_operation_name"="add_groupeCompetence"
      *         }
     * )
     */
    public function creatGroupeCompetence(Request $request)
    {
        $json = json_decode($request->getContent());
       // dd($json,401);
        
        //verifions s'il faut crée le groupe oubien l'affecté des competences
        if (isset($json->id)) {
            $groupeCompetences = $this->groupe->find($json->id);
        }else{
            $groupeCompetences = null;
        }
        if ($groupeCompetences != null) {
            //dans le cas ou groupe competence existe deja
            for ($i=0; $i < count($json->competences); $i++) { 
                if (isset($json->competences[$i]->id)) {
                    //affectation la/les competences au groupe
                    $competences = $this->competence->find($json->competences[$i]->id);
                    $groupeCompetences->addCompetence($competences);
                }else{
                    //creation de la/les competences
                    $competences = new Competence;
                    $competences->setLibelle($json->competences[$i]->libelle)
                                ->setDescription($json->competences[$i]->description);
                    $groupeCompetences->addCompetence($competences);
                }
            }
            //validation competences avant mise a jour
            $erreurscompetences = $this->validator->validate($competences);
            if ($erreurscompetences) {
                return $this->json($erreurscompetences);
            }
         
        }else{ //si groupe de competence n'existe on crée
            $groupeCompetences = new GroupeCompetence;
                $groupeCompetences->setLibelle($json->libelle)
                                  ->setDescription($json->description);
            for ($i=0; $i < count($json->competences); $i++) { 
                if (isset($json->competences[$i]->id)) {
                    //affectation de la competence
                    $competences = $this->competence->find($json->competences[$i]->id);
                    $groupeCompetences->addCompetence($competences);
                }else{
                    //creation competence
                    $competences = new Competence;
                    $competences->setLibelle($json->competences[$i]->libelle)
                                ->setDescription($json->competences[$i]->description);
                    $groupeCompetences->addCompetence($competences);
                }
            }
            //validation groupe competences
            $erreurs = $this->validator->validate($groupeCompetences);
            if ($erreurs) {
                return $this->json($erreurs);
            }

            $this->em->persist($groupeCompetences);
            
        }
        $this->em->flush();
            return $this->json('added succesfully',Response::HTTP_OK);
    }
    /**
     * @Route (
     *     name="addDeleteCrpCompetecne",
     *      path="/api/admin/grpecompetences/{id}",
     *      methods={"PUT"},
     *     defaults={
     *           "__controller"="App\Controller\GroupeCompetenceController::addDeleteCrpCompetecne",
     *           "__api_ressource_class"=GroupeCompetence::class,
     *           "__api_collection_operation_name"="addDelet_groupeCompetence"
     *         }
     * )
     */
    public  function  addDeleteCrpCompetecne( Request $request ,$id){

       $json = json_decode($request->getContent());
       $grpecompetence = $this->groupe->find($id);
       if ($json->action=="add"){
           for ($i=0; $i<count($json->competences);$i++){
               if ($json->competences[$i]->id){
                   $competence = $this->competence->find($json->competences[$i]->id);
                   if ($competence){
                       $grpecompetence->addCompetence($competence);
                   }else{
                       dd("id bi amoul de");
                   }
               }
           }
       }else{
           if ($json->action=="delete"){
              // dd("delete");
               for ($i=0; $i < count($json->competences); $i++){
                   if ($json->competences[$i]->id){
                       $competence = $this->competence->find($json->competences[$i]->id);
                       if ($competence){
                           $grpecompetence->removeCompetence($competence);
                       }
                   }
               }
           }
       }
          //mettons a jour le bdd
        $this->em->flush();
        return $this->json("valider");
    }
}

