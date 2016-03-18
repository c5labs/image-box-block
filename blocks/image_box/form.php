<?php
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$ps = Loader::helper('form/page_selector');
$al = Loader::helper('concrete/asset_library');
$bf = null;

if ($controller->fID > 0) { 
	$bf = File::getByID($controller->fID);
}


$args = array();
$args['minWidth']= $args['maxWidth'] = 270;
$args['minHeight'] = $args['maxHeight'] = 300;
?>

<!-- A few styles !-->
<style>
	input,
	textarea {
		width: 100%;
	}

	#manualLink,
	#pageSelector {
		display: none;
		margin-bottom: 30px;
	}

	input,
	textarea,
	select {
		padding: 8px 10px;
		box-sizing: border-box;
	}

</style>
<div>
	<strong><?php echo t('Image')?></strong><br>
	<div class="input">	
		<?php echo $al->image('ccm-b-image', 'fID', t('Choose Image'), $bf, $args);?>
	</div>
</div>

<br>

<div>
	<strong><?php echo t('Title')?></strong><br>
	<div class="input">	
		<?php echo $form->text('title', $controller->title, array('class' => 'form-control')); ?>
	</div>
</div>

<br>

<div>
	<strong><?php echo t('Content')?></strong> <span style="color: #ccc;">(optional)</span><br>
	<div class="input">	
		<?php echo $form->textarea('content', $controller->content, array('class' => 'form-control')); ?>
	</div>
</div>

<br>

<div>
	<strong><?php echo t('Link Type')?></strong><br>
	<div class="input">	
		<?php echo $form->select('link_type', array('manual' => 'Manual', 'page_selector' => 'Page Selector'), $controller->link_type ?: 'page_selector', array('class' => 'form-control')); ?>
	</div>
	<script>
	$(function(){
		$('select[name=link_type]').change(function(){
			if ('page_selector' === $(this).val()) {
				$('#pageSelector').fadeIn();
				$('#manualLink').fadeOut();
			} else {
				$('#pageSelector').fadeOut();
				$('#manualLink').fadeIn();
			}
		}).trigger('change');
	});
	</script>
</div>

<br>

<div id="manualLink">
	<strong><?php echo t('Link')?></strong><br>
	<div class="input">	
		<?php echo $form->text('link', $controller->link, array('class' => 'form-control')); ?>
	</div>
</div>

<div id="pageSelector">
	<strong><?php echo t('Link')?></strong><br>
	<div class="input">	
		<?php echo $ps->selectPage('linkCID', $controller->linkCID); ?>
	</div>
</div>

<div>
	<strong><?php echo t('Button Text')?></strong> <span style="color: #ccc;">(optional)</span><br>
	<div class="input">	
		<?php echo $form->text('button_text', $controller->button_text, array('class' => 'form-control')); ?>
	</div>
</div>