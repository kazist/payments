<?php

namespace Payments\Transactions\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transactions
 *
 * @ORM\Table(name="payments_transactions", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="payment_id_index", columns={"payment_id"}), @ORM\Index(name="rate_id_index", columns={"rate_id"}), @ORM\Index(name="behalf_user_id_index", columns={"behalf_user_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Transactions extends \Kazist\Table\BaseTable
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
     * @var integer
     *
     * @ORM\Column(name="payment_id", type="integer", length=11, nullable=true)
     */
    protected $payment_id;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    protected $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate_id", type="integer", length=11, nullable=true)
     */
    protected $rate_id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="decimal", precision=11, scale=2, nullable=true)
     */
    protected $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="decimal", precision=11, scale=2, nullable=true)
     */
    protected $debit;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255, nullable=true)
     */
    protected $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_capped", type="integer", length=11, nullable=true)
     */
    protected $is_capped;

    /**
     * @var integer
     *
     * @ORM\Column(name="behalf_user_id", type="integer", length=11, nullable=true)
     */
    protected $behalf_user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_processed", type="integer", length=11, nullable=true)
     */
    protected $is_processed;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Transactions
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
     * @return Transactions
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
     * Set paymentId
     *
     * @param integer $paymentId
     *
     * @return Transactions
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
     * Set source
     *
     * @param string $source
     *
     * @return Transactions
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set rateId
     *
     * @param integer $rateId
     *
     * @return Transactions
     */
    public function setRateId($rateId)
    {
        $this->rate_id = $rateId;

        return $this;
    }

    /**
     * Get rateId
     *
     * @return integer
     */
    public function getRateId()
    {
        return $this->rate_id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Transactions
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
     * Set credit
     *
     * @param string $credit
     *
     * @return Transactions
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set debit
     *
     * @param string $debit
     *
     * @return Transactions
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Transactions
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set level
     *
     * @param string $level
     *
     * @return Transactions
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isCapped
     *
     * @param integer $isCapped
     *
     * @return Transactions
     */
    public function setIsCapped($isCapped)
    {
        $this->is_capped = $isCapped;

        return $this;
    }

    /**
     * Get isCapped
     *
     * @return integer
     */
    public function getIsCapped()
    {
        return $this->is_capped;
    }

    /**
     * Set behalfUserId
     *
     * @param integer $behalfUserId
     *
     * @return Transactions
     */
    public function setBehalfUserId($behalfUserId)
    {
        $this->behalf_user_id = $behalfUserId;

        return $this;
    }

    /**
     * Get behalfUserId
     *
     * @return integer
     */
    public function getBehalfUserId()
    {
        return $this->behalf_user_id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Transactions
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isProcessed
     *
     * @param integer $isProcessed
     *
     * @return Transactions
     */
    public function setIsProcessed($isProcessed)
    {
        $this->is_processed = $isProcessed;

        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return integer
     */
    public function getIsProcessed()
    {
        return $this->is_processed;
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

