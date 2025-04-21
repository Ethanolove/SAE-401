<?php
// src/Entity/Brands.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Products;
/**
 * @ORM\Entity
 * @ORM\Table(name="brands")
 **/
class Brands implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $brand_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $brand_name;

    /**
     * @ORM\OneToMany(targetEntity="Entity\Products", mappedBy="brand")
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
     * Get brand_id
     *
     * @return int
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * Set brand_id
     *
     * @param int $brand_id
     * @return brands
     */
    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
        return $this;
    }

    /**
     * Get brand_name
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * Set brand_name
     *
     * @param string $brand_name
     * @return brands
     */
    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
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