<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="albums")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Star", inversedBy="albums")
     * @ORM\JoinColumn(nullable=true)
     */
    private $star;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="album", orphanRemoval=true,cascade={"persist"})
     */
    private $pictures;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuItem", inversedBy="mega",cascade={"persist"})
     */
    private $menuItem;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $viewed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tags", mappedBy="albums")
     */
    private $tags;



    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->setCreatedAt(new \DateTime('now'));
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStar(): ?Star
    {
        return $this->star;
    }

    public function setStar(?Star $star): self
    {
        $this->star = $star;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {

        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAlbum($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getAlbum() === $this) {
                $picture->setAlbum(null);
            }
        }

        return $this;
    }

    public function getCounterPictures()
    {
        return $this->pictures->count();
    }

    public function getMenuItem(): ?MenuItem
    {
        return $this->menuItem;
    }

    public function setMenuItem(?MenuItem $menuItem): self
    {
        $this->menuItem = $menuItem;

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

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getViewed(): ?int
    {
        return $this->viewed;
    }

    public function setViewed(?int $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function addViewed()
    {
        $this->viewed++;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        if($isPublished)
        {
            $this->setPublishedAt(new \DateTime('now'));
        }

        return $this;
    }

    public function getPicPath()
    {
        $pic = $this->getPictures()->get(random_int(0,$this->getPictures()->count()-1));
        $times = $pic->getCreatedAt();
        $dateDir = $times->format("Y")."/".$times->format("m")."/".$times->format("d");
        return Picture::SERVER_PATH_TO_IMAGE_FOLDER.$dateDir."/".$pic->getName();
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addAlbum($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeAlbum($this);
        }

        return $this;
    }






}
