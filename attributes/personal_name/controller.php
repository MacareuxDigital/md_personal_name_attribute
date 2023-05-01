<?php

namespace Concrete\Package\MdPersonalNameAttribute\Attribute\PersonalName;

use Concrete\Core\Attribute\Context\BasicFormContext;
use Concrete\Core\Attribute\Controller as AttributeController;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Attribute\Form\Control\View\GroupedView;
use Concrete\Core\Attribute\MulticolumnTextExportableAttributeInterface;
use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Error\ErrorList\Error\FieldNotPresentError;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Error\ErrorList\Field\AttributeField;
use Concrete\Core\Form\Context\ContextInterface;
use Concrete\Core\Utility\Service\Validation\Strings;
use Concrete\Core\Validator\String\RegexValidator;
use Macareux\Package\PersonalNameAttribute\Entity\PersonalNameSettings;
use Macareux\Package\PersonalNameAttribute\Entity\PersonalNameValue;

class Controller extends AttributeController implements MulticolumnTextExportableAttributeInterface
{
    public $helpers = ['form'];

    protected $searchIndexFieldDefinition = [
        'given_name' => [
            'type' => 'string',
            'options' => ['length' => '255', 'default' => '', 'notnull' => false],
        ],
        'family_name' => [
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
    protected $akGivenNameLabel;

    /**
     * @var string
     */
    protected $akGivenNamePattern;

    /**
     * @var string
     */
    protected $akGivenNameErrorMessage;

    /**
     * @var string
     */
    protected $akFamilyNameLabel;

    /**
     * @var string
     */
    protected $akFamilyNamePattern;

    /**
     * @var string
     */
    protected $akFamilyNameErrorMessage;

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('user');
    }

    public function searchKeywords($keywords, $queryBuilder)
    {
        $h = $this->attributeKey->getAttributeKeyHandle();

        return $queryBuilder->expr()->orX(
            $queryBuilder->expr()->like("ak_{$h}_given_name", ':keywords'),
            $queryBuilder->expr()->like("ak_{$h}_family_name", ':keywords')
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

        $given_name = $this->request('given_name');
        if ($given_name) {
            $list->filter('ak_' . $akHandle . '_given_name', '%' . $given_name . '%', 'like');
        }

        $family_name = $this->request('family_name');
        if ($family_name) {
            $list->filter('ak_' . $akHandle . '_family_name', '%' . $family_name . '%', 'like');
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
        $ak = $this->getAttributeKey();
        /** @var ErrorList $errorList */
        $errorList = $this->app->make('helper/validation/error');

        if (isset($data['given_name']) && $data['given_name'] != '' && isset($data['family_name']) && $data['family_name'] != '') {
            /** @var PersonalNameSettings $settings */
            $settings = $ak->getAttributeKeySettings();
            $givenNamePattern = $settings->getGivenNamePattern();
            if ($givenNamePattern) {
                /** @var RegexValidator $validator */
                $validator = $this->app->make(RegexValidator::class, ['pattern' => '/^' . $givenNamePattern . '$/u']);
                if (!$validator->isValid($data['given_name'])) {
                    $message = $settings->getGivenNameErrorMessage() ? $settings->getGivenNameErrorMessage() : t('Must match pattern.');
                    $errorList->add($message, new Error(new AttributeField($ak)));
                }
            }
            $familyNamePattern = $settings->getFamilyNamePattern();
            if ($familyNamePattern) {
                /** @var RegexValidator $validator */
                $validator = $this->app->make(RegexValidator::class, ['pattern' => '/^' . $familyNamePattern . '$/u']);
                if (!$validator->isValid($data['family_name'])) {
                    $message = $settings->getFamilyNameErrorMessage() ? $settings->getFamilyNameErrorMessage() : t('Must match pattern.');
                    $errorList->add($message, new Error(new AttributeField($ak)));
                }
            }
        } else {
            $errorList->add(new FieldNotPresentError(new AttributeField($ak)));
        }

        return $errorList;
    }

    public function getSearchIndexValue()
    {
        /** @var PersonalNameValue $v */
        $v = $this->getAttributeValue()->getValue();

        return [
            'given_name' => $v->getGivenName(),
            'family_name' => $v->getFamilyName(),
        ];
    }

    public function getDisplayValue()
    {
        /** @var PersonalNameValue $value */
        $value = $this->getAttributeValue()->getValue();
        if ($value) {
            /** @var PersonalNameSettings $settings */
            $settings = $this->getAttributeKeySettings();
            if ($settings->getFirstName() === 'family_name') {
                $value->setFormat('%2$s %1$s');
            }
        }

        return (string) $value;
    }

    public function getPlainTextValue()
    {
        return $this->getDisplayValue();
    }

    public function validateKey($data = false)
    {
        $akFirstName = $data['akFirstName'];
        $akGivenNameLabel = $data['akGivenNameLabel'];
        $akGivenNamePattern = $data['akGivenNamePattern'];
        $akGivenNameErrorMessage = $data['akGivenNameErrorMessage'];
        $akFamilyNameLabel = $data['akFamilyNameLabel'];
        $akFamilyNamePattern = $data['akFamilyNamePattern'];
        $akFamilyNameErrorMessage = $data['akFamilyNameErrorMessage'];

        $e = $this->app->make('error');
        /** @var Strings $strings */
        $strings = $this->app->make(Strings::class);

        if (empty($akFirstName)) {
            $e->add(t('You must select first name field.'));
        }

        if (empty($akGivenNameLabel)) {
            $e->add(t('You must specify a label for given name field.'));
        }

        if (empty($akFamilyNameLabel)) {
            $e->add(t('You must specify a label for family name field.'));
        }

        if (!empty($akGivenNamePattern)) {
            if (!$strings->isValidRegex('/^' . $akGivenNamePattern . '$/u')) {
                $e->add(t('Invalid regex pattern for given name field.'));
            }
            if (empty($akGivenNameErrorMessage)) {
                $e->add(t('You must specify an error message for given name field.'));
            }
        }

        if (!empty($akFamilyNamePattern)) {
            if (!$strings->isValidRegex('/^' . $akFamilyNamePattern . '$/u')) {
                $e->add(t('Invalid regex pattern for family name field.'));
            }
            if (empty($akFamilyNameErrorMessage)) {
                $e->add(t('You must specify an error message for family name field.'));
            }
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
        $av->setGivenName($given_name);
        $av->setFamilyName($family_name);

        return $av;
    }

    public function saveKey($data)
    {
        /** @var PersonalNameSettings $type */
        $type = $this->getAttributeKeySettings();

        $akFirstName = $data['akFirstName'];
        $akGivenNameLabel = $data['akGivenNameLabel'];
        $akGivenNamePattern = $data['akGivenNamePattern'];
        $akGivenNameErrorMessage = $data['akGivenNameErrorMessage'];
        $akFamilyNameLabel = $data['akFamilyNameLabel'];
        $akFamilyNamePattern = $data['akFamilyNamePattern'];
        $akFamilyNameErrorMessage = $data['akFamilyNameErrorMessage'];

        $type->setFirstName($akFirstName);
        $type->setGivenNameLabel($akGivenNameLabel);
        $type->setGivenNamePattern($akGivenNamePattern);
        $type->setGivenNameErrorMessage($akGivenNameErrorMessage);
        $type->setFamilyNameLabel($akFamilyNameLabel);
        $type->setFamilyNamePattern($akFamilyNamePattern);
        $type->setFamilyNameErrorMessage($akFamilyNameErrorMessage);

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
                $this->set('given_name', $value->getGivenName());
                $this->set('family_name', $value->getFamilyName());
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
            'given_name',
            'family_name',
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
            $value ? (string) $value->getGivenName() : '',
            $value ? (string) $value->getFamilyName() : '',
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
            // @var PersonalNameValue $value
            $value->setGivenName(trim(array_shift($textRepresentation)));
            $value->setFamilyName(trim(array_shift($textRepresentation)));
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
        $this->akGivenNameLabel = $type->getGivenNameLabel();
        $this->akGivenNamePattern = $type->getGivenNamePattern();
        $this->akGivenNameErrorMessage = $type->getGivenNameErrorMessage();
        $this->akFamilyNameLabel = $type->getFamilyNameLabel();
        $this->akFamilyNamePattern = $type->getFamilyNamePattern();
        $this->akFamilyNameErrorMessage = $type->getFamilyNameErrorMessage();
        $this->set('akFirstName', $this->akFirstName);
        $this->set('akGivenNameLabel', $this->akGivenNameLabel);
        $this->set('akGivenNamePattern', $this->akGivenNamePattern);
        $this->set('akGivenNameErrorMessage', $this->akGivenNameErrorMessage);
        $this->set('akFamilyNameLabel', $this->akFamilyNameLabel);
        $this->set('akFamilyNamePattern', $this->akFamilyNamePattern);
        $this->set('akFamilyNameErrorMessage', $this->akFamilyNameErrorMessage);
    }
}
