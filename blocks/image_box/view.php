<?php
	
?>
<div class="box">
	<div>
		<?php if ('' !== trim($this->controller->getImageUrl())) { ?>
		<img src="<?php echo $this->controller->getImageUrl(); ?>" alt="<?php echo $this->controller->title ?>"></a>
		<?php } ?>
		<span class="content">
			<h2><?php echo $this->controller->title; ?></h2>
			<span><?php echo $this->controller->content; ?></span>
			<?php if('' !== trim($this->controller->button_text) && '' !== trim($this->controller->getLinkUrl())) { ?>
			<a class="btn" href="<?php echo $this->controller->getLinkUrl(); ?>">
				<?php echo $this->controller->button_text; ?>
				<i class="fa fa-lg fa-angle-double-right"></i>
			</a>
			<?php } ?>
		</span>
	</div>
</div>