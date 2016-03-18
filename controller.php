<?php
namespace Concrete\Package\ConcreteImageBox;

use Group;
use Package;
use Page;
use PermissionKey;
use PermissionAccess;
use \Concrete\Core\Permission\Access\Entity\GroupEntity as GroupPermissionAccessEntity;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'concrete-image-box';

    protected $appVersionRequired = '5.7.1';

    protected $pkgVersion = '0.9.0';

    public function getPackageName()
    {
        return t("Image Box");
    }

    public function getPackageDescription()
    {
        return t("Adds a simple image box block.");
    }

    public function install()
    {
        $pkg = parent::install();

        // Install the calendar block type
        $imageBoxBT = \BlockType::installBlockTypeFromPackage('image_box', $pkg);
    }
}
