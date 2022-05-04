<?php

namespace Macareux\Package\PersonalNameAttribute\Entity;

use Concrete\Core\Entity\Attribute\Key\Settings\Settings;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="atMacareuxPersonalNameSettings")
 */
class PersonalNameSettings extends Settings
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akFirstName = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akGivenNameLabel = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akGivenNamePattern = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akGivenNameErrorMessage = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akFamilyNameLabel = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akFamilyNamePattern = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akFamilyNameErrorMessage = '';

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->akFirstName;
    }

    /**
     * @param string $akFirstName
     */
    public function setFirstName($akFirstName)
    {
        $this->akFirstName = $akFirstName;
    }

    /**
     * @return string
     */
    public function getGivenNameLabel()
    {
        return $this->akGivenNameLabel;
    }

    /**
     * @param string $akGivenNameLabel
     */
    public function setGivenNameLabel($akGivenNameLabel)
    {
        $this->akGivenNameLabel = $akGivenNameLabel;
    }

    /**
     * @return string
     */
    public function getGivenNamePattern()
    {
        return $this->akGivenNamePattern;
    }

    /**
     * @param string $akGivenNamePattern
     */
    public function setGivenNamePattern($akGivenNamePattern)
    {
        $this->akGivenNamePattern = $akGivenNamePattern;
    }

    /**
     * @return string
     */
    public function getGivenNameErrorMessage()
    {
        return $this->akGivenNameErrorMessage;
    }

    /**
     * @param string $akGivenNameErrorMessage
     */
    public function setGivenNameErrorMessage(string $akGivenNameErrorMessage)
    {
        $this->akGivenNameErrorMessage = $akGivenNameErrorMessage;
    }

    /**
     * @return string
     */
    public function getFamilyNameLabel()
    {
        return $this->akFamilyNameLabel;
    }

    /**
     * @param string $akFamilyNameLabel
     */
    public function setFamilyNameLabel($akFamilyNameLabel)
    {
        $this->akFamilyNameLabel = $akFamilyNameLabel;
    }

    /**
     * @return string
     */
    public function getFamilyNamePattern()
    {
        return $this->akFamilyNamePattern;
    }

    /**
     * @param string $akFamilyNamePattern
     */
    public function setFamilyNamePattern($akFamilyNamePattern)
    {
        $this->akFamilyNamePattern = $akFamilyNamePattern;
    }

    /**
     * @return string
     */
    public function getFamilyNameErrorMessage()
    {
        return $this->akFamilyNameErrorMessage;
    }

    /**
     * @param string $akFamilyNameErrorMessage
     */
    public function setFamilyNameErrorMessage(string $akFamilyNameErrorMessage)
    {
        $this->akFamilyNameErrorMessage = $akFamilyNameErrorMessage;
    }
}
