<!DOCTYPE html>
<html>
<?php echo $this->_render('element', 'header'); ?>
<body onload="initialize()">
	<div id="pagewidth">
		<?php echo $this->_render('element', 'navigation'); ?>
		<div id="content">
		<?php echo $this->content(); ?>
		</div>
		<?php echo $this->_render('element', 'footer'); ?>
	</div>
</body>
</html>