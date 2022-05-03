<?php

defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Attribute\Address\Controller $controller */
/* @var Concrete\Core\Form\Service\Form $form */
/* @var array $scopeItems */
/* @var Concrete\Core\Attribute\View $view */

$akFamilyNameLabel = isset($akFamilyNameLabel) ? $akFamilyNameLabel : t('Family Name');
$akGivenNameLabel = isset($akGivenNameLabel) ? $akGivenNameLabel : t('Given Name');
$akFirstName = isset($akFirstName) ? $akFirstName : 'family_name';
$akFamilyNamePattern = isset($akFamilyNamePattern) ? $akFamilyNamePattern : '';
$akGivenNamePattern = isset($akGivenNamePattern) ? $akGivenNamePattern : '';
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
        <?= $form->label('akFamilyNameLabel', t('Family Name Label')) ?>
        <?= $form->text('akFamilyNameLabel', $akFamilyNameLabel) ?>
    </div>
    <div class="form-group">
        <?= $form->label('akFamilyNamePattern', t('Family Name Pattern')) ?>
        <?= $form->text('akFamilyNamePattern', $akFamilyNamePattern, ['placeholder' => '[a-zA-Z]*']) ?>
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