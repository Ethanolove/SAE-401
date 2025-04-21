<?php
// src/Entity/Film.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use \JsonSerializable;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;



use Entity\Stores;
/**
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employees implements \JsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $employee_id;
    /** @var string */
    /**
     * @ORM\Column(type="string")
     */

    /** @var Stores */
    /**
     * @ManyToOne(targetEntity="Entity\Stores",inversedBy="employees",cascade={"persist"})
     * @JoinColumn(name="store_id", referencedColumnName="store_id")
     */
    private $store;
    /** @var string */
     /**
     * @ORM\Column(type="string")
     */
    private $employee_name;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $employee_email;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private $employee_password;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */

    private $employee_role;



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
        foreach ($this as $key => $value) {
            $s .= "$value ";
        }
        return $s;
    }





    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    public function getEmployeeName()
    {
        return $this->employee_name;
    }

    public function setEmployeeName(string $name)
    {
        $this->employee_name = $name;
    }

    public function getEmployeeEmail()
    {
        return $this->employee_email;
    }

    public function setEmployeeEmail(string $email)
    {
        $this->employee_email = $email;
    }

    public function getEmployeePassword()
    {
        return $this->employee_password;
    }

    public function setEmployeePassword(string $password)
    {
        $this->employee_password = $password;
    }

    public function getEmployeeRole(): ?string
    {
        return $this->employee_role;
    }

    public function setEmployeeRole(string $role)
    {
        $this->employee_role = $role;
    }
    public function getStoreId()
    {
        return $this->store;
    }

    public function setStoreId(Stores $store): void
{
    $this->store = $store;
}

    public function jsonSerialize(): mixed
    {
        return [
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee_name,
            'employee_email' => $this->employee_email,
            'employee_password' => $this->employee_password,
            'employee_role' => $this->employee_role,
            'store_id' => $this->store
        ];
    }
}
