<?php

namespace Payments\Gateways\Withdrawrates\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Withdrawrates
 *
 * @ORM\Table(name="payments_gateways_withdrawrates", indexes={@ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="rate_id_index", columns={"rate_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Withdrawrates extends \Kazist\Table\BaseTable {

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
     * @ORM\Column(name="gateway_id", type="integer", length=11, nullable=false)
     */
    protected $gateway_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate_id", type="integer", length=11, nullable=false)
     */
    protected $rate_id;

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set gateway_id
     *
     * @param integer $gatewayId
     * @return Withdrawrates
     */
    public function setGatewayId($gatewayId) {
        $this->gateway_id = $gatewayId;

        return $this;
    }

    /**
     * Get gateway_id
     *
     * @return integer 
     */
    public function getGatewayId() {
        return $this->gateway_id;
    }

    /**
     * Set rate_id
     *
     * @param integer $rateId
     * @return Withdrawrates
     */
    public function setRateId($rateId) {
        $this->rate_id = $rateId;

        return $this;
    }

    /**
     * Get rate_id
     *
     * @return integer 
     */
    public function getRateId() {
        return $this->rate_id;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

}
