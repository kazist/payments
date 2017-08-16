<?php

namespace Payments\Invoices\Items\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Items
 *
 * @ORM\Table(name="payments_invoices_items", indexes={@ORM\Index(name="invoice_id_index", columns={"invoice_id"}), @ORM\Index(name="user_id_index", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Items extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="invoice_id", type="integer", length=11, nullable=false)
     */
    protected $invoice_id;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_source", type="string", length=255, nullable=true)
     */
    protected $payment_source;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", length=11, nullable=true)
     */
    protected $item_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", length=11, nullable=true)
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", length=11, nullable=true)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_price", type="decimal", precision=11, scale=2, nullable=true)
     */
    protected $unit_price;

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
     * Set invoiceId
     *
     * @param integer $invoiceId
     *
     * @return Items
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoice_id = $invoiceId;

        return $this;
    }

    /**
     * Get invoiceId
     *
     * @return integer
     */
    public function getInvoiceId()
    {
        return $this->invoice_id;
    }

    /**
     * Set paymentSource
     *
     * @param string $paymentSource
     *
     * @return Items
     */
    public function setPaymentSource($paymentSource)
    {
        $this->payment_source = $paymentSource;

        return $this;
    }

    /**
     * Get paymentSource
     *
     * @return string
     */
    public function getPaymentSource()
    {
        return $this->payment_source;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Items
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
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return Items
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
     * @return Items
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Items
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Items
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set unitPrice
     *
     * @param string $unitPrice
     *
     * @return Items
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unit_price = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unit_price;
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

