<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuItemRepository")
 */
class MenuItem
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
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuItem", inversedBy="menuItems")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuItem", mappedBy="parent")
     */
    private $menuItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="menuItem",cascade={"persist"})
     */
    private $mega;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", inversedBy="Items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $menu;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasMega;

    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
        $this->mega = new ArrayCollection();
        $this->setHasMega(false);
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(self $menuItem): self
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems[] = $menuItem;
            $menuItem->setParent($this);
        }

        return $this;
    }

    public function removeMenuItem(self $menuItem): self
    {
        if ($this->menuItems->contains($menuItem)) {
            $this->menuItems->removeElement($menuItem);
            // set the owning side to null (unless already changed)
            if ($menuItem->getParent() === $this) {
                $menuItem->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getMega(): Collection
    {
        return $this->mega;
    }

    /**
     * @param Album $mega
     * @return MenuItem
     * 这是为了管理使用的
     */
    public function addMegon(Album $mega): self
    {
        $this->addMega($mega);
    }

    public function getMegon(): Collection
    {
        return $this->getMega();
    }

    public function removeMegon(Album $mega): self
    {
        return $this->removeMega($mega);
    }

    public function addMega(Album $mega): self
    {
        $this->setHasMega(true);
        if (!$this->mega->contains($mega)) {
            $this->mega[] = $mega;
            $mega->setMenuItem($this);
        }

        return $this;
    }

    public function removeMega(Album $mega): self
    {
        if ($this->mega->contains($mega)) {
            $this->mega->removeElement($mega);
            // set the owning side to null (unless already changed)
            if ($mega->getMenuItem() === $this) {
                $mega->setMenuItem(null);
            }
        }

        $this->setHasMega(!$this->mega->isEmpty());

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getHasMega(): ?bool
    {
        foreach ($this->menuItems as $item)
        {
            if($item->getHasMega()){
                $this->hasMega = true;
            }
        }

        return $this->hasMega;
    }

    public function setHasMega(?bool $hasMega): self
    {
        $this->hasMega = $hasMega;

        return $this;
    }
}
