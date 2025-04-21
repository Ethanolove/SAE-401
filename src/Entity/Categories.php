<?php

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Categories implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $category_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $category_name;
    /**
    * @OneToMany(targetEntity="Entity\Products", mappedBy="categories")
    * @var Collection
    */
    private Collection $products;

    public function __construct(array $t = [])
    {
        $this->products = new ArrayCollection();
        if (!empty($t)) {
            foreach ($t as $k => $v) {
                $this->$k = $v;
            }
        }
    }
    public function __toString()
    {
        $s = "";
        foreach ($this as $k => $v) {
            $s .= $k . " : " . $v . "\n";
        }
        return $s;
    }
    /**
     * Set the category ID.
     *
     * @param int $category_id The category ID.
     */
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the category ID.
     *
     * @return int The category ID.
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * Set the category name.
     *
     * @param string $category_name The category name.
     */
    public function setCategoryName(string $category_name)
    {
        $this->category_name = $category_name;
    }

    /**
     * Get the category name.
     *
     * @return string The category name.
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }
    /**
     * Get the products.
     *
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
    public function jsonSerialize(): mixed
    {
        return ["category_id" => $this->category_id, "category_name" => $this->category_name];
    }
}
