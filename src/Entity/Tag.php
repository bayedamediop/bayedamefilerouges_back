<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *  attributes={
 *          "security" = " (is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "security_message" = "vous n'avez pas accès a cette resource"
 *      },
 * collectionOperations={
 *           "get_tag"={
 *                  "method" = "GET",
 *                  "path" = "/admin/tags",
 *                  "normalization_context"={"groups"={"tag:read"}}
 *                  },
 *              "CREate_tag"={
 *                  "method" = "POST",
 *                  "path" = "/admin/tags"
 *                  },
 *
 *   },
 *     itemOperations={
 *      "get_one_tagé_By_Id"={
 *             "method"="GET",
 *             "path" = "/admin/tags/{id}",
 *             "normalization_context"={"groups"={"tag:read"}}
 *        },
 *          "edit_tag"={
 *             "method"="PUT",
 *             "path" = "/admin/tags/{id}",
 *      }
 *   }
 *  )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","grptags:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","grptags:read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tag",cascade={"persist"})
     *
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
