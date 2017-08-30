<?php

namespace Payments\Payments\Coupons\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupons
 *
 * @ORM\Table(name="payments_payments_coupons", indexes={@ORM\Index(name="payment_id_index", columns={"payment_id"}), @ORM\Index(name="coupon_id_index", columns={"coupon_id"})})
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
     * @var integer
     *
     * @ORM\Column(name="payment_id", type="integer", length=11, nullable=false)
     */
    protected $payment_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", length=11, nullable=false)
     */
    protected $item_id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=11, scale=2, nullable=true)
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * @var integer
     *
     * @ORM\Column(name="coupon_id", type="integer", length=11)
     */
    protected $coupon_id;


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
     * Set paymentId
     *
     * @param integer $paymentId
     *
     * @return Coupons
     */
    public function setPaymentId($paymentId)
    {
        $this->payment_id = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return integer
     */
    public function getPaymentId()
    {
        return $this->payment_id;
    }

    /**
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return Coupons
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer
     */
    public function getItemId()
    {
        return $this->item_id;
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
     * Set amount
     *
     * @param string $amount
     *
     * @return Coupons
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
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
     * Set couponId
     *
     * @param integer $couponId
     *
     * @return Coupons
     */
    public function setCouponId($couponId)
    {
        $this->coupon_id = $couponId;

        return $this;
    }

    /**
     * Get couponId
     *
     * @return integer
     */
    public function getCouponId()
    {
        return $this->coupon_id;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}

