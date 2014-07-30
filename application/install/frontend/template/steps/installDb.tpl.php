<?php
$oCurrentStep=$this->get('currentStep');
?>


<?php echo $this->lang('steps.installDb.form.db_host.title'); ?>
<input type="text" name="db.params.host" value="<?php echo $oCurrentStep->getValue('db.params.host','localhost'); ?>">

<br/><br/>

<?php echo $this->lang('steps.installDb.form.db_port.title'); ?>
<input type="text" name="db.params.port" value="<?php echo $oCurrentStep->getValue('db.params.port',3306); ?>">

<br/><br/>

<?php echo $this->lang('steps.installDb.form.db_name.title'); ?>
<input type="text" name="db.params.dbname" value="<?php echo $oCurrentStep->getValue('db.params.dbname','social'); ?>">

<?php if (!$oCurrentStep->getParam('hide_create_db')) { ?>
	<br/><br/>

	<?php echo $this->lang('steps.installDb.form.db_create.title'); ?>
	<input type="checkbox" name="db_create" value="1">
<?php } ?>

<br/><br/>

<?php echo $this->lang('steps.installDb.form.db_user.title'); ?>
<input type="text" name="db.params.user" value="<?php echo $oCurrentStep->getValue('db.params.user','root'); ?>">

<br/><br/>

<?php echo $this->lang('steps.installDb.form.db_passwd.title'); ?>
<input type="text" name="db.params.pass" value="<?php echo $oCurrentStep->getValue('db.params.pass',''); ?>">

<br/><br/>

<?php echo $this->lang('steps.installDb.form.db_prefix.title'); ?>
<input type="text" name="db.table.prefix" value="<?php echo $oCurrentStep->getValue('db.table.prefix','prefix_'); ?>">