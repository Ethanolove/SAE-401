<?php
// src/Entity/Stores.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Employees;
use Entity\Stocks;
/**
 * @ORM\Entity
 * @ORM\Table(name="stores")
 **/
class Stores implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $store_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $store_name;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $phone;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $street;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $city;

    /** @var string */
    /**
     * @ORM\Column(type="string",length=10)
     */
    private string $state;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=5)
     */
    private string $zip_code;
    /**
     * @ORM\OneToMany(targetEntity="Entity\Employees", mappedBy="store")
     */
    private Collection $employees;
    /**
     * @ORM\OneToMany(targetEntity="Entity\Stocks", mappedBy="store")
     */
    private Collection $stocks;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->employees = new ArrayCollection();
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
            if($k != "employees" && $k != "stocks")
                $s .= $k . ": " . $v . "\n";            
        }
        return $s;
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
     * @return Stores
     */
    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
        return $this;
    }

    /**
     * Get store_name
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->store_name;
    }

    /**
     * Set store_name
     *
     * @param string $store_name
     * @return Stores
     */
    public function setStoreName($store_name)
    {
        $this->store_name = $store_name;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Stores
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */    
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Stores
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Stores
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Stores
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Stores
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get zip_code
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set zip_code
     *
     * @param string $zip_code
     * @return Stores
     */
    public function setZipCode($zip_code)
    {
        $this->zip_code = $zip_code;
        return $this;
    }
    public function jsonSerialize()
    {
        $res = [];
        foreach ($this as $k => $v) {
            $res[$k] = $v;
        }
        return $res;
    }
}