<?php
defined('C5_EXECUTE') or die('Access Denied.');
/*
 * Block View Template
 *
 * @package  ImageBlockPackage
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
?>
<div class="image-box">
	<div class="image-box-outer">
		<?php if (! empty($this->controller->getImageUrl())) {
    ?>
			<?php if (! empty($this->controller->getLinkUrl())) {
    ?>
			<a href="<?php echo $this->controller->getLinkUrl();
    ?>">
			<?php 
}
    ?>
			<img src="<?php echo $this->controller->getImageUrl();
    ?>" alt="<?php echo $title ?>"></a>
			<?php if ('' !== trim($this->controller->getLinkUrl())) {
    ?>
			</a>
			<?php 
}
    ?>
		<?php 
} ?>
		<?php if (! empty($title) || ! empty($content)) {
    ?>
			<div class="image-box-inner">
				<h2><?php echo $title;
    ?></h2>
				<span><?php echo $content;
    ?></span>
				<?php if (! empty($button_text) && ! empty($this->controller->getLinkUrl())) {
    ?>
				<a class="image-box-link" href="<?php echo $this->controller->getLinkUrl();
    ?>">
					<?php echo $button_text;
    ?>
				</a>
				<?php 
}
    ?>
			</div>
		<?php 
} elseif (empty($this->controller->getImageUrl())) {
    ?>
			<div class="image-box-inner"><span class="image-box-empty">Empty image box block</span></div>
		<?php 
} ?>
	</div>
</div>