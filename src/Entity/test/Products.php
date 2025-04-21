<?php
// src/Entity/Products.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Brands;
use Entity\Categories;
/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 **/
class Products implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $product_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $product_name;

    /**
     * @ManyToOne(targetEntity="Entity\Brands", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id")
     */
    private Brands $brand;

    /**
     * @ManyToOne(targetEntity="Entity\Categories", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     */
    private Categories $category;

    /** @var int */
    /**
     * @ORM\Column(type="smallint")
     */
    private int $model_year;

    /** @var string */
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private string $list_price;

    /**
    * @ORM\OneToMany(targetEntity="Entity\Stocks", mappedBy="product")
    */
    private Collection $stocks;

    public function __construct(){
        $this->stocks = new ArrayCollection();
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
            if($k!="stocks")
                $s .= $k . ": " . $v . "\n";
            
        }
        return $s;
    }
    
    /**
     * Get product_id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set product_id
     *
     * @param int $product_id
     * @return products
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * Get product_name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set product_name
     *
     * @param string $product_name
     * @return products
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
        return $this;
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
     * @return products
     */
    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
        return $this;
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
     * @return products
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * Get model_year
     *
     * @return int
     */
    public function getModelYear()
    {
        return $this->model_year;
    }

    /**
     * Set model_year
     *
     * @param int $model_year
     * @return products
     */
    public function setModelYear($model_year)
    {
        $this->model_year = $model_year;
        return $this;
    }

    /**
     * Get list_price
     *
     * @return int
     */
    public function getListPrice()
    {
        return $this->list_price;
    }

    /**
     * Set list_price
     *
     * @param int $list_price
     * @return products
     */
    public function setListPrice($list_price)
    {
        $this->list_price = $list_price;
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