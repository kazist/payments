<?php

namespace Payments\Rates\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rates
 *
 * @ORM\Table(name="payments_rates", indexes={@ORM\Index(name="country_id_index", columns={"country_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Rates extends \Kazist\Table\BaseTable {

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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=255, nullable=false)
     */
    protected $short_name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    protected $value;

    /**
     * @var string
     *
     * @ORM\Column(name="rule", type="string", length=255, nullable=true)
     */
    protected $rule;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="effected_on", type="string", length=255, nullable=true)
     */
    protected $effected_on;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_limit", type="integer", length=11, nullable=true)
     */
    protected $amount_limit;

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
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", length=11, nullable=true)
     */
    protected $country_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="file_limit", type="integer", length=11, nullable=true)
     */
    protected $file_limit;

    /**
     * @var string
     *
     * @ORM\Column(name="file_type", type="string", length=255, nullable=true)
     */
    protected $file_type;

    /**
     * @var string
     *
     * @ORM\Column(name="file_prefix", type="text", nullable=true)
     */
    protected $file_prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="file_structure", type="text", nullable=true)
     */
    protected $file_structure;

    /**
     * @var string
     *
     * @ORM\Column(name="file_suffix", type="text", nullable=true)
     */
    protected $file_suffix;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_visible", type="integer", length=11, nullable=true)
     */
    protected $is_visible;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="next_effected_on_date", type="datetime", nullable=true)
     */
    protected $next_effected_on_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

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
     * Set title
     *
     * @param string $title
     * @return Rates
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set short_name
     *
     * @param string $shortName
     * @return Rates
     */
    public function setShortName($shortName) {
        $this->short_name = $shortName;

        return $this;
    }

    /**
     * Get short_name
     *
     * @return string 
     */
    public function getShortName() {
        return $this->short_name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Rates
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
     * Set rule
     *
     * @param string $rule
     * @return Rates
     */
    public function setRule($rule) {
        $this->rule = $rule;

        return $this;
    }

    /**
     * Get rule
     *
     * @return string 
     */
    public function getRule() {
        return $this->rule;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Rates
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set effected_on
     *
     * @param string $effectedOn
     * @return Rates
     */
    public function setEffectedOn($effectedOn) {
        $this->effected_on = $effectedOn;

        return $this;
    }

    /**
     * Get effected_on
     *
     * @return string 
     */
    public function getEffectedOn() {
        return $this->effected_on;
    }

    /**
     * Set amount_limit
     *
     * @param integer $amountLimit
     * @return Rates
     */
    public function setAmountLimit($amountLimit) {
        $this->amount_limit = $amountLimit;

        return $this;
    }

    /**
     * Get amount_limit
     *
     * @return integer 
     */
    public function getAmountLimit() {
        return $this->amount_limit;
    }

    /**
     * Set start_amount
     *
     * @param integer $startAmount
     * @return Rates
     */
    public function setStartAmount($startAmount) {
        $this->start_amount = $startAmount;

        return $this;
    }

    /**
     * Get start_amount
     *
     * @return integer 
     */
    public function getStartAmount() {
        return $this->start_amount;
    }

    /**
     * Set end_amount
     *
     * @param integer $endAmount
     * @return Rates
     */
    public function setEndAmount($endAmount) {
        $this->end_amount = $endAmount;

        return $this;
    }

    /**
     * Get end_amount
     *
     * @return integer 
     */
    public function getEndAmount() {
        return $this->end_amount;
    }

    /**
     * Set country_id
     *
     * @param integer $countryId
     * @return Rates
     */
    public function setCountryId($countryId) {
        $this->country_id = $countryId;

        return $this;
    }

    /**
     * Get country_id
     *
     * @return integer 
     */
    public function getCountryId() {
        return $this->country_id;
    }

    /**
     * Set file_limit
     *
     * @param integer $fileLimit
     * @return Rates
     */
    public function setFileLimit($fileLimit) {
        $this->file_limit = $fileLimit;

        return $this;
    }

    /**
     * Get file_limit
     *
     * @return integer 
     */
    public function getFileLimit() {
        return $this->file_limit;
    }

    /**
     * Set file_type
     *
     * @param string $fileType
     * @return Rates
     */
    public function setFileType($fileType) {
        $this->file_type = $fileType;

        return $this;
    }

    /**
     * Get file_type
     *
     * @return string 
     */
    public function getFileType() {
        return $this->file_type;
    }

    /**
     * Set file_prefix
     *
     * @param string $filePrefix
     * @return Rates
     */
    public function setFilePrefix($filePrefix) {
        $this->file_prefix = $filePrefix;

        return $this;
    }

    /**
     * Get file_prefix
     *
     * @return string 
     */
    public function getFilePrefix() {
        return $this->file_prefix;
    }

    /**
     * Set file_structure
     *
     * @param string $fileStructure
     * @return Rates
     */
    public function setFileStructure($fileStructure) {
        $this->file_structure = $fileStructure;

        return $this;
    }

    /**
     * Get file_structure
     *
     * @return string 
     */
    public function getFileStructure() {
        return $this->file_structure;
    }

    /**
     * Set file_suffix
     *
     * @param string $fileSuffix
     * @return Rates
     */
    public function setFileSuffix($fileSuffix) {
        $this->file_suffix = $fileSuffix;

        return $this;
    }

    /**
     * Get file_suffix
     *
     * @return string 
     */
    public function getFileSuffix() {
        return $this->file_suffix;
    }

    /**
     * Set is_visible
     *
     * @param integer $isVisible
     * @return Rates
     */
    public function setIsVisible($isVisible) {
        $this->is_visible = $isVisible;

        return $this;
    }

    /**
     * Get is_visible
     *
     * @return integer 
     */
    public function getIsVisible() {
        return $this->is_visible;
    }

    /**
     * Set next_effected_on_date
     *
     * @param \DateTime $nextEffectedOnDate
     * @return Rates
     */
    public function setNextEffectedOnDate($nextEffectedOnDate) {
        $this->next_effected_on_date = $nextEffectedOnDate;

        return $this;
    }

    /**
     * Get next_effected_on_date
     *
     * @return \DateTime 
     */
    public function getNextEffectedOnDate() {
        return $this->next_effected_on_date;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Rates
     */
    public function setPublished($published) {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished() {
        return $this->published;
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
