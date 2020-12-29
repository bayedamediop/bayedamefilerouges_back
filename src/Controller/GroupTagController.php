<?php

namespace App\Controller;

use App\Entity\GroupeTag;
use App\Entity\Tag;
use App\Repository\GroupeTagRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupTagController extends AbstractController
{
    private $validator;
    private $em;
    private $groupe;
    private $tagRepo;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,
                                GroupeTagRepository $groupe,TagRepository $tagRepo)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->groupe = $groupe;
        $this->tagRepo = $tagRepo;
    }

/**
 * @Route(
 *  name = "createGroupeTags",
 *  path = "/api/admin/grptags",
 *  methods = {"POST"},
 *  defaults  = {
 *      "__controller"="App\Controller\UserController::createGroupeTags",
 *      "__api_ressource_class"=GroupeTag::class,
 *      "__api_collection_operation_name"="add_groupeTag"
 * }
 * )
 */
    public function createGroupeTags(Request $request)
    {
        $json = json_decode($request->getContent());
        //dd($json);

        //verifions s'il faut crée le groupe oubien l'affecté des tags
        if (isset($json->id)) {
            $groupeTags = $this->groupe->find($json->id);
        }else{
            $groupeTags = null;
        }
        if ($groupeTags != null) {
            //dans le cas ou groupe tags existe deja
            for ($i=0; $i < count($json->tags); $i++) {

                if (isset($json->tags[$i]->id)) {
                    //affectation la/les competences au groupe
                    $tags = $this->tagRepo->find($json->tags[$i]->id);
                    $groupeTags->addTag($tags);
                }else{
                    $tags = new Tag();
                    $tags->setLibelle($json->tags[$i]->libelle)
                        ->setDescription($json->tags[$i]->description);
                    $groupeTags->addTag($tags);
                }
            }

            //mettons a jour le bdd
            $this->em->flush();
            return $this->json('added succesfully',Response::HTTP_OK);
        }else{ //si groupe de competence n'existe on crée
            $groupeTags = new GroupeTag;
            $groupeTags->setLibelle($json->libelle);
            for ($i=0; $i < count($json->tags); $i++) {
                if (isset($json->tags[$i]->id)) {
                    //affectation de la tags
                    $tags = $this->tagRepo->find($json->tags[$i]->id);
                    $groupeTags->addTag($tags);
                }else{
                    //creation tags
                    $tags = new Tag;
                    $tags->setLibelle($json->tags[$i]->libelle)
                        ->setDescription($json->tags[$i]->description);;
                    $groupeTags->addTag($tags);
                }
            }
            // //validation groupe tags
            // $erreurs = $this->validator->validate($groupeTags);
            // if ($erreurs) {
            //     return $this->json($erreurs);
            // }
            $this->em->persist($groupeTags);
            $this->em->flush();
            return $this->json('added succesfully',Response::HTTP_OK);
        }
    }

    /**
     * @Route(
     *  name = "addDeleteGroupeTags",
     *  path = "/api/admin/grptags/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\UserController::addDeleteGroupeTags",
     *      "__api_ressource_class"=GroupeTag::class,
     *      "__api_collection_operation_name"="add_deletegroupeTag"
     * }
     * )
     */
    public  function addDeleteGroupeTags (Request $request , $id){
        $jsons = json_decode($request->getContent());
        //dd($jsons->tags);
        $grptags=$this->groupe->find($id) ;
        if($jsons->action =="add"){
            for ($i =0; $i < count($jsons->tags); $i++){
                if ($jsons->tags[$i]->id){
                    $tag = $this->tagRepo->find($jsons->tags[$i]->id);
                    if ($tag){
                        $grptags->addTag($tag);
                    }
                }else{
                    return $this->json(" l ' ID du tags n' existe pans!!!");
                }
            }
        }else{
            if($jsons->action =="delete"){
                for ($i =0; $i < count($jsons->tags); $i++){
                    if ($jsons->tags[$i]->id){
                        $tag = $this->tagRepo->find($jsons->tags[$i]->id);
                        if ($tag){
                            $grptags->removeTag($tag);
                        }
                    }else{
                        return $this->json(" l ' ID du tags n' existe pans!!!");
                    }
                }
            }
        }
        $this->em->flush();
        return $this->json(" successfully");
    }
}
