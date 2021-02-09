<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource (
 *      attributes={
 *
 *    },
 *     collectionOperations={
 *        "add_groupeCompetence"={
 *                         "path"="/admin/grpecompetences",
 *                          "method" = "POST",
 *                           "denormalization_context"={"groups"={"grpcompetence:write"}},
 *              },
 *              "GetGrroupesCompetences"={
 *                         "path"="/admin/grpecompetences",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"grpcompetence:read"}},
 *                             "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                            "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *             },
 *     "GetGrroupesCompetencesCompetences"={
 *                         "path"="/admin/grpecompetences/competences",
 *                          "method" = "GET",
 *                           "normalization_context"={"groups"={"grpcompetencecompe:read"}},
 *                              " security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                            "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *             }
 *           },

 *     itemOperations={
 *      "get_one_grpecompetence"={
 *          "normalization_context"={"groups"={"grpcompetence:read"}},
 *          "method" = "GET",
 *          "path"  = "/admin/grpecompetences/{id}"
 *      },
 *     "grpecompetences_get_subresource"={
 *          "method" = "GET",
 *          "path"  = "/admin/grpecompetences/{id}/competences",
 *           "normalization_context"={"groups"={"grpcompetencecompe:read"}},
 *         " security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *         },
 *
 *      "edit_tags"={
 *          "method" = "PUT",
 *          "path"  = "/admin/grptags/{id}"
 *      },
 *     "addDelet_groupeCompetence"={
 *                  "route_name"="addDeleteCrpCompetecne"
 *              },
 * }
 * )
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups ({"grpcompetence:read"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences",cascade={"persist"})
     * @Groups ({"grpcompetencecompe:read","grpcompetence:read","grpcompetence:write"})
     */
    private $competence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"grpcompetence:read","grpcompetencecompe:read","referentiel:read","grpcompetence:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"grpcompetence:read","grpcompetencecompe:read","referentiel:read","grpcompetence:write"})

     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Referentiels::class, mappedBy="grpeCompetence")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Referentiels[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiels $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->setGrpeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiels $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            // set the owning side to null (unless already changed)
            if ($referentiel->getGrpeCompetence() === $this) {
                $referentiel->setGrpeCompetence(null);
            }
        }

        return $this;
    }
}
