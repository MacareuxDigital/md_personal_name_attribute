<?php

namespace Concrete\Package\MdPersonalNameAttribute\Attribute\PersonalName;

use Concrete\Core\Attribute\Context\BasicFormContext;
use Concrete\Core\Attribute\Controller as AttributeController;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Attribute\Form\Control\View\GroupedView;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Context\ContextInterface;
use Macareux\Package\PersonalNameAttribute\Entity\PersonalNameSettings;
use Macareux\Package\PersonalNameAttribute\Entity\PersonalNameValue;

class Controller extends AttributeController
{
    public $helpers = ['form'];

    protected $searchIndexFieldDefinition = [
        'family_name' => [
            'type' => 'string',
            'options' => ['length' => '255', 'default' => '', 'notnull' => false],
        ],
        'given_name' => [
            'type' => 'string',
            'options' => ['length' => '255', 'default' => '', 'notnull' => false],
        ],
    ];

    /**
     * @var string
     */
    protected $akFirstName;
    /**
     * @var string
     */
    protected $akFamilyNameLabel;

    /**
     * @var string
     */
    protected $akGivenNameLabel;

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('user');
    }

    public function searchKeywords($keywords, $queryBuilder)
    {
        $h = $this->attributeKey->getAttributeKeyHandle();

        return $queryBuilder->expr()->orX(
            $queryBuilder->expr()->like("ak_{$h}_family_name", ':keywords'),
            $queryBuilder->expr()->like("ak_{$h}_given_name", ':keywords')
        );
    }

    public function getControlView(ContextInterface $context)
    {
        return new GroupedView($context, $this->getAttributeKey(), $this->getAttributeValue());
    }

    public function getAttributeValueClass()
    {
        return PersonalNameValue::class;
    }

    public function getAttributeValueObject()
    {
        return $this->attributeValue ? $this->entityManager->find(PersonalNameValue::class, $this->attributeValue->getGenericValue()) : null;
    }

    public function searchForm($list)
    {
        $akHandle = $this->attributeKey->getAttributeKeyHandle();

        $family_name = $this->request('family_name');
        if ($family_name) {
            $list->filter('ak_' . $akHandle . '_family_name', '%' . $family_name . '%', 'like');
        }

        $given_name = $this->request('given_name');
        if ($given_name) {
            $list->filter('ak_' . $akHandle . '_given_name', '%' . $given_name . '%', 'like');
        }

        return $list;
    }

    public function search()
    {
        $this->load();
        $this->form();
        $v = $this->getView();
        $this->set('search', true);
        $v->render(new BasicFormContext());
    }

    public function createAttributeValueFromRequest()
    {
        return $this->createAttributeValue($this->post());
    }

    public function validateValue()
    {
        $v = $this->getAttributeValue()->getValue();
        if (!is_object($v)) {
            return false;
        }
        if (trim((string) $v) == '') {
            return false;
        }

        return true;
    }

    public function validateForm($data)
    {
        return isset($data['family_name']) && $data['family_name'] != ''
            && isset($data['given_name']) && $data['given_name'] != '';
    }

    public function getSearchIndexValue()
    {
        /** @var PersonalNameValue $v */
        $v = $this->getAttributeValue()->getValue();

        return [
            'family_name' => $v->getFamilyName(),
            'given_name' => $v->getGivenName(),
        ];
    }

    public function getDisplayValue()
    {
        return (string) $this->getAttributeValue()->getValue();
    }

    public function validateKey($data = false)
    {
        $akFirstName = $data['akFirstName'];
        $akFamilyNameLabel = $data['akFamilyNameLabel'];
        $akGivenNameLabel = $data['akGivenNameLabel'];

        $e = $this->app->make('error');

        if (empty($akFirstName)) {
            $e->add(t('You must select first name field.'));
        }

        if (empty($akFamilyNameLabel)) {
            $e->add(t('You must specify a label for family name field.'));
        }

        if (empty($akGivenNameLabel)) {
            $e->add(t('You must specify a label for given name field.'));
        }

        return $e;
    }

    public function createAttributeValue($data)
    {
        if ($data instanceof PersonalNameValue) {
            return clone $data;
        }
        extract($data);
        $av = new PersonalNameValue();
        $av->setFamilyName($family_name);
        $av->setGivenName($given_name);

        return $av;
    }

    public function saveKey($data)
    {
        /** @var PersonalNameSettings $type */
        $type = $this->getAttributeKeySettings();

        $akFirstName = $data['akFirstName'];
        $akFamilyNameLabel = $data['akFamilyNameLabel'];
        $akGivenNameLabel = $data['akGivenNameLabel'];

        $type->setFirstName($akFirstName);
        $type->setFamilyNameLabel($akFamilyNameLabel);
        $type->setGivenNameLabel($akGivenNameLabel);

        return $type;
    }

    public function type_form()
    {
        $this->load();
    }

    public function form()
    {
        $this->load();

        if (is_object($this->attributeValue)) {
            /** @var PersonalNameValue $value */
            $value = $this->getAttributeValue()->getValue();
            if ($value) {
                $this->set('family_name', $value->getFamilyName());
                $this->set('given_name', $value->getGivenName());
            }
        }

        $this->set('key', $this->attributeKey);
    }

    public function getAttributeKeySettingsClass()
    {
        return PersonalNameSettings::class;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Attribute\MulticolumnTextExportableAttributeInterface::getAttributeTextRepresentationHeaders()
     */
    public function getAttributeTextRepresentationHeaders()
    {
        return [
            'family_name',
            'given_name',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Attribute\MulticolumnTextExportableAttributeInterface::getAttributeValueTextRepresentation()
     */
    public function getAttributeValueTextRepresentation()
    {
        /** @var PersonalNameValue $value */
        $value = $this->getAttributeValueObject();

        return [
            $value ? (string) $value->getFamilyName() : '',
            $value ? (string) $value->getGivenName() : '',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Attribute\MulticolumnTextExportableAttributeInterface::updateAttributeValueFromTextRepresentation()
     */
    public function updateAttributeValueFromTextRepresentation(array $textRepresentation, ErrorList $warnings)
    {
        $textRepresentation = array_map('trim', $textRepresentation);
        $value = $this->getAttributeValueObject();
        if ($value === null) {
            if (implode('', $textRepresentation) !== '') {
                $value = new PersonalNameValue();
            }
        }
        if ($value !== null) {
            /* @var PersonalNameValue $value */
            $value->setFamilyName(trim(array_shift($textRepresentation)));
            $value->setGivenName(trim(array_shift($textRepresentation)));
        }

        return $value;
    }

    protected function load()
    {
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }

        /** @var PersonalNameSettings $type */
        $type = $ak->getAttributeKeySettings();
        $this->akFirstName = $type->getFirstName();
        $this->akFamilyNameLabel = $type->getFamilyNameLabel();
        $this->akGivenNameLabel = $type->getGivenNameLabel();
        $this->set('akFirstName', $this->akFirstName);
        $this->set('akFamilyNameLabel', $this->akFamilyNameLabel);
        $this->set('akGivenNameLabel', $this->akGivenNameLabel);
    }
}
