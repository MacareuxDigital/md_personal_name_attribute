<?php

defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Attribute\Address\Controller $controller */
/* @var Concrete\Core\Attribute\View $view */
/* @var Concrete\Core\Attribute\View $this */
/* @var Concrete\Core\Form\Service\Form $form */

$family_name = isset($family_name) ? $family_name : null;
$given_name = isset($given_name) ? $given_name : null;

$family_name_label = isset($akFamilyNameLabel) ? $akFamilyNameLabel : t('Family Name');
$given_name_label = isset($akGivenNameLabel) ? $akGivenNameLabel : t('Given Name');
$akFirstName = isset($akFirstName) ? $akFirstName : 'family_name';
?>

<div class="row ccm-attribute-personal-name-composer-wrapper">

    <?php if ($akFirstName === 'family_name') { ?>
        <div class="col-md-6 form-group ccm-attribute-personal-name-family-name">
            <?= $form->label($this->field('family_name'), $family_name_label); ?>
            <?= $form->text($this->field('family_name'), $family_name); ?>
        </div>
    <?php } ?>

    <div class="col-md-6 form-group ccm-attribute-personal-name-given-name">
        <?= $form->label($this->field('given_name'), $given_name_label); ?>
        <?= $form->text($this->field('given_name'), $given_name); ?>
    </div>

    <?php if ($akFirstName !== 'family_name') { ?>
        <div class="col-md-6 form-group ccm-attribute-personal-name-family-name">
            <?= $form->label($this->field('family_name'), $family_name_label); ?>
            <?= $form->text($this->field('family_name'), $family_name); ?>
        </div>
    <?php } ?>

</div>
