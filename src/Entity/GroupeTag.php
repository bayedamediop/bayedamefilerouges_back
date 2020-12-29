<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource (
 *     attributes={
 *         "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      "security_message" = " OBBB ,vous n'avez pas accès a cette resource"
 *      },
 *     collectionOperations={
 *
 *        "add_groupeTag"={
 *                  "route_name"="createGroupeTags",
 *              },
 *     "getGroupeTags"={
 *                  "method" = "GET",
 *                  "path" = "/admin/grptags",
 *                  "normalization_context"={"groups"={"grptags:read"}}
 *                }
 *   },
 *     subresourceOperations={
 *     "tags_get_subresource"={
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}/tags"
 *      }
 *      },
 *     itemOperations={
 *      "get_one_grpe_tags"={
 *          "normalization_context"={"groups"={"grptags:read"}},
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}"
 *      },
 *      "edit_tags"={
 *          "method" = "PUT",
 *          "path"  = "/admin/grptags/{id}"
 *      },
 *     "add_deletegroupeTag"={
 *     "route_name"="addDeleteGroupeTags",
 *      }
 * }
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grptags:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags" ,cascade={"persist"})
     * @ApiSubresource()
     * @Groups({"grptags:read"})
     */
    private $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }
}
