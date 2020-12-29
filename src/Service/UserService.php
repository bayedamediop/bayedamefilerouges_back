<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class UserService
{

    /**
     * @var DenormalizerInterface
     */
    private $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    private $repo;
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder, DenormalizerInterface $serializer,EntityManagerInterface $manager,ProfileRepository $repo)
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->manager=$manager;
        $this->repo=$repo;
    }

    public function Apprenant($profil, Request $request, ValidatorInterface $validator,ProfileRepository $repo)
    {
        $userTab = $request->request->all();
        $uploadedfile = $request->files->get('avatar');
        if ($uploadedfile) {
            $file = $uploadedfile->getRealPath();
            $avatar = fopen($file, 'rb');
            //dd($avatar);
            $userTab['avatar'] = $avatar;
            $userTab['isdelate'] = "0";

        }
        if ($profil == "APPRENANT") {
            $userType = Apprenant::class;
        }
        $apprenant = $this->serializer->denormalize($userTab, $userType);
        $apprenant->setProfil($repo->findOneBy(['libelle' => $profil]));
        $apprenant->setAttente('1');
        $password = $apprenant->getPassword();
        $apprenant->setPassword($this->encoder->encodePassword($apprenant, $password));

        $this->manager->persist($apprenant);;

        fclose($avatar);
    }

        public function addUsers(Request $request)
    {
            $profil ='';
        $userTab = $request->request->all();
        $uploadedfile = $request->files->get('avatar');
        if ($uploadedfile)
        {
            $file = $uploadedfile->getRealPath();
            $avatar = fopen($file, 'rb');
            $userTab['avatar'] = $avatar;
        }
        if($userTab['profiles'] == "Admin"){
            $userType = User::class;
        }elseif($userTab['profiles'] == "Formateur"){
            $userType = Formateur::class;
        }elseif($userTab['profiles'] == "Apprenant"){
            $userType = Apprenant::class;
        }elseif($userTab['profiles'] == "Cm"){
            $userType = Cm::class;
        }
        //dd($user);
        $idProfil = $this->repo->findOneBy(['libelle' =>$profil])->getId();
        $userTab['profil'] = "api/admin/profils/".$idProfil;
        $users = $this->serializer->denormalize($userTab, $userType);
        $users->setPassword($this->encoder->encodePassword($users, $userTab['password']));
        $users->setProfil($this->repo->findOneBy(['libelle' =>$profil]));
//        $error = $validator->validate($users);
//        if(count($error)>0){
//            throw new BadRequestException($error);
//        }else{

            return $users;
//        }
        fclose($avatar);

    }


    public function UpdateUser(Request $request, string $filename = null)
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
            $key = str_replace(["\r\n",'"','"'],'',$tabElements[$i]);
            //dd($key);
            if (strchr($key, $filename))
            {
                $file = fopen('php://memory', 'r+');
                fwrite($file, $tabElements[$i+1]);
                rewind($file);
                $data[$filename] = $file;
            }else {
                $val = str_replace(["\r\n",'--'], '', $tabElements[$i+1]);
                $data[$key] = $val;
            }
        }
        //dd($data);

        return $data;
    }
}