<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Settings
{
    const SITE_IMAGES = "images";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siteName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $keywords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $copyRight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $beian;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $footerImg;

    private $logoFile;

    private $footerFile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    public function setSiteName(string $siteName): self
    {
        $this->siteName = $siteName;

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

    public function getCopyRight(): ?string
    {
        return $this->copyRight;
    }

    public function setCopyRight(?string $copyRight): self
    {
        $this->copyRight = $copyRight;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBeian(): ?string
    {
        return $this->beian;
    }

    public function setBeian(?string $beian): self
    {
        $this->beian = $beian;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFooterImg(): ?string
    {
        return $this->footerImg;
    }

    public function setFooterImg(?string $footerImg): self
    {
        $this->footerImg = $footerImg;

        return $this;
    }

    public function setLogoFile(UploadedFile $file = null): self
    {
        $this->logoFile = $file;
        return $this;
    }

    public function getLogoFile(): ?UploadedFile
    {
        return $this->logoFile;
    }

    public function setFooterFile(UploadedFile $uploadedFile = null): self
    {
        $this->footerFile = $uploadedFile;

        return $this;
    }

    public function getFooterFile(): ?UploadedFile
    {
        return $this->footerFile;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function upload()
    {
        $this->uploadFile("logoFile","logo");
        $this->uploadFile("footerFile","footerImg");

    }

    public function uploadFile($file,$name)
    {
        $getter = "get".ucfirst($file);
        $setter = "set".ucfirst($file);
        if($this->$getter() == null)
        {
            return;
        }
        $this->$getter()->move(
            self::SITE_IMAGES,
            $this->$getter()->getClientOriginalName()
        );
        $this->$name = $this->$getter()->getClientOriginalName();
        $this->$setter(null);
    }

    public function getLogoSrc()
    {
        return "/".self::SITE_IMAGES."/".$this->logo;
    }

    public function getFooterSrc()
    {
        return "/".self::SITE_IMAGES."/".$this->footerImg;
    }
}
