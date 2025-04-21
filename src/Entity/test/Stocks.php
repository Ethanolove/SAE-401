<?php
// src/Entity/Stocks.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Stores;
use Entity\Products;
/**
 * @ORM\Entity
 * @ORM\Table(name="stocks")
 **/
class Stocks implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $stock_id;

    /** @var store */
    /**
     * @ManyToOne(targetEntity="Entity\Stores", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private stores $store;

    /** @var products */
    /**
     * @ManyToOne(targetEntity="Entity\Products", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     */
    private products $product;

    /** @var int */
    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    public function __construct(){
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
            $s .= $k . ": " . $v . "\n";
        }
        return $s;
    }
    
    /**
     * Get stock_id
     *
     * @return int
     */
    public function getStockId()
    {
        return $this->stock_id;
    }

    /**
     * Set stock_id
     *
     * @param int $stock_id
     * @return stocks
     */
    public function setStockId($stock_id)
    {
        $this->stock_id = $stock_id;
        return $this;
    }

    /**
     * Get store_id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * Set store_id
     *
     * @param int $store_id
     * @return stocks
     */
    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
        return $this;
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
     * @return stocks
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     * @return stocks
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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