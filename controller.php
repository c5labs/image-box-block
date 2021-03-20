<?php
/**
 * Package Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com/add-ons/image-box
 */
namespace Concrete\Package\ImageBoxBlock;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\File\File;
use Concrete\Core\Http\Response;
use Concrete\Core\Package\Package;
use Core;
use Illuminate\Filesystem\Filesystem;

/**
 * Package Controller Class.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com/add-ons/image-box
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
    protected $appVersionRequired = '8.5.4';

    /**
     * Package version.
     *
     * @var string
     */
    protected $pkgVersion = '0.9.1';

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

        // Install the file type
        $type = new \Concrete\Core\File\Image\Thumbnail\Type\Type;

        if (class_exists(\Concrete\Core\Entity\File\Image\Thumbnail\Type\Type::class)) {
            $type = new \Concrete\Core\Entity\File\Image\Thumbnail\Type\Type;
        }

        $type->setHandle('image_box_image');
        $type->setName('Image Box Image');
        $type->setWidth(360);
        $type->setHeight(200);
        $type->save();

        return $pkg;
    }

    /**
     * The packages upgrade routine.
     * 
     * @return void
     */
    public function upgrade()
    {
        parent::upgrade();

        if (! \Concrete\Core\File\Image\Thumbnail\Type\Type::getByHandle('image_box_image')) {
            // Install the file type
            $type = new \Concrete\Core\File\Image\Thumbnail\Type\Type;
            $type->setHandle('image_box_image');
            $type->setName('Image Box Image');
            $type->setWidth(360);
            $type->setHeight(200);
            $type->save();
        }
    }

    public function uninstall()
    {
        // Remove the file type
        $type = \Concrete\Core\File\Image\Thumbnail\Type\Type::getByHandle('image_box_image');
        if ($type instanceof \Concrete\Core\File\Image\Thumbnail\Type\Type) {
            $type->delete();
        }

        parent::uninstall();
    }
}
