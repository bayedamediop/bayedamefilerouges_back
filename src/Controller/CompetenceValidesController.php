<?php

namespace App\Controller;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\CompetenceValides;
use App\Repository\ApprenantRepository;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\GroupesRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\ReferentielsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceValidesController extends AbstractController
{
    private $validator;
    private $em;
    private $referentielsRepository;
    private $apprenantRepository;
    private $promosRepository;
    private $competence;
    private $niveau;
    private $serializer;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,SerializerInterface $serializer,
                                GroupeCompetenceRepository $groupe,CompetenceRepository $competence, ReferentielsRepository $referentielsRepository
                                ,PromosRepository $promosRepository,ApprenantRepository $apprenantRepository)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->referentielsRepository = $referentielsRepository;
        $this->competence = $competence;
        $this->apprenantRepository = $apprenantRepository;
        $this->promosRepository = $promosRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route (
     *     name="addCompetVailder",
     *      path="/api/admin/competencevalide",
     *      methods={"POST"},
     *     defaults={
     *           "__controller"="App\Controller\CompetenceValidesController::addCompetVailder",
     *           "__api_ressource_class"=CompetenceValides::class,
     *           "__api_collection_operation_name"="competence"
     *         }
     * )
     */
    public function addCompetVailder( Request $request )
    {
        $objCompetence= json_decode($request->getContent(),'json');
        //dd($objCompetence);
        $competencevalide = new CompetenceValides();
        $competencevalide->setNiveau1(false)
            ->setNiveau2(false)
            ->setNiveau3(false);
        //$objetGroupe= $this->groupesRepository->find($id);
       // dd($objCompetence['apprenant']);

            for ($i=0; $i<count($objCompetence['apprenant']);$i++){
                    if ($this->apprenantRepository->find($objCompetence['apprenant'][$i])){
                       // dd("okii");
                        $apprenant=$this->apprenantRepository->find($objCompetence['apprenant'][$i]);
                        $apprenant=($this->serializer->denormalize($objCompetence, CompetenceValides::class));
                        //dd($apprenant);
                        $apprenant->addCompetenceValide($objCompetence);
                        $this->em->persist($apprenant);
                    }else{
                        return $this->json('cette id n est pas un apprenat ',Response::HTTP_OK);
                    }
                }
//        for ($i=0; $i<count($objCompetence['referentiel']);$i++){
//            if ($this->apprenantRepository->find($objCompetence['referentiel'][$i])){
//               // dd("oki");
//                $referentiel=$this->referentielsRepository->find($objCompetence['referentiel'][$i]);
////                dd($objCompetence);
//                $referentiel=($this->serializer->denormalize($objCompetence, CompetenceValides::class));
//                $referentiel->addCompetenceValide($objCompetence);
//
//                $this->em->persist($objCompetence);
//            }else{
//                return $this->json('cette id n est pas un Id referentiel ',Response::HTTP_OK);
//            }
//        }

            //$this->em->flush();

        return new JsonResponse("Success", 200, [], true);

    }
}
