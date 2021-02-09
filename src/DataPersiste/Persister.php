<?php
namespace App\DataPersiste;

use App\Entity\Profile;
use App\DataPersiste\Persister;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class Persister implements ContextAwareDataPersisterInterface
{
    private $manager;
    private $user;
    public function __construct(EntityManagerInterface $manager ,UserRepository $user){
        $this->manager = $manager;
        $this->user = $user;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profile;
    }

    public function persist($data, array $context = [])
    {
       
      $data->setLibelle($data->getLibelle());
      $data->setArchive($data->getArchive(1));
      $this->manager->persist($data);
      $this->manager->flush();
      return $data;
    }

    public function remove($data, array $context = [] )
    {
        $data->setArchive(true);
        $this->manager->persist($data);
        $id = $data->getId();
        $users = $this->user->findBy(['profile'=>$id]);
        //dd($users);
        foreach ($users as $value) {
            $value->setIsdelate(1);
            $this->manager->persist($value);
            $this->manager->flush();
        }
        
        $this->manager->flush();
        return $data;
      // call your persistence layer to delete $data
    }
}