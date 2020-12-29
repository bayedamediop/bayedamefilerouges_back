<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=GroupesRepository::class)
 * @ApiResource(
 *attributes={
 *         "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *      },
 *     collectionOperations={
 *
 *        "Getpromos"={
 *                         "path"="/admin/promos",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"promoRefForGroupe:read"}},
 *                      },
 *         "GetApprenant"={
 *                         "path"="/admin/groupes/apprenants",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"getapprenant:read"}},
 *                      }
 *        },
 *     itemOperations={
 *           "get_groupes_id"={
 *                   "method"="GET",
 *                    "path" = "/admin/groupes/{id}",
 *                    "normalization_context"={"groups"={"groupes:read"}}
 *      },
 *     "ajoutapprenant"={
 *                   "method"="GET",
 *                    "path" = "/admin/groupes/{id}",
 *                    "normalization_context"={"groups"={"groupes:read"}}
 *      },
 *
 *     "add_apprenant"={
 *                  "route_name"="addApprenantDansUnGroup"
 *              },
 *
 *     "delete_apprenant"={
 *                  "route_name"="deleteApprenantDansUnGroup"
 *           }
 *         }
 * )
 */
class Groupes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","groupes:read","refgrpcompetence:read"})
     * @Groups ({"refgrpcompetence:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","refgrpcompetence:read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="groupes",cascade={"persist"}))

     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="groupes",cascade={"persist"})
     * @ApiSubresource()
     * @Groups ({"apprenantsAttante:read","promoRefForGroupe:read","getapprenant:read"})
     */

    private $apprenants;

    /**
     * @ORM\Column(type="date")
     */
    private $DateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="groupes")
     * @ApiSubresource
     * @Groups ({"promoRefForGroupe:read","formateur:read"})
     *
     */
    private $formateurs;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addGroupe($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            $apprenant->removeGroupe($this);
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(\DateTimeInterface $DateCreation): self
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addGroupe($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->removeElement($formateur)) {
            $formateur->removeGroupe($this);
        }

        return $this;
    }

}
