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
    protected $akFamilyNameLabel = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $akGivenNameLabel = '';

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
}
