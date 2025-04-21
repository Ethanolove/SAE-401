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
 * @ORM\Table(name="products")
 */
class Products implements \JsonSerializable
{
    /** 
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
    private $product_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */

    private $product_name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $model_year;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */

    private $list_price;


    /** @var Categories */
    /**
     * @ManyToOne(targetEntity="Entity\Categories",inversedBy="products",cascade={"persist"})
     * @JoinColumn(name="category_id", referencedColumnName="category_id")
     */
    private $categories;
    /** @var Brands */
    /**
     * @ManyToOne(targetEntity="Entity\Brands",inversedBy="products",cascade={"persist"})
     * @JoinColumn(name="brand_id", referencedColumnName="brand_id")
     */
    private $brands;

    /**
     * @OneToMany(targetEntity="Entity\Stocks", mappedBy="product")
     */
    private Collection $stocks;

  

    public function __construct(array $t = [])
    {
        $this->stocks = new ArrayCollection();
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
     * Get the product ID.
     *
     * @return int|null
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set the product ID.
     *
     * @param int|null $product_id
     * @return void
     */
    public function setProductId(?int $product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * Get the product name.
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    /**
     * Set the product name.
     *
     * @param string|null $product_name
     * @return void
     */
    public function setProductName(?string $product_name)
    {
        $this->product_name = $product_name;
    }

    /**
     * Get the model year.
     *
     * @return int|null
     */
    public function getModelYear()
    {
        return $this->model_year;
    }

    /**
     * Set the model year.
     *
     * @param int|null $model_year
     * @return void
     */
    public function setModelYear(?int $model_year)
    {
        $this->model_year = $model_year;
    }

    /**
     * Get the list price.
     *
     * @return float|null
     */
    public function getListPrice()
    {
        return $this->list_price;
    }

    /**
     * Set the list price.
     *
     * @param float|null $list_price
     * @return void
     */
    public function setListPrice(?float $list_price)
    {
        $this->list_price = $list_price;
    }

    /**
     * Get the category.
     *
     * @return Category|null
     */
    public function getCategories()
    {
        return $this->categories;
    }
    public function setCategories(Categories $categories): void
    {
        $this->categories = $categories;
    }
  /**
     * Get the stocks.
     *
     * @return Collection
     */
    public function getStocks()
    {
        return $this->stocks;
    }
    /**
     * Get the brand.
     *
     * @return Brand|null
     */
    public function getBrand()
    {
        return $this->brands;
    }
    public function setBrands(Brands $brands): void
    {
        $this->brands = $brands;
    }
    public function jsonSerialize(): mixed
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'model_year' => $this->model_year,
            'list_price' => $this->list_price,
            'category' => $this->categories,
            'brand' => $this->brands
        ];
    }
  
}
