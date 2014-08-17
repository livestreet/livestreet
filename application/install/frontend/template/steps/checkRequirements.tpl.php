<?php
if ($requirements=$this->get('requirements')) { ?>

	Хостинг не удовлетворяет минимальным требованиям.<br/>

	<?php foreach($requirements as $requirement) { ?>

			<div>
				<div>
					<?php echo $this->lang('steps.checkRequirements.requirements.'.$requirement['name'].'.title'); ?> &mdash; <?php echo $requirement['current']; ?>
				</div>

				<div>
					<?php echo $this->lang('steps.checkRequirements.requirements.'.$requirement['name'].'.solution'); ?>
				</div>
			</div>

	<?php } ?>

<?php } else { ?>

	Здесь показываем процесс-лоадер на пару секунд. Далее автоматически переходим на следующий шаг.
	<script type="text/javascript">
		jQuery(function($){
			install.goNextStep();
		});
	</script>

<?php } ?>