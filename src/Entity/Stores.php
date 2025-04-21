<?php 
//Src/Entity/Stores.php
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
 * @ORM\Table(name="stores")
 */
class Stores implements \JsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $store_id;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $store_name;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $street;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $city;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $state;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $zip_code;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $phone;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $email;
   /**
    * @OneToMany(targetEntity="Entity\Employees",mappedBy="store")
    */
    private Collection $employees;

    /** 
     * @ORM\OneToMany(targetEntity="Entity\Stocks", mappedBy="store")
     */
    private Collection $stocks;

    


    public function __construct(array $t = [])
    {   
        $this->stocks = new ArrayCollection();
        $this->employees = new ArrayCollection();
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
         * @return int The store ID.
         */
        public function getStoreId()
        {
            return $this->store_id;
        }

        /**
         * Get the store name.
         *
         * @return string The store name.
         */
        public function getStoreName()
        {
            return $this->store_name;
        }

        /**
         * Get the street.
         *
         * @return string The street.
         */
        public function getStreet()
        {
            return $this->street;
        }

        /**
         * Get the city.
         *
         * @return string The city.
         */
        public function getCity()
        {
            return $this->city;
        }

        /**
         * Get the state.
         *
         * @return string The state.
         */
        public function getState()
        {
            return $this->state;
        }

        /**
         * Get the zip code.
         *
         * @return string The zip code.
         */
        public function getZipCode()
        {
            return $this->zip_code;
        }

        /**
         * Get the phone number.
         *
         * @return string The phone number.
         */
        public function getPhone()
        {
            return $this->phone;
        }

        /**
         * Get the email address.
         *
         * @return string The email address.
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Get the employees collection.
         *
         * @return Collection The employees collection.
         */
        public function getEmployees()
        {
            return $this->employees;
        }
         /**
         * Get the Stocks collection.
         *
         * @return Collection The Stocks collection.
         */
        public function getStocks()
        {
            return $this->stocks;
        }


        /**
         * Set the store ID.
         *
         * @param int $storeId The store ID.
         * @return void
         */
        public function setStoreId(int $storeId): void
        {
            $this->store_id = $storeId;
        }

        /**
         * Set the store name.
         *
         * @param string $storeName The store name.
         * @return void
         */
        public function setStoreName(string $storeName): void
        {
            $this->store_name = $storeName;
        }

        /**
         * Set the street.
         *
         * @param string $street The street.
         * @return void
         */
        public function setStreet(string $street): void
        {
            $this->street = $street;
        }

        /**
         * Set the city.
         *
         * @param string $city The city.
         * @return void
         */
        public function setCity(string $city): void
        {
            $this->city = $city;
        }

        /**
         * Set the state.
         *
         * @param string $state The state.
         * @return void
         */
        public function setState(string $state): void
        {
            $this->state = $state;
        }

        /**
         * Set the zip code.
         *
         * @param string $zipCode The zip code.
         * @return void
         */
        public function setZipCode(string $zipCode): void
        {
            $this->zip_code = $zipCode;
        }

        /**
         * Set the phone number.
         *
         * @param string $phone The phone number.
         * @return void
         */
        public function setPhone(string $phone): void
        {
            $this->phone = $phone;
        }

        /**
         * Set the email address.
         *
         * @param string $email The email address.
         * @return void
         */
        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

    public function jsonSerialize(): mixed
    {
        return [
            'store_id' => $this->store_id,
            'store_name' => $this->store_name,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }

    // ...

  
   
}



?>