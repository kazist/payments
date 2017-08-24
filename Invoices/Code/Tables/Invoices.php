<?php

namespace Payments\Invoices\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoices
 *
 * @ORM\Table(name="payments_invoices", indexes={@ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="user_id_index", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Invoices extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="invoice_no", type="string", length=255, nullable=false)
     */
    protected $invoice_no;

    /**
     * @var integer
     *
     * @ORM\Column(name="gateway_id", type="integer", length=11, nullable=true)
     */
    protected $gateway_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", length=11, nullable=true)
     */
    protected $item_id;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_source", type="string", length=255, nullable=true)
     */
    protected $payment_source;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=255, nullable=true)
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="completed", type="integer", length=11, nullable=true)
     */
    protected $completed;

    /**
     * @var integer
     *
     * @ORM\Column(name="successful", type="integer", length=11, nullable=true)
     */
    protected $successful;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_canceled", type="integer", length=11, nullable=true)
     */
    protected $is_canceled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=false)
     */
    protected $expiry_date;

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
     * Set invoiceNo
     *
     * @param string $invoiceNo
     *
     * @return Invoices
     */
    public function setInvoiceNo($invoiceNo)
    {
        $this->invoice_no = $invoiceNo;

        return $this;
    }

    /**
     * Get invoiceNo
     *
     * @return string
     */
    public function getInvoiceNo()
    {
        return $this->invoice_no;
    }

    /**
     * Set gatewayId
     *
     * @param integer $gatewayId
     *
     * @return Invoices
     */
    public function setGatewayId($gatewayId)
    {
        $this->gateway_id = $gatewayId;

        return $this;
    }

    /**
     * Get gatewayId
     *
     * @return integer
     */
    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Invoices
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
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return Invoices
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
     * Set paymentSource
     *
     * @param string $paymentSource
     *
     * @return Invoices
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
     * @return Invoices
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
     * @return Invoices
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
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Invoices
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set completed
     *
     * @param integer $completed
     *
     * @return Invoices
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return integer
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set successful
     *
     * @param integer $successful
     *
     * @return Invoices
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;

        return $this;
    }

    /**
     * Get successful
     *
     * @return integer
     */
    public function getSuccessful()
    {
        return $this->successful;
    }

    /**
     * Set isCanceled
     *
     * @param integer $isCanceled
     *
     * @return Invoices
     */
    public function setIsCanceled($isCanceled)
    {
        $this->is_canceled = $isCanceled;

        return $this;
    }

    /**
     * Get isCanceled
     *
     * @return integer
     */
    public function getIsCanceled()
    {
        return $this->is_canceled;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return Invoices
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiry_date = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiry_date;
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

