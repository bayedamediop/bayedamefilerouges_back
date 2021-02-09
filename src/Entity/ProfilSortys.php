<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortysRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilSortysRepository::class)
 * @ApiResource(
 *      attributes={
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "vous n'avez pas accès a cette resource"
 *          
 *      },
 *      collectionOperations={
 *          "get"={
 *                  "method" = "GET",
 *                  "path" = "/admin/profilsortys",
 *                  "normalization_context"={"groups"={"profilsortys:read"}}
 *                  },
 *           "post"={
 *                  "method" = "POST",
 *                  "path" = "/admin/profilsortys",
 *                  },
 *          
 *      },
 *  itemOperations={
 *           "get_profilsortys_by_id"={
 *                   "method"="GET",
 *                    "path" = "/admin/profilsortys/{id}",
 *                    "normalization_context"={"groups"={"profilsortys:read"}},
 *      },
 * "put_profilsortys_by_id"={
 *                   "method"="PUT",
 *                    "path" = "/admin/profilsortys/{id}",
 *                    
 *      },
 * "delete_profilsortys_by_id"={
 *                   "method"="DELETE",
 *                    "path" = "/admin/profilsortys/{id}",
 *                    
 *      },
 * }
 * )
 */
class ProfilSortys
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profilsortys:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profilsortys:read"})
     */
    private $libelle;

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
}
