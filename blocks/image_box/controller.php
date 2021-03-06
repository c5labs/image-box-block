<?php
/**
 * Block Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com/add-ons/image-box
 */
namespace Concrete\Package\ImageBoxBlock\Block\ImageBox;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockController;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\File\File;
use Concrete\Core\Legacy\Loader;
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;
use Core;

/**
 * Block Controller Class.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com/add-ons/image-box
 */
class Controller extends BlockController
{
    /**
     * Block table name.
     *
     * @var string
     */
    protected $btTable = 'btImageBox';

    /**
     * Block editor interface width.
     *
     * @var string
     */
    protected $btInterfaceWidth = '340';

    /**
     * Block editor interface height.
     *
     * @var string
     */
    protected $btInterfaceHeight = '365';

    /**
     * Cache the blocks database record?
     *
     * @var bool
     */
    protected $btCacheBlockRecord = true;

    /**
     * Cache the blocks output?
     *
     * @var bool
     */
    protected $btCacheBlockOutput = true;

    /**
     * Cache the block output for $_POST requests?
     *
     * @var bool
     */
    protected $btCacheBlockOutputOnPost = true;

    /**
     * Cache the blocks output for registered users?
     *
     * @var bool
     */
    protected $btCacheBlockOutputForRegisteredUsers = false;

    /**
     * The set within the block chooser interface
     * that this block belongs to.
     *
     * @var string
     */
    protected $btDefaultSet = 'basic';

    /**
     * Get the blocks name.
     *
     * @return string
     */
    public function getBlockTypeName()
    {
        return t('Image box');
    }

    /**
     * Get the blocks description.
     *
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t('Simple image, text & link units.');
    }

    /**
     * Add form hook.
     *
     * @return  void
     */
    public function add()
    {
        $this->form();
    }

    /**
     * Edit form hook.
     *
     * @return void
     */
    public function edit()
    {
        $this->form();
    }

    /**
     * Set the data for blocks form.
     *
     * @return void
     */
    public function form()
    {
        $form = Loader::helper('form');
        $ps = Loader::helper('form/page_selector');

        $v = new \Concrete\Core\Block\View\BlockView(BlockType::getByHandle($this->btHandle));
        $this->addHeaderItem('<link href="'.$v->getBlockURL().'/form.css" rel="stylesheet" type="text/css">');
        $this->addHeaderItem('<script src="'.$v->getBlockURL().'/form.js"></script>');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('javascript', 'jquery');

        // Set the current file.
        if ($this->getImageFileObject()) {
            $this->set('json_file', json_encode([
                'resultsThumbnailImg' => '<img src="'.$this->getImageFileObject()->getThumbnailURL('file_manager_listing').'">',
                'fID' => $this->fID,
            ]));
        }

        // Type size.
        $type = \Concrete\Core\File\Image\Thumbnail\Type\Type::getByHandle($this->getThumbnailTypeHandle())->getDoubledVersion();
        $this->set('thumbnail_dimensions', [
            'width' => $type->getWidth(),
            'height' => $type->getHeight(),
        ]);

        $this->set('image_file', $this->getImageFileObject());
        $this->set('page_selector', $ps);
        $this->set('form', $form);
        $this->set('thumbnail_handle', $this->getThumbnailTypeHandle());
    }

    /**
     * Save the block record.
     *
     * @param  array $args
     * @return bool
     */
    public function save($args)
    {
        if ('none' === $args['link_type']) {
            $args['linkCID'] = 0;
            $args['link'] = '';
            $args['button_text'] = '';
        } elseif ('manual' === $args['link_type']) {
            $args['link'] = LinkAbstractor::translateTo($args['link']);
        }

        return parent::save($args);
    }

    /**
     * Get the file object associated with the block.
     *
     * @return mixed
     */
    public function getImageFileObject()
    {
        return $this->fID > 0 ? File::getByID($this->fID) : null;
    }

    /**
     * Get a formatted url for the resource the block links to.
     *
     * @return string
     */
    public function getLinkUrl()
    {
        if ('page_selector' !== $this->link_type) {
            return LinkAbstractor::translateFrom($this->link);
        } else {
            $page = Page::getByID($this->linkCID);
            
            return Core::make('helper/navigation')->getLinkToCollection($page);
        }
    }

    protected function guessTemplateThumbnailTypeHandle()
    {
        if ($this->getBlockObject()) {
            $bvt = new \Concrete\Core\Block\View\BlockViewTemplate(
                $this->getBlockObject()
            );

            $filename = pathinfo($bvt->getTemplate(), PATHINFO_BASENAME);

            $dirname = basename(pathinfo($bvt->getTemplate(), PATHINFO_DIRNAME));

            if ('view.php' === strtolower($filename) && 'templates' !== strtolower($dirname)) {
                return 'image_box_image_' . $dirname;
            } elseif ('view.php' !== strtolower($filename)) {
                $filename = explode('.', $filename);
                return 'image_box_image_' . array_shift($filename);
            }
        }

        return 'image_box_image';
    }

    protected function rescanThumbnails()
    {
        $file = $this->getImageFileObject();

        if ($file) {
            $file->rescanThumbnails();
        }
    }

    protected function getFileThumbnailHandles()
    {
        $file = $this->getImageFileObject();

        if ($file) {
            return array_map(function($item) {
                return $item->getThumbnailTypeVersionObject()->getHandle();
            }, $file->getThumbnails());
        }

        return [];
    }

    protected function isValidThumbnailHandle($handle)
    {
        // If we don't have a valid thumbnail, generate them.
        $version = \Concrete\Core\File\Image\Thumbnail\Type\Version::getByHandle($handle);

        if (!$version) {
            return false;
        }

        $thumbnails = $this->getFileThumbnailHandles();

        if (!in_array($handle, $thumbnails)) {
            $this->rescanThumbnails();

            $thumbnails = $this->getFileThumbnailHandles();

            if (! in_array($handle, $thumbnails)) {
                return false;
            }
        }

        return true;
    }

    public function getThumbnailTypeHandle()
    {
        $thumbnailHandle = $this->guessTemplateThumbnailTypeHandle();

        if (!$this->isValidThumbnailHandle($thumbnailHandle)) {
            $thumbnailHandle = 'image_box_image';
        }

        return $thumbnailHandle;
    }


    /**
     * Get the associated images URL.
     *
     * @return string
     */
    public function getImageUrl()
    {
        if ($this->fID > 0) {
            $thumbnailHandle = $this->getThumbnailTypeHandle();

            $file = $this->getImageFileObject();
            
            if ($file) {
                return $file->getThumbnailURL($thumbnailHandle.'_2x');
            }
        }

        return '';
    }
}
