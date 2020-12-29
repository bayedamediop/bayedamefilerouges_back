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
     *      path="/api/admin/grpcompetences",
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
        $compObject= $this->serializer->decode($request->getContent(),'json');
        //dd($compObject);
        $groupeCompetene = new GroupeCompetence();
        $groupeCompetene->setLibelle($compObject['libelle']);
        $groupeCompetene->setDescription($compObject['description']);
        foreach ($compObject['competence'] as $competence){
            //dd($competence['libelle']);
            // verification si competence existe ou pas via l'attribut libelle
            if($this->competence->findOneBy(['libelle'=>$competence['libelle']])){
               // recuperation l' objet de competence
                $objCompetence =$this->competence->findOneBy(['libelle'=>$competence['libelle']]);

                $groupeCompetene->addCompetence($objCompetence);
                $this->em->persist($groupeCompetene);
            }else{
                if (isset($compObject['competence'][0]['niveau'])){
                    $data = $compObject['competence'][0]['niveau'];
                    //dd(count($data));
                    if (count($data) == 3){
                        //verification le nombre de competence a insere
                        foreach ($compObject['competence'] as $objetCompetence){
                            $competence = new Competence();
                            $competence->setLibelle($objetCompetence['libelle']);
                            $competence->setDescription($objetCompetence['description']);

                            foreach ($data as $objetniveau){
                                //dd($objetniveau);
                                $niveau = new Niveau();
                                $niveau->setLibelle($objetniveau['libelle'])
                                    ->setCritereEvaluation($objetniveau['critereEvalution']);
                                $competence->addNiveau($niveau);
                                $this->em->persist($niveau);

                            }
                            $this->em->persist($competence);
                            $groupeCompetene->addCompetence($competence);
                            $this->em->persist($groupeCompetene);
                        }
                    }else{
                        return new JsonResponse("Error please enter 3 levels!",400,[],true);

                    }
                }else{
                    return new JsonResponse("Error please enter 3 levels!",400,[],true);
                }
            }
            $this->em->flush();
        }
        return $this->json("valider");

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
        $this->em->flush();
        return $this->json("valider");
    }
}

