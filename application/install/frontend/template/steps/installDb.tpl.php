<?php $oCurrentStep = $this->get('currentStep'); ?>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_host.title'); ?></label>
<input type="text" name="db.params.host" class="width-full" value="<?php echo $oCurrentStep->getValue('db.params.host', 'localhost'); ?>"></p>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_port.title'); ?></label>
<input type="text" name="db.params.port" class="width-full" value="<?php echo $oCurrentStep->getValue('db.params.port', 3306); ?>"></p>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_name.title'); ?></label>
<input type="text" name="db.params.dbname" class="width-full" value="<?php echo $oCurrentStep->getValue('db.params.dbname', 'social'); ?>"></p>

<?php if (!$oCurrentStep->getParam('hide_create_db')) { ?>
    <p><label><input type="checkbox" name="db_create" value="1">
    <?php echo $this->lang('steps.installDb.form.db_create.title'); ?></label></p>
<?php } ?>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_user.title'); ?></label>
<input type="text" name="db.params.user" class="width-full" value="<?php echo $oCurrentStep->getValue('db.params.user', 'root'); ?>"></p>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_passwd.title'); ?></label>
<input type="password" name="db.params.pass" class="width-full" value="<?php echo $oCurrentStep->getValue('db.params.pass', ''); ?>"></p>

<p><label for=""><?php echo $this->lang('steps.installDb.form.db_prefix.title'); ?></label>
<input type="text" name="db.table.prefix" class="width-full" value="<?php echo $oCurrentStep->getValue('db.table.prefix', 'prefix_'); ?>"></p>