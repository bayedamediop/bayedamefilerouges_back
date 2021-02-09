<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
  *       "add_referentiel"={
 *                   "method" = "POST",
 *                  "route_name"="creatReferentiels"
 *              },
 *        "get_referentiel"={
 *                         "path"="/admin/referentiels",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"ref:read"}},
 *                      },
 * "get_referentielgrpcompetence"={
 *                         "path"="/admin/referentieles",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"referentiel:read"}},
 *                      },
 *     "get_referentielroupsompetencecompetence"={
 *                         "path"="/admin/referentiels/grpecompetences",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"refgrpcompetence:read"}},
 *                      },
 *           },
 *     itemOperations={
 *             "Getreferentiels_Id_group_competence"={
 *                         "path"="/admin/referentieles/{id}",
 *                          "method" = "GET",
 *                            "normalization_context"={"groups"={"referentiel:read"}}
 *                      },
 *                      "edit_referentiel"={
 *                         "route_name"="editReferentiels"
 *                       },
 *                 }
 * )
 * @ORM\Entity(repositoryClass=ReferentielsRepository::class)
 */
class Referentiels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"addreferentiel:read","ref:read","referentiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","referentiel:read","ref:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","referentiel:read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups ({"promoRefForGroupe:read","referentiel:read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","referentiel:read"})
     */
    private $evaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promoRefForGroupe:read","referentiel:read"})
     */
    private $admission;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="referentiels",cascade={"persist"}))
     * @Groups ({"refgrpcompetence:read"})
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValides::class, mappedBy="referentiel")
     *  @Groups ({"referentiel:read"})
     */
    private $competenceValides;

    /**
     * @ORM\ManyToOne(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups ({"referentiel:read"})
     */
    private $grpeCompetence;
    
    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
        $this->competences = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        $programme = $this->programme;
        if ($programme) {
            return (base64_encode(stream_get_contents($this->programme)));
        }
        return $programme;
    }

    public function setProgramme( $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getEvaluation(): ?string
    {
        return $this->evaluation;
    }

    public function setEvaluation(string $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function getAdmission(): ?string
    {
        return $this->admission;
    }

    public function setAdmission(string $admission): self
    {
        $this->admission = $admission;

        return $this;
    }

    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiels($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiels() === $this) {
                $promo->setReferentiels(null);
            }
        }

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
            $competenceValide->setReferentiel($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValides $competenceValide): self
    {
        if ($this->competenceValides->removeElement($competenceValide)) {
            // set the owning side to null (unless already changed)
            if ($competenceValide->getReferentiel() === $this) {
                $competenceValide->setReferentiel(null);
            }
        }

        return $this;
    }

    public function getGrpeCompetence(): ?GroupeCompetence
    {
        return $this->grpeCompetence;
    }

    public function setGrpeCompetence(?GroupeCompetence $grpeCompetence): self
    {
        $this->grpeCompetence = $grpeCompetence;

        return $this;
    }

}
