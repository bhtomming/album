<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CrawlerCacheRepository")
 */
class CrawlerCache
{
    const FRONT = 0;

    const LIST = 1;

    const CATEGORY = 2;

    const TAGS = 3;

    const CONTENT = 4;

    const FILE = 5;

    const PAGE = 6;

    const NAV = 7;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $task;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $urlType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gather;

    public function __construct()
    {
        $this->setGather(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlType(): ?string
    {
        return $this->urlType;
    }

    public function setUrlType(string $urlType): self
    {
        $this->urlType = $urlType;

        return $this;
    }

    public function getGather(): ?bool
    {
        return $this->gather;
    }

    public function setGather(bool $gather): self
    {
        $this->gather = $gather;

        return $this;
    }
}
