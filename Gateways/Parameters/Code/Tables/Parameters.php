<?php

namespace Payments\Gateways\Parameters\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameters
 *
 * @ORM\Table(name="payments_gateways_parameters", indexes={@ORM\Index(name="gateway_id_index", columns={"gateway_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Parameters extends \Kazist\Table\BaseTable {

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_private", type="integer", length=11, nullable=true)
     */
    protected $is_private;

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
     * @return Parameters
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
     * Set name
     *
     * @param string $name
     * @return Parameters
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Parameters
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set is_private
     *
     * @param integer $isPrivate
     * @return Parameters
     */
    public function setIsPrivate($isPrivate) {
        $this->is_private = $isPrivate;

        return $this;
    }

    /**
     * Get is_private
     *
     * @return integer 
     */
    public function getIsPrivate() {
        return $this->is_private;
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
