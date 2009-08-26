<?php if(!in_array($_POST['defaultView'], array('month', 'year', 'decade'))) $_POST['defaultView'] = 'month'; ?>
<div class="vlaCalendar<?php echo ($_POST['style'] ? ' '.$_POST['style'] : ''); ?>">
	<span class="indication">
		<div class="arrowRight"></div>
		<div class="arrowLeft"></div>
		<span class="label" date="{'day': '<?php echo date('j'); ?>', 'month': '<?php echo date('n'); ?>', 'year': '<?php echo date('Y'); ?>'}">&nbsp;</span>
	</span>
	<div class="container">
		<div class="loaderB"></div>
		<div class="loaderA"><?php include $_POST['defaultView'].'.php'; ?></div>
	</div>
</div>
