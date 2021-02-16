<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfileRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * 
 *  @ApiResource(
 *  attributes={
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "vous n'avez pas accès a cette resource"
 *          
 *      },
 * subresourceOperations={
 *   
 * },
 * collectionOperations={
 *           "get_profils"={
 *                  "method" = "GET",
 *                  "path" = "/admin/profils",
 *                  "normalization_context"={"groups"={"users:read"}}
 *                  },
 *          "creer_un_profils"={
 *             "method"= "POST",
 *             "path" = "/admin/profils",
 *          },
 *  
 *   },
 *  itemOperations={
 *  "users_get_subresource"= {
 *               "normalization_context"={"groups"={"profil:read"}},
 *               "method"= "GET",
 *                "path" = "/admin/profils/{id}/users",
 *        },
 *      "get_one_profile"={
 *             "method"="GET",
 *             "path" = "/admin/profils/{id}",
 *             "normalization_context"={"groups"={"users:read"}}
 *        },
 *          "edit_profil"={
 *             "method"="PUT",
 *             "path" = "/admin/profils/{id}",
 *      },
 *        "delete_profil"={
 *             "method"="DELETE",
 *             "path" = "/admin/profils/{id}"
 *          } ,
 *   }
 * )
 * @UniqueEntity ("libelle",
 *      message="Ndanidite dougalalle benénn bi Amne!!!!!."),
 * @ApiFilter(SearchFilter::class, properties={"archive": "exact"})
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
      * @Groups({"users:read","profille:read","users:read"})
     */
   private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entre votre prenom")
     * @Groups({"user:read","profille:read","users:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profile")
     * @ApiSubresource
     * @Groups({"profil:read","profilUser:read"})
     */
    private $users;

    /**
     * @ORM\Column(type="integer")
     */
    private $archive= false;

    public function __construct()
    {
        $this->users = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfile($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfile() === $this) {
                $user->setProfile(null);
            }
        }

        return $this;
    }

    public function getArchive(): ?string
    {
        return $this->archive;
    }

    public function setArchive(string $archive): self
    {
        $this->archive = $archive;

        return $this;
    }
}
