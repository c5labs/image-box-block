<?php
defined('C5_EXECUTE') or die('Access Denied.');
/*
 * Block Form
 *
 * @package  ImageBlockPackage
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/image-box
 */
?>

<style>
	#imageBlockForm {
		padding: 10px 15px;
	}

	#manualLink,
	#pageSelector {
		display: none;
	}

</style>
<div id="imageBlockForm" class="row">
	<fieldset>
		<div class="form-group">
			<label for="fID"><?php echo t('Image')?></label>
			<?php echo $asset_library->image('ccm-b-image', 'fID', t('Choose Image'), $image_file);?>
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
			<?php echo $form->select('link_type', ['none' => 'None', 'manual' => 'Manual', 'page_selector' => 'Page Selector'], $controller->link_type ?: 'page_selector', ['class' => 'form-control']); ?>
			<script>
			$(function(){
				$('select[name=link_type]').change(function(){
					if ('page_selector' === $(this).val()) {
						$('#pageSelector').fadeIn();
						$('#manualLink').fadeIn();
						$('#buttonText').fadeOut();
					} else if ('manual' === $(this).val()) {
						$('#pageSelector').fadeOut();
						$('#manualLink').fadeIn();
						$('#buttonText').fadeIn();
					} else {
						$('#pageSelector').fadeOut();
						$('#manualLink').fadeOut();
						$('#buttonText').fadeOut();
					}
				}).trigger('change');
			});
			</script>
		</div>

		<div id="manualLink" class="form-group">
			<label for="link"><?php echo t('Link')?></label>
			<?php echo $form->text('link', $controller->link, ['class' => 'form-control']); ?>
		</div>

		<div id="pageSelector" class="form-group">
			<label for="linkCID"><?php echo t('Link')?></label>
			<div class="input">	
				<?php echo $page_selector->selectPage('linkCID', $controller->linkCID); ?>
			</div>
		</div>

		<div id="buttonText" class="form-group">
			<label for="button_text"><?php echo t('Button Text')?></label> <span style="color: #ccc;">(optional)</span><br>
			<div class="input">	
				<?php echo $form->text('button_text', $controller->button_text, ['class' => 'form-control']); ?>
			</div>
		</div>

	</fieldset>
</div>