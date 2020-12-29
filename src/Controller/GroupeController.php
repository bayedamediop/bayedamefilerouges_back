<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Groupes;
use App\Repository\ApprenantRepository;
use App\Repository\CompetenceRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\GroupesRepository;
use App\Repository\NiveauRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeController extends AbstractController
{
    private $validator;
    private $em;
    private $groupe;
    private $competence;
    private $niveau;
    private $serializer;
    private $groupesRepository;
    private $apprenantRepository;
    private $formateurRepository;
    private $id;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,SerializerInterface $serializer,
                                GroupeCompetenceRepository $groupe,CompetenceRepository $competence, NiveauRepository $niveau,
                                GroupesRepository $groupesRepository,UserRepository $userRepository,ApprenantRepository $apprenantRepository,
                                    FormateurRepository $formateurRepository)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->groupe = $groupe;
        $this->competence = $competence;
        $this->niveau = $niveau;
        $this->serializer = $serializer;
        $this->groupesRepository = $groupesRepository;
        $this->apprenantRepository = $apprenantRepository;
        $this->formateurRepository = $formateurRepository;
    }
  /**
     * @Route (
     *     name="addApprenantDansUnGroup",
     *      path="/api/admin/groupes/{id}",
     *      methods={"PUT"},
     *     defaults={
     *           "__controller"="App\Controller\GroupeController::addApprenantDansUnGroup",
     *           "__api_ressource_class"=Groupes::class,
     *           "__api_collection_operation_name"="add_apprenant"
     *         }
     * )
    */
  public function  addApprenantDansUnGroup(Request $request , $id){

        $json= json_decode($request->getContent());
       //dd($json);
      $objetGroupe= $this->groupesRepository->find($id);
      //dd($json->apprenants);
      if ($objetGroupe){
      for ($i=0; $i<count($json->apprenants);$i++){
          if ($json->apprenants[$i]->id){
             // dd($json->apprenants[$i]->id);
              if ($this->apprenantRepository->find($json->apprenants[$i]->id)){
                  $apprenant=$this->apprenantRepository->find($json->apprenants[$i]->id);
                  $objetGroupe->addApprenant($apprenant);
                  $this->em->persist($objetGroupe);
              }else{
                  return $this->json('cette id n est pas un apprenat ',Response::HTTP_OK);
              }
      }
      }
      $this->em->flush();
      }else{
          return $this->json('l ID de ce groupes ne exsiste pa ',Response::HTTP_OK);
      }
return new JsonResponse("Success", 200, [], true);
  }

    /**
     * @Route (
     *     name="deleteApprenantDansUnGroup",
     *      path= "/api/admin/groupes/{idg}/apprenants/{ida}",
     *      methods={"DELETE"},
     *     defaults={
     *           "__controller"="App\Controller\GroupeController::deleteApprenantDansUnGroup",
     *           "__api_ressource_class"=Groupes::class,
     *           "__api_collection_operation_name"="delete_apprenant"
     *         }
     * )
     */
    public function  deleteApprenantDansUnGroup(Request $request , $idg,$ida){
        $json= json_decode($request->getContent());
        // dd($json);
        $objetGroupe= $this->groupesRepository->find($idg);
        $apprenant=$this->apprenantRepository->find($ida);
        //dd($apprenant);
        if ($objetGroupe){
            if ($apprenant){
                $objetGroupe->removeApprenant($apprenant);
                $this->em->persist($objetGroupe);
            }else{
                return $this->json('cette id n est pas un apprenat ',Response::HTTP_OK);
            }

        }else{
            return $this->json('l ID de ce groupes ne exsiste pa ',Response::HTTP_OK);
        }


            $this->em->flush();

        return new JsonResponse("Success", 200, [], true);
    }

 /* @Route (
 *     name="addApprenantFormateDunGroup",
 *      path="/api/admin/groupes",
 *      methods={"POST"},
 *     defaults={
 *           "__controller"="App\Controller\GroupeController::addApprenantFormateDunGroup",
 *           "__api_ressource_class"=Groupes::class,
 *           "__api_collection_operation_name"="add_groupe"
 *         }
 * )
 */
    public function addApprenantFormateDunGroup( Request $request , GroupesRepository $groupesRepository)
    {
        $compObject= json_decode($request->getContent(),'json');
        foreach ($compObject as $groupe) {
                    $groupes=$groupesRepository->find($groupe['id']);
                    //dd($groupes);

                if ($groupesRepository->findOneBy(['nom' => $groupe['nom']])) {
                    $objetgroupe = $groupesRepository->findOneBy(['nom' => $groupe['nom']]);
                    dd($objetgroupe); }
                //dd($objetgroupe);


        foreach ($compObject['apprenant'] as $competence){
            //dd($competence['libelle']);
            // verification si competence existe ou pas via l'attribut libelle
            if($this->competence->findOneBy(['libelle'=>$competence['libelle']])){
                // recuperation l' objet de competence
                $objCompetence =$this->competence->findOneBy(['libelle'=>$competence['libelle']]);

                $groupeCompetene->addCompetence($objCompetence);
                $this->em->persist($groupeCompetene);
            }
    }
    }
    }
}
