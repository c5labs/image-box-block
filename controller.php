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
        return t('Image Box');
    }

    /**
     * Get the package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('A block to allow easy addition of combined image, text & link units.');
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

        return $pkg;
    }
}
