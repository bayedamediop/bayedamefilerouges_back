<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *  itemOperations={
 *              "get_formateur_by_id"={
 *                   "method"="GET",
 *                    "path" = "/formateurs/{id}",
 *                    "normalization_context"={"groups"={"formateur:read"}},
 *                   "security" = " (is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                 },
 *                "edit_formateur_by_id"={
 *                   "method"="PUT",
 *                    "path" = "/formateurs/{id}",
 *                    
 *                   "security" = " (is_granted('ROLE_FORMATEUR'))",
 *                 }
 *      }
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="formateurs")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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
}
