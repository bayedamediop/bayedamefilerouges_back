<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CompetenceValidesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=CompetenceValidesRepository::class)
 *  @ApiResource(
 *     collectionOperations={
 *
 *      "competence"={
 *                  "route_name"="addCompetVailder"
 *              },
 *       },
 * )
 */
class CompetenceValides
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="competenceValides")
     */
    private $aprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class, inversedBy="competenceValides",cascade={"persist"})
     * @Groups ({"addreferentiel:read"})
     * @ApiSubresource
     */
    private $referentiel;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="competenceValides",cascade={"persist"})
     */
    private $promo;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="competenceValides",cascade={"persist"})
     * @ApiSubresource
     * @Groups ({"referentiel:read"})
     */
    private $competence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $niveau3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAprenant(): ?Apprenant
    {
        return $this->aprenant;
    }

    public function setAprenant(?Apprenant $aprenant): self
    {
        $this->aprenant = $aprenant;

        return $this;
    }

    public function getReferentiel(): ?Referentiels
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiels $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    public function getPromo(): ?Promos
    {
        return $this->promo;
    }

    public function setPromo(?Promos $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getNiveau1(): ?string
    {
        return $this->niveau1;
    }

    public function setNiveau1(string $niveau1): self
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    public function getNiveau2(): ?string
    {
        return $this->niveau2;
    }

    public function setNiveau2(string $niveau2): self
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    public function getNiveau3(): ?string
    {
        return $this->niveau3;
    }

    public function setNiveau3(string $niveau3): self
    {
        $this->niveau3 = $niveau3;

        return $this;
    }
}
