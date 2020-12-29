<?php
namespace App\Service;


use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;

use App\Entity\User;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PostService

{
    /**
     * @var SerializerInterface
     */
    private $serialize;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    private $encoder;
    private $profileRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PostController constructor.
     */
    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, ProfileRepository $profileRepository, UserPasswordEncoderInterface $encoder )
    {
        $this->serialize = $serializer ;
        $this->validator = $validator ;
        $this->encoder = $encoder ;
        $this->em = $em ;
        $this->profileRepository = $profileRepository ;
    }
    public function createUser(Request $request,$profil)
    {
        $user = $request->request->all();
        //dd($user);
        $avatar = $request->files->get("avatar");
        if ($avatar){
            $file = $avatar->getRealPath();
            $avatar=fopen($file,"r+");
            $user['avatar'] = $avatar;
        }

        //  $users = new User;
        //  dd($user);
        if ($profil == "CM") {
            $type = CM::class;}
        elseif ($profil == "FORMATEUR") {
            $type = Formateur::class;
        }
        elseif ($profil == "APPRENANT") {
            $type = Apprenant::class;}

        else
        {
            $type = User::class;
        }
        $users = $this->serialize->denormalize($user,$type,true);
        $users->setAvatar($avatar);
       //dd($users);
         //encode password
        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serialize->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        // $password = $users->getPassword();
        //$users->setPassword($this->encoder->encodePassword($users,$user['password']));
        return $user ;
    }

    /**
     * put image of user
     * @param Request $request
     * @param string|null $fileName
     * @return array
     */
   public function PutUser(Request $request, string $fileName = null)
{
    $row = $request->getContent();
    $delimitor = "multipart/form-data; boundary=";
    $boundary = "--".explode($delimitor, $request->headers->get("content-type"))[1];
    $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$row);
    //dd($elements);
    $tabElements = explode("\r\n\r\n", $elements);
    //dd($tabElements);
    $data = [];

    for ($i = 0; isset($tabElements[$i+1]); $i++)
    {
        $key = str_replace(["\r\n",' "','"'],'',$tabElements[$i]);
        //dd($key);
        if (strchr($key, $fileName))
        {
            $file = fopen('php://memory', 'r+');
            fwrite($file, $tabElements[$i+1]);
            rewind($file);
            $data[$fileName] = $file;
        }else {
            $val = str_replace(["\r\n",'--'], '', $tabElements[$i+1]);
            $data[$key] = $val;
        }
    }
   //dd($data);
    return $data;
}
}

?>