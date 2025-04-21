<?php
namespace Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

use JsonSerializable;


/**
 * @ORM\Entity
 * @ORM\Table(name="brands")
 */
class Brands implements \JsonSerializable{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $brand_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $brand_name;

    /**
     * @OneToMany(targetEntity="Entity\Products", mappedBy="brands")
     * @var Collection
     */
    private Collection $products;
    /**
     * Get the products.
     *
     * @return Collection
     */
   
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
     * Get the brand ID.
     *
     * @return int|null
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * Set the brand ID.
     *
     * @param int $brandId
     */
    public function setBrandId(int $brandId)
    {
        $this->brand_id = $brandId;
    }

    /**
     * Get the brand name.
     *
     * @return string|null
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * Set the brand name.
     *
     * @param string $brandName
     */
    public function setBrandName(string $brandName)
    {
        $this->brand_name = $brandName;
    }

    /**
     * Get the products associated with this brand.
     *
     * @return Collection The collection of products.
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
    public function jsonSerialize() :mixed
    {
        return [
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name
        ];
    }
}

?>