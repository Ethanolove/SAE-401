<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="stocks")
 */

class Stocks implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $stock_id;
    /**
     * @ORM\Column(type="integer")
     */

    /** @var Stores */
    /**
     * @ManyToOne(targetEntity="Entity\Stores", inversedBy="employees", cascade={"persist"})
     * @JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private $store;
    /** @var Products */
    /**
     * @ManyToOne(targetEntity="Entity\Products",inversedBy="stocks",cascade={"persist"})
     * @JoinColumn(name="product_id", referencedColumnName="product_id")
     * 
     */
    private $product;
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;
    public function __construct(array $t = [])
    {
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
     * Get the store ID.
     *
     * @return int
     */
    public function getStockId()
    {
        return $this->stock_id;
    }

    /**
     * Set the store ID.
     *
     * @param int $storeId
     */
    public function setStock_id(int $stock_id)
    {
        $this->stock_id = $stock_id;
    }

    /**
     * Get the store.
     *
     * @return Stores
     */
    public function getStore(): Stores
    {
        return $this->store;
    }



    /**
     * Get the product.
     *
     * @return Products
     */
    public function getProduct()
    {
        return $this->product;
    }



    /**
     * Get the quantity.
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantity;
    }
    public function setStockId(int $stock_id)
    {
        $this->stock_id = $stock_id;
    }

    public function setStore(Stores $store)
    {
        $this->store = $store;
    }

    public function setProduct(Products $product)
    {
        $this->product = $product;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
    /**
     * Set the quantity.
     *
     * @param int $quantite
     */
    public function setQuantite(int $quantity)
    {
        $this->quantity = $quantity;
    }
    public function jsonSerialize(): mixed
    {
        return [
            'stock_id' => $this->getStockId(),
            'store_id' => $this->getStore()->getStoreId(),  // Récupère seulement l'ID du magasin
            'product_id' => $this->getProduct()->getProductId(),  // Récupère seulement l'ID du produit
            'quantity' => $this->getQuantite(),
        ];
    }
}
