<?php
namespace App\DataPersiste;

use App\Entity\User;
use App\DataPersiste\UserPersister;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class UserPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    private $user;
    public function __construct(EntityManagerInterface $manager ,UserRepository $user){
        $this->manager = $manager;
        $this->user = $user;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
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

        $data->setIsdelate('0');
        $this->manager->flush();
        return $data;
      // call your persistence layer to delete $data
    }
}
