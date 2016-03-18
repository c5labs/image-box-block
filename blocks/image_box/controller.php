<?php
namespace Concrete\Package\ConcreteImageBox\Block\ImageBox;

use Concrete\Core\Block\BlockController;

/**
 * The controller for the box block.
 *
 * @package Blocks
 * @subpackage Content
 * @author Oliver Green <oliver@devisegraphics.co.uk>
 * @license GPL
 *
 */
class Controller extends BlockController {

	protected $btTable = 'btImageBox';
	protected $btInterfaceWidth = "600";
	protected $btInterfaceHeight = "365";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME; //until manually updated or cleared
	protected $btDefaultSet = 'basic';

	public function getBlockTypeDescription() {
		return t("Formatted image, title & button display.");
	}

	public function getBlockTypeName() {
		return t("Image box");
	}

	public function getFileID() {
		return $this->fID;
	}

	public function getFileObject() {
		return \File::getByID($this->fID);
	}

	public function getLinkUrl() {
		if ('page_selector' !== $this->link_type) {
			return $this->link;
		} else {
			$path = \Page::getByID($this->linkCID)->getCollectionPath();

			return \View::url($path ?: '/');
		}
	}

	public function getImageUrl() 
	{
		if ($this->fID > 0)
		{
			$f = \File::getByID($this->fID)->getRecentVersion();
			return $f->getUrl();
		}

		return '';
	}
}
