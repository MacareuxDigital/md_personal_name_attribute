<?php

namespace Concrete\Package\MdPersonalNameAttribute;

use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Attribute\TypeFactory;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'md_personal_name_attribute';
    protected $appVersionRequired = '8.5.5';
    protected $pkgVersion = '0.0.2';
    protected $pkgAutoloaderRegistries = [
        'src' => '\Macareux\Package\PersonalNameAttribute',
    ];

    public function getPackageName()
    {
        return t('Macareux Personal Name Attribute');
    }

    public function getPackageDescription()
    {
        return t('Add a new attribute type for personal names.');
    }

    public function install()
    {
        if (!file_exists($this->getPackagePath() . '/vendor/autoload.php')) {
            throw new \Exception(t('Required libraries not found.'));
        }

        $pkg = parent::install();

        /** @var TypeFactory $factory */
        $factory = $this->app->make(TypeFactory::class);
        $type = $factory->getByHandle('personal_name');
        if (!is_object($type)) {
            $type = $factory->add('personal_name', 'Personal Name', $pkg);
            /** @var CategoryService $service */
            $service = $this->app->make(CategoryService::class);
            $userCategory = $service->getByHandle('user')->getController();
            $userCategory->associateAttributeKeyType($type);
        }
    }

    public function on_start()
    {
        $this->registerAutoload();
    }

    protected function registerAutoload()
    {
        require $this->getPackagePath() . '/vendor/autoload.php';
    }
}
