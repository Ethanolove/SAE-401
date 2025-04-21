<?php
// src/Entity/Employees.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Stores;
/**
 * @ORM\Entity
 * @ORM\Table(name="employees")
 **/
class Employees implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $employee_id;

    /** @var Stores */
    /**
     * @ManyToOne(targetEntity="Entity\Stores", inversedBy="employees", cascade={"persist"})
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private string $store;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $employee_name;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $employee_email;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $employee_password;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $employee_role;

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
     * Get employee_id
     *
     * @return int
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * Set employee_id
     *
     * @param int $employee_id
     * @return employe
     */
    public function setEmployeeId($employee_id)
    {
        $this->employee_id = $employee_id;
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
     * @return employe
     */
    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
        return $this;
    }

    /**
     * Get employee_name
     *
     * @return string
     */
    public function getEmployeeName()
    {
        return $this->employee_name;
    }

    /**
     * Set employee_name
     *
     * @param string $employee_name
     * @return employe
     */
    public function setEmployeeName($employee_name)
    {
        $this->employee_name = $employee_name;
        return $this;
    }

    /**
     * Get employee_email
     *
     * @return string
     */
    public function getEmployeeEmail()
    {
        return $this->employee_email;
    }

    /**
     * Set employee_email
     *
     * @param string $employee_email
     * @return employe
     */
    public function setEmployeeEmail($employee_email)
    {
        $this->employee_email = $employee_email;
        return $this;
    }

    /**
     * Get employee_password
     *
     * @return string
     */
    public function getEmployeePassword()
    {
        return $this->employee_password;
    }

    /**
     * Set employee_password
     *
     * @param string $employee_password
     * @return employe
     */
    public function setEmployeePassword($employee_password)
    {
        $this->employee_password = $employee_password;
        return $this;
    }

    /**
     * Get employee_role
     *
     * @return string
     */
    public function getEmployeeRole()
    {
        return $this->employee_role;
    }

    /**
     * Set employee_role
     *
     * @param string $employee_role
     * @return employe
     */
    public function setEmployeeRole($employee_role)
    {
        $this->employee_role = $employee_role;
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