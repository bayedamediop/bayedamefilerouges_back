<?php

namespace App\Controller;
use\App\Entity\Competence;
use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NiveauCompetencesController extends AbstractController
{
    private $manager;
    private $niveauRepository;
    private $competence;
    private $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator,NiveauRepository $niveauRepository,CompetenceRepository $competence){
        $this->manager = $manager;
        $this->niveauRepository = $niveauRepository;
        $this->competence = $competence;
        $this->validator = $validator;


}

    /**
     * @Route (
     *     name="creatNiveauCompetence",
     *      path="/api/admin/competences",
     *      methods={"POST"},
     *     defaults={
     *           "__controller"="App\Controller\NiveauCompetencesController::creatNiveauCompetence",
     *           "__api_ressource_class"=Competence::class,
     *           "__api_collection_operation_name"="add_niveauCompetence"
     *         }
     * )
     */
        public  function creatNiveauCompetence(Request $request)
        {
            $json = json_decode($request->getContent());

            //dd($json);
            if (isset($json->id)){
                $jsonCompetence = $this->competence->find($json->id);
            }else{
                $jsonCompetence = null;
            }
            if ($jsonCompetence != null) {

                    //dans le cas ou groupe competence existe deja
                   // dd($json->niveaux);
                //dd($json);
                    for ($i=0; $i < count($json->niveaux); $i++) {
                        if (isset($json->niveaux[$i]->id)) {
                            //dd("salut");
                            //affectation la/les competences au groupe
                            $competences = $this->niveauRepository->find($json->niveaux[$i]->id);
                            $jsonCompetence->addNiveau($competences);
                        }
                        else{
                            //creation de la/les competences
                            $competences = new Competence;
                            $competences->setLibelle($json->niveaux[$i]->libelle);
                            $competences->setDescription($json->niveaux[$i]->description);
                            $jsonCompetence->addNiveau($competences);
                        }
                        //validation competences avant mise a jour
                        //mettons a jour le bdd
                        //dd($jsonCompetence);
                        $this->manager->persist($jsonCompetence);
                        $this->manager->flush();
                        return $this->json('added succesfully',Response::HTTP_OK);
                    }

                }else{ //si groupe de competence n'existe pas on crée
               // dd($json);
                    $niveau = new Niveau();
                $niveau->setLibelle($json->libelle)
                        ->setCritereEvaluation($json->critereEvoluation);

                    for ($i=0; $i < count($json->niveaux); $i++) {
                        if (isset($json->niveaux[$i]->id)) {
                            //affectation de la competence
                            $niveau = $this->competence->find($json->niveaux[$i]->id);
                            $jsonCompetence->addNiveau($niveau);

                        }else{
                            //creation competence
                            $competences = new Competence;
                            $competences->setLibelle($json->libelle)
                                        ->setDescription($json->description);
                            $jsonCompetence->addNiveau($competences);
                        }
                    }
                dd($json);
                    //validation groupe competences
                    $erreurs = $this->validator->validate($jsonCompetence);
                    if ($erreurs) {
                        return $this->json($erreurs);
                    }

                    $this->manager->persist($jsonCompetence);
                    $this->manager->flush();

                }
                    return $this->json('added succesfully',Response::HTTP_OK);

        }
}
