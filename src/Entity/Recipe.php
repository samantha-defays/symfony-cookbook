<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ApiResource
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"recipe", "category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recipe", "category"})
     * @Assert\NotBlank(message="Le nom de la recette est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom de la recette ne peut pas faire moins de 3 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"recipe", "category"})
     * @Assert\NotBlank(message="La description est obligatoire")
     * @Assert\Length(min=10, minMessage="La description ne peut pas faire moins de 10 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"recipe", "category"})
     * @Assert\NotBlank(message="La liste des ingrédients est nécessaire")
     * @Assert\Length(min=10, minMessage="La description ne peut pas faire moins de 10 caractères")
     */
    private $ingredients;

    /**
     * @ORM\Column(type="text")
     * @Groups({"recipe", "category"})
     * @Assert\NotBlank(message="Le détail de la recette est obligatoire")
     * @Assert\Length(min=10, minMessage="Une recette qui ferait moins de 10 caractères ne serait pas vraiment une recette, n'est-ce pas ?")
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"recipe", "category"})
     * @Assert\Length(min=0, minMessage="Le temps de préparation ne peut pas être inférieur à zéro minutes")
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"recipe", "category"})
     * @Assert\Length(min=0, minMessage="Le temps de cuisson ne peut pas être inférieur à zéro minutes")
     */
    private $cookingTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"recipe", "category"})
     */
    private $utensils;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"recipe", "category"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"recipe", "category"})
     * @Assert\Url(message="L'URL entré n'est pas valide")
     */
    private $illustration;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @Groups({"recipe", "category"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="recipes")
     * @Groups({"recipe"})
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(int $cookingTime): self
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getUtensils(): ?string
    {
        return $this->utensils;
    }

    public function setUtensils(?string $utensils): self
    {
        $this->utensils = $utensils;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
