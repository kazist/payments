<?php

namespace Payments\Coupons\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupons
 *
 * @ORM\Table(name="payments_coupons", indexes={@ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Coupons extends \Kazist\Table\BaseTable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="start_amount", type="integer", length=11, nullable=true)
     */
    protected $start_amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="end_amount", type="integer", length=11, nullable=true)
     */
    protected $end_amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    protected $start_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    protected $end_date;

    /**
     * @var string
     *
     * @ORM\Column(name="applied", type="string", length=255, nullable=true)
     */
    protected $applied;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_percent", type="integer", length=11, nullable=true)
     */
    protected $is_percent;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_visible", type="integer", length=11, nullable=true)
     */
    protected $is_visible;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Coupons
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Coupons
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Coupons
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set startAmount
     *
     * @param integer $startAmount
     *
     * @return Coupons
     */
    public function setStartAmount($startAmount)
    {
        $this->start_amount = $startAmount;

        return $this;
    }

    /**
     * Get startAmount
     *
     * @return integer
     */
    public function getStartAmount()
    {
        return $this->start_amount;
    }

    /**
     * Set endAmount
     *
     * @param integer $endAmount
     *
     * @return Coupons
     */
    public function setEndAmount($endAmount)
    {
        $this->end_amount = $endAmount;

        return $this;
    }

    /**
     * Get endAmount
     *
     * @return integer
     */
    public function getEndAmount()
    {
        return $this->end_amount;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Coupons
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Coupons
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set applied
     *
     * @param string $applied
     *
     * @return Coupons
     */
    public function setApplied($applied)
    {
        $this->applied = $applied;

        return $this;
    }

    /**
     * Get applied
     *
     * @return string
     */
    public function getApplied()
    {
        return $this->applied;
    }

    /**
     * Set isPercent
     *
     * @param integer $isPercent
     *
     * @return Coupons
     */
    public function setIsPercent($isPercent)
    {
        $this->is_percent = $isPercent;

        return $this;
    }

    /**
     * Get isPercent
     *
     * @return integer
     */
    public function getIsPercent()
    {
        return $this->is_percent;
    }

    /**
     * Set isVisible
     *
     * @param integer $isVisible
     *
     * @return Coupons
     */
    public function setIsVisible($isVisible)
    {
        $this->is_visible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return integer
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return Coupons
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modifiedBy
     *
     * @return integer
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}

