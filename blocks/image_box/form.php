<?php
defined('C5_EXECUTE') or die('Access Denied.');
/*
 * Block Form
 *
 * @package  ImageBlockPackage
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com/add-ons/image-box
 */
?>
<div id="imageBlockForm" class="row">
	<fieldset style="width: 100%;">
		<div class="form-group">
			<label for="fID"><?php echo t('Image')?></label>
			<?php echo $form->hidden('fID', $fID); ?>
			<div id="imageHolderWrapper" data-thumbnail-type-handle="<?php echo $thumbnail_handle; ?>" style="background-color: #eee; min-height: 150px;">
				<div id="imageHolderSelect" style="">
					<span style="">Choose Image</span>
				</div>
				<div id="imageHolder" style="text-align: center;"></div>
			</div>
			<button id="cropBtn" class="btn btn-primary" data-fid="<?php echo $fID; ?>" type="button" style="margin-top: .5rem;"><span class="fa fa-undo"></span> Re-crop / position</button>
		</div>

		<div class="form-group">
			<label for="title"><?php echo t('Title')?></label>
			<?php echo $form->text('title', $controller->title, ['class' => 'form-control']); ?>
		</div>

		<div class="form-group">
			<label for="content"><?php echo t('Description')?></label> <span style="color: #ccc;">(<?php echo t('Optional')?>)</span>
			<?php echo $form->textarea('content', $controller->content, ['class' => 'form-control']); ?>
		</div>

		<div class="form-group">
			<label for="link_type"><?php echo t('Link Type')?></label>
			<?php echo $form->select('link_type', ['none' => 'None', 'manual' => 'Manual', 'page_selector' => 'Page Selector'], $controller->link_type ?: 'none', ['class' => 'form-control']); ?>
		</div>

		<div id="manualLink" class="form-group" style="display: none;">
			<label for="link"><?php echo t('Link')?></label>
			<?php echo $form->text('link', $controller->getLinkUrl(), ['class' => 'form-control']); ?>
		</div>

		<div id="pageSelector" class="form-group" style="display: none;">
			<label for="linkCID"><?php echo t('Link')?></label>
			<?php echo $page_selector->selectPage('linkCID', $controller->linkCID); ?>
		</div>

		<div id="buttonText" class="form-group" style="display: none;">
			<label for="button_text"><?php echo t('Button Text')?></label> <span style="color: #ccc;">(<?php echo t('Optional')?>)</span>
			<?php echo $form->text('button_text', $controller->button_text, ['class' => 'form-control']); ?>
		</div>

	</fieldset>
</div>

<script>
	window.image_block_editor = $.extend(window.image_block_editor, {
		'dimensions': <?php echo json_encode($thumbnail_dimensions); ?>,
		'crop_prompt': <?php echo (\Config::get('app.image-box-block.crop', true) ? 'true' : 'false'); ?>
	});

	<?php if (isset($json_file)) { ?>
		window.image_block_editor.setImage(<?php echo $json_file; ?>);
	<?php } ?>
</script>