<html>

<head>
	<link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/main.css" />
	<script type="text/javascript" src="./frontend/template/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="./frontend/template/assets/js/main.js"></script>
</head>


<div class="header">
	header
	<br/>

	<?php if (!$this->get('install_reset_hide')) { ?>
		<a href="./?reset=1"><?php echo $this->lang('install_reset'); ?></a>
	<?php } ?>

</div>


<div class="content">
	<form action="" method="post">

		<div>
			<?php if ($currentStep=$this->get('currentStep')) { ?>
				<?php echo $currentStep->getGroupTitle(); ?> &mdash; <?php echo $currentStep->getStepTitle(); ?>

				<?php if ($errors=$currentStep->getErrors()) { ?>
				<div class="errors">
					<?php foreach($errors as $sMsg) { ?>

						<?php echo $sMsg; ?><br/>

					<?php } ?>
				</div>
				<?php } ?>

			<?php } ?>
		</div>

		<div class="content-body">
			<?php echo $this->get('content'); ?>
		</div>

		<div>

			<?php if (!$this->get('previous_step_hide')) { ?>
				<button type="submit" name="action_previous" id="action_previous" <?php if ($this->get('previous_step_disable')) { ?>disabled="disabled" <?php } ?> >previous</button>
			<?php } ?>

			<?php if (!$this->get('next_step_hide')) { ?>
				<button type="submit" name="action_next" id="action_next" <?php if ($this->get('next_step_disable')) { ?>disabled="disabled" <?php } ?> >next</button>
			<?php } ?>

		</div>

	</form>
</div>


<div class="footer">
	footer
</div>

</html>