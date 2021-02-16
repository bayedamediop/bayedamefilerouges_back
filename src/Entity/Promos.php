<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\PromosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 *
 * @ORM\Entity(repositoryClass=PromosRepository::class)
 *  @ApiResource(
 *      attributes={
 *         "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *      },
 *     collectionOperations={
 *        "add_promo"={
 *                  "route_name"="createPromos"
 *              },
 *        "Getpromos"={
 *                         "path"="/admin/promos",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"promoRefForGroupe:read"}},
 *                      },
 *     "apprenantsAttantes"={
 *                  "method" = "GET",
 *                  "path" = "/admin/promo/apprenants/attente",
 *                  "normalization_context"={"groups"={"apprenantsAttante:read"}}
 *                  },
 *        },
 *     itemOperations={
 *             "GetpromosID"={
 *                         "path"="/admin/promos/{id}",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"promoRefForGroupe:read"}},
 *                      },
 *             "GetpromosIdPrincipal"={
 *                         "path"="/admin/promos/{id}/principal",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"promoRefForGroupe:read"}},
 *                      },
 *     "promoApprenantsAttantes"={
 *                  "method" = "GET",
 *                  "path" = "/admin/promo/{id}/apprenants/attente",
 *                  "normalization_context"={"groups"={"apprenantsAttante:read"}}
 *                  },
 *     "promoIdGroupeIdApprenants"={
 *                  "method" = "GET",
 *                  "path" = "/admin/promo/{idp}/groupes/{idg}/apprenants",
 *                  "normalization_context"={"groups"={"apprenantsAttante:read"}}
 *                  },
 *     "GetpromosFormateurID"={
 *                         "path"="/admin/promos/{id}/formateur",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"formateur:read"}},
 *                      },
 *     "edit_promreref"={
 *                  "route_name"="editpromoreferentiel"
 *              },
 *        }
 * )

 */
class Promos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"addreferentiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $dateFinProvisoire;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Groupes::class, mappedBy="promos",cascade={"persist"})))
     * @ApiSubresource
     * @Groups ({"promoRefForGroupe:read","apprenantsAttante:read","formateur:read","refgrpcompetence:read"})
     *
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class, inversedBy="promos",cascade={"persist"}))
     * @ApiSubresource()
     * @Groups ({"promoRefForGroupe:read"})
     */
    private $referentiels;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinReel;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValides::class, mappedBy="promo")
     */
    private $competenceValides;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="promos")
     */
    private $apprenant;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getAvatar()
    {
        $avatar = $this->avatar;
        if ($avatar) {
            return (base64_encode(stream_get_contents($this->avatar)));
        }
        return $avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }

    public function getReferentiels(): ?Referentiels
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiels $referentiels): self
    {
        $this->referentiels = $referentiels;

        return $this;
    }

    public function getDateFinReel(): ?\DateTimeInterface
    {
        return $this->dateFinReel;
    }

    public function setDateFinReel(\DateTimeInterface $dateFinReel): self
    {
        $this->dateFinReel = $dateFinReel;

        return $this;
    }

    /**
     * @return Collection|CompetenceValides[]
     */
    public function getCompetenceValides(): Collection
    {
        return $this->competenceValides;
    }

    public function addCompetenceValide(CompetenceValides $competenceValide): self
    {
        if (!$this->competenceValides->contains($competenceValide)) {
            $this->competenceValides[] = $competenceValide;
            $competenceValide->setPromo($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValides $competenceValide): self
    {
        if ($this->competenceValides->removeElement($competenceValide)) {
            // set the owning side to null (unless already changed)
            if ($competenceValide->getPromo() === $this) {
                $competenceValide->setPromo(null);
            }
        }

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

   
}
