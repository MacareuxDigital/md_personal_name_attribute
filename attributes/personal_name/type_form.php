<?php

defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Attribute\Address\Controller $controller */
/* @var Concrete\Core\Form\Service\Form $form */
/* @var array $scopeItems */
/* @var Concrete\Core\Attribute\View $view */

$akGivenNameLabel = isset($akGivenNameLabel) ? $akGivenNameLabel : t('Given Name');
$akGivenNamePattern = isset($akGivenNamePattern) ? $akGivenNamePattern : '';
$akGivenNameErrorMessage = isset($akGivenNameErrorMessage) ? $akGivenNameErrorMessage : '';
$akFamilyNameLabel = isset($akFamilyNameLabel) ? $akFamilyNameLabel : t('Family Name');
$akFamilyNamePattern = isset($akFamilyNamePattern) ? $akFamilyNamePattern : '';
$akFamilyNameErrorMessage = isset($akFamilyNameErrorMessage) ? $akFamilyNameErrorMessage : '';
$akFirstName = isset($akFirstName) ? $akFirstName : 'family_name';
?>
<fieldset class="ccm-attribute ccm-attribute-personal-name">
    <legend><?=t('Personal Name Options')?></legend>
    <div class="form-group">
        <?= $form->label('akGivenNameLabel', t('Given Name Label')) ?>
        <?= $form->text('akGivenNameLabel', $akGivenNameLabel) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akGivenNamePattern', t('Given Name Pattern')) ?>
        <?= $form->text('akGivenNamePattern', $akGivenNamePattern, ['placeholder' => '[a-zA-Z]*']) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akGivenNameErrorMessage', t('Given Name Error Message')) ?>
        <?= $form->text('akGivenNameErrorMessage', $akGivenNameErrorMessage, ['placeholder' => t('Given name may only contain letters.')]) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akFamilyNameLabel', t('Family Name Label')) ?>
        <?= $form->text('akFamilyNameLabel', $akFamilyNameLabel) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akFamilyNamePattern', t('Family Name Pattern')) ?>
        <?= $form->text('akFamilyNamePattern', $akFamilyNamePattern, ['placeholder' => '[a-zA-Z]*']) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akFamilyNameErrorMessage', t('Family Name Error Message')) ?>
        <?= $form->text('akFamilyNameErrorMessage', $akFamilyNameErrorMessage, ['placeholder' => t('Family name may only contain letters.')]) ?>
    </div>
    <div class="form-group">
        <?= $form->label('', t('Which field as First Name?')) ?>
        <div class="radio">
            <label><?= $form->radio('akFirstName', 'given_name', $akFirstName) ?><?= t('Given Name') ?></label>
        </div>
        <div class="radio">
            <label><?= $form->radio('akFirstName', 'family_name', $akFirstName) ?><?= t('Family Name') ?></label>
        </div>
    </div>
</fieldset>