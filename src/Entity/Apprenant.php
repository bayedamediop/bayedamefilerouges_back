<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *  attributes={
 *          "security" = "is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message" = "vous n'avez pas accès a cette resource"
 *          
 *      },
 * collectionOperations={
 *      "list_Apprenants"={
 *           "method" = "GET",
 *            "path" = "/apprenants",
 *          "normalization_context"={"groups"={"apprenant:read"}},
 *         
 *      },
 * },
 * itemOperations={
 *           "get_apprenant_by_id"={
 *                   "method"="GET",
 *                    "path" = "/apprenants/{id}",
 *                    "normalization_context"={"groups"={"apprenant:read"}},
 *                   "security" = "(is_granted('ROLE_APPRENANT') or is_granted('ROLE_FORMATEUR')  or is_granted('ROLE_CM'))",
 *      },
 *  "edit_apprenant_by_id"={
 *                   "method"="PUT",
 *                    "path" = "/apprenants/{id}",
 *                   "security" = " (is_granted('ROLE_FORMATEUR'))",
 *                 },
 *     "apprenants"={
 *        "method"="GET",
 *          "path" = "/apprenants/{id}",
 *         "put"={"security_post_denormalize"="is_granted('ROLE_APPRENANT') or (object.owner == Apprenant and previous_object.owner == Apprenant)"},
 *     }            
 *      },
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="apprenants",cascade={"persist"})
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValides::class, mappedBy="aprenant")
     */
    private $competenceValides;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="apprenant")
     */
    private $promos;


    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
        $this->promos = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        $this->groupes->removeElement($groupe);

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
            $competenceValide->setAprenant($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValides $competenceValide): self
    {
        if ($this->competenceValides->removeElement($competenceValide)) {
            // set the owning side to null (unless already changed)
            if ($competenceValide->getAprenant() === $this) {
                $competenceValide->setAprenant(null);
            }
        }

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
            $promo->setApprenant($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getApprenant() === $this) {
                $promo->setApprenant(null);
            }
        }

        return $this;
    }


}
