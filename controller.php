<?php
/**
 * Package Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
namespace Concrete\Package\ImageBoxBlock;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Illuminate\Filesystem\Filesystem;

/**
 * Package Controller Class.
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
class Controller extends Package
{
    /**
     * Package handle.
     *
     * @var string
     */
    protected $pkgHandle = 'image-box-block';

    /**
     * Minimum concrete5 version.
     *
     * @var string
     */
    protected $appVersionRequired = '5.7.1';

    /**
     * Package version.
     *
     * @var string
     */
    protected $pkgVersion = '0.9.0';

    /**
     * Keep me updated interest ID.
     *
     * @var string
     */
    public $interest_id = 'ebfc805465';

    /**
     * Get the package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t("Image Box");
    }

    /**
     * Get the package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t("A block to allow easy addition of combined image, text & link units.");
    }

    /**
     * Get a helper instance.
     *
     * @param  mixed $pkg
     * @return \C5dev\Package\Thanks\PackageInstallHelper
     */
    protected function getHelperInstance($pkg)
    {
        if (! class_exists('\C5dev\Package\Thanks\PackageInstallHelper')) {
            // Require composer
            $filesystem = new Filesystem();
            $filesystem->getRequire(__DIR__.'/vendor/autoload.php');
        }

        return new \C5dev\Package\Thanks\PackageInstallHelper($pkg);
    }

    /**
     * Start-up Hook.
     *
     * @return void
     */
    public function on_start()
    {
        // Check whether we have just installed the package
        // and should redirect to intermediate 'thank you' page.
        $this->getHelperInstance($this)->checkForPostInstall();
    }

    /**
     * Install routine.
     *
     * @return \Concrete\Core\Package\Package
     */
    public function install()
    {
        $pkg = parent::install();

        // Install the image box block type
        $bt = BlockType::installBlockTypeFromPackage('image_box', $pkg);

        // Install the 'thank you' page if needed.
        $this->getHelperInstance($pkg)->addThanksPage();

        return $pkg;
    }
}
