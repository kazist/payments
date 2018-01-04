<?php

namespace Payments\Prepayments\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prepayments
 *
 * @ORM\Table(name="payments_prepayments", indexes={@ORM\Index(name="payment_source_index", columns={"payment_source"}), @ORM\Index(name="item_id_index", columns={"item_id"}), @ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="package_source_index", columns={"package_source"}), @ORM\Index(name="modified_by_index", columns={"modified_by"}), @ORM\Index(name="created_by_index", columns={"created_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Prepayments extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="payment_source", type="integer", length=11, nullable=false)
     */
    protected $payment_source;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", length=11, nullable=false)
     */
    protected $item_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=true)
     */
    protected $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="package_source", type="string", length=255, nullable=true)
     */
    protected $package_source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;


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
     * Set paymentSource
     *
     * @param integer $paymentSource
     *
     * @return Prepayments
     */
    public function setPaymentSource($paymentSource)
    {
        $this->payment_source = $paymentSource;

        return $this;
    }

    /**
     * Get paymentSource
     *
     * @return integer
     */
    public function getPaymentSource()
    {
        return $this->payment_source;
    }

    /**
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return Prepayments
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Prepayments
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set packageSource
     *
     * @param string $packageSource
     *
     * @return Prepayments
     */
    public function setPackageSource($packageSource)
    {
        $this->package_source = $packageSource;

        return $this;
    }

    /**
     * Get packageSource
     *
     * @return string
     */
    public function getPackageSource()
    {
        return $this->package_source;
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
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->date_modified;
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
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}

