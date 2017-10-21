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

    public function on_start()
    {
        /**
         * This is a fix as the current implementation of the asset manger doesn't return 
         * the fvID for the selected file. We need this for editing the thumbnail.
         */
        $router = Core::make(\Concrete\Core\Routing\Router::class);
        $router->register('/ccm/system/image-box-block/current-file-version-resolver/{fID}', function($fID) {
            $fID = \Loader::helper('security')->sanitizeInt($fID);
            $file = File::getById($fID);

            if ($file instanceof File) {

                // If we don't have a valid thumbnail, generate them.
                $thumbnails = array_map(function($item) {
                    return $item->getThumbnailTypeVersionObject()->getHandle();
                }, $file->getThumbnails());

                if (! in_array('image_box_image', $thumbnails)) {
                    $file->rescanThumbnails();
                }

                return new Response(json_encode($file->getFileVersionID()), 200);
            }
            return new Response('404 Not Found', 404);
        });

        /**
         * Enables us to get the dimensions of a file.
         */
        $router = Core::make(\Concrete\Core\Routing\Router::class);
        $router->register('/ccm/system/image-box-block/dimensions/{fID}', function($fID) {
            $fID = \Loader::helper('security')->sanitizeInt($fID);
            $file = File::getById($fID);
            $type = $file->getTypeObject();

            if ($file instanceof File && \Concrete\Core\File\Type\Type::T_IMAGE === intval($type->getGenericType())) {
                return new Response(json_encode([
                    'width' => $file->getAttribute('width'), 
                    'height' => $file->getAttribute('height')
                ]), 200);
            }
            return new Response('404 Not Found', 404);
        });
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
