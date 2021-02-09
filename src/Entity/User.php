<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Entity\Profile;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 *  @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name = "type", type = "string")
     * @ORM\DiscriminatorMap({"formateur"="Formateur","cm"= "Cm", "apprenant"="Apprenant", "admin"="User"})
 * @ApiResource(
 *    
 *      attributes={
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "vous n'avez pas accès a cette resource"
 *          
 *      },
 *      collectionOperations={
 *          "get"={
 *                  "method" = "GET",
 *                  "path" = "/admin/users",
 *                  "normalization_context"={"groups"={"user:read"}}
 *                  },
 *          "add/home/diopkoki/myAngular/home/diopkoki/myAngular/home/diopkoki/myAngular_users"={
 *                  "route_name"="addUser",
 *              }
 *      },
 *  itemOperations={
 *           "get_user_by_id"={
 *                   "method"="GET",
 *                    "path" = "/admin/users/{id}",
 *                    "normalization_context"={"groups"={"user:read"}},
 *      },
 *            "delete"={
 *                      "method"="DELETE",
  *                    "path" = "/admin/users/{id}",
  *              },
  *      "putUserId":{
 *           "method":"put",
 *          "path":"/admin/users/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN') )",
 *              "deserialize"= false,
 *          }
 * },
 * )
 * @UniqueEntity ("email",
 *      message="Ndanidite dougalalle benénn Email bi Amne!!!!!.")
 *  @UniqueEntity(
 * fields={"email"},
 * message={"cet email est déjà utilisé"})
 * @ApiFilter(SearchFilter::class, properties={"isdelate": "exact"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"addreferentiel:read"})
     * @Groups({"user:read","profil:read","profilUser:read","apprenant:read","formateur:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L ' email doit etre unique")
     * * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     *  @Groups({"user:read","profil:read","apprenant:read","formateur:read","users:read"})
     */
    private $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true)
     *  @Assert\NotBlank()
     *  @Groups({"user:read","profil:read","apprenant:read","formateur:read","promoRefForGroupe:read","papprenantsAttante:read","getapprenant:read"})
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entre votre prenom")
     *  @Groups({"user:read","profil:read","apprenant:read","formateur:read","apprenantsAttante:read","promoRefForGroupe:read","getapprenant:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true )
     * @Assert\NotBlank(message="Veuillez entre votre un numero telephone")
     * 
     *  @Groups({"user:read","profil:read","apprenant:read","formateur:read","apprenantsAttante:read","promoRefForGroupe:read","getapprenant:read"})
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="users")
     * @Assert\NotBlank(message="Veuillez charger une image")
     *   @Groups({"user:read","apprenant:read"})
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $isdelate='1';

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Assert\NotBlank(message="Veuillez charger une image")
     * @Assert\File(mimeTypes={"image/jpeg","image/png"})
     *  @Groups({"user:read","profil:read","apprenant:read","formateur:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $attente ='0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profile->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getIsdelate(): ?string
    {
        return $this->isdelate;
    }

    public function setIsdelate(string $isdelate): self
    {
        $this->isdelate = $isdelate;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAttente(): ?float
    {
        return $this->attente;
    }

    public function setAttente(float $attente): self
    {
        $this->attente = $attente;

        return $this;
    }
}
