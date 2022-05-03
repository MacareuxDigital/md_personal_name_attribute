<?php

namespace Macareux\Package\PersonalNameAttribute\Entity;

use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="atMacareuxPersonalName")
 */
class PersonalNameValue extends AbstractValue
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $given_name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $family_name;

    /**
     * @var string
     */
    protected $format = '%1$s %2$s';

    public function __toString()
    {
        return sprintf($this->format, $this->getGivenName(), $this->getFamilyName());
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @param string $given_name
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->family_name;
    }

    /**
     * @param string $family_name
     */
    public function setFamilyName($family_name)
    {
        $this->family_name = $family_name;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
}
