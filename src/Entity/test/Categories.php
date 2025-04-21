<?php
// src/Entity/Categories.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Products;
/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 **/
class Categories implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $category_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $category_name;

    /**
     * @ORM\OneToMany(targetEntity="Entity\Products", mappedBy="category")
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        if (empty($t)) {
            foreach ($t as $k => $v ) {
                $this->$k = $v;
            }
        }
    }
    public function __tostring()
    {
        $s = "";
        foreach ($this as $k => $v) {
            if ($k != "products") {
                $s .= $k . ": " . $v . ", ";
            }
        }
        return $s;
    }
    
    /**
     * Get category_id
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category_id
     *
     * @param int $category_id
     * @return categories
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * Get category_name
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * Set category_name
     *
     * @param string $category_name
     * @return categories
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
        return $this;
    }
    public function jsonSerialize()
    {
        $res = array();
        foreach ($this as $k => $v) {
            $res[$k] = $v;
        }
        return $res;
    }
}