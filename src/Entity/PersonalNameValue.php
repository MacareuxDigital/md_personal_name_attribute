<?php

namespace Macareux\Package\PersonalNameAttribute\Entity;

use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Concrete\Core\Localization\Localization;
use Doctrine\ORM\Mapping as ORM;
use Jsor\StringFormatter\NameFormatter;

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
    protected $family_name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $given_name;

    public function __toString()
    {
        $formatter = new NameFormatter(Localization::activeLocale());

        return $formatter->format([
            'family_name' => $this->getFamilyName(),
            'given_name' => $this->getGivenName(),
        ]);
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
}
