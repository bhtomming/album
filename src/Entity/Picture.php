<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Picture
{
    const SERVER_PATH_TO_IMAGE_FOLDER = 'upload/images/';

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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Album", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $album;

    private $file;

    public function __construct()
    {
        $current = new \DateTime('now');
        $this->setCreatedAt($current);
        $this->setUpdatedAt($current);
    }

    public function setFile(UploadedFile $uploadedFile = null)
    {
        $this->file = $uploadedFile;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
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


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function upload()
    {
        if($this->getFile() == null){
            return;
        }

        $name = new \DateTime('now');
        $this->getFile()->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER.date('Y',$name)."/".date('m',$name)."/".date('d',$name)."/",
            $this->getFile()->getClientOriginalName()
        );
        $this->name = $this->getFile()->getClientOriginalName();

        $this->setFile(null);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function fileUpload()
    {
        $this->upload();
    }

    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function getReview()
    {
        return "<img src=\"/".$this->getWebPath()."\" />";
    }

    public function getWebPath()
    {
        $times = $this->getCreatedAt();
        $dateDir = $times->format("Y")."/".$times->format("m")."/".$times->format("d");
        return self::SERVER_PATH_TO_IMAGE_FOLDER.$dateDir."/".$this->getName();
    }
}
