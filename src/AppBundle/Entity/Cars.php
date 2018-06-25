<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cars
 *
 * @ORM\Table(name="cars")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarsRepository")
 */
class Cars
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="year_of_issue", type="integer")
     */
    private $yearOfIssue;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_policy_number", type="string", length=255)
     */
    private $insurancePolicyNumber;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="id")
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Cars
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Cars
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set yearOfIssue
     *
     * @param integer $yearOfIssue
     *
     * @return Cars
     */
    public function setYearOfIssue($yearOfIssue)
    {
        $this->yearOfIssue = $yearOfIssue;

        return $this;
    }

    /**
     * Get yearOfIssue
     *
     * @return int
     */
    public function getYearOfIssue()
    {
        return $this->yearOfIssue;
    }

    /**
     * Set insurancePolicyNumber
     *
     * @param string $insurancePolicyNumber
     *
     * @return Cars
     */
    public function setInsurancePolicyNumber($insurancePolicyNumber)
    {
        $this->insurancePolicyNumber = $insurancePolicyNumber;

        return $this;
    }

    /**
     * Get insurancePolicyNumber
     *
     * @return string
     */
    public function getInsurancePolicyNumber()
    {
        return $this->insurancePolicyNumber;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Cars
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}

