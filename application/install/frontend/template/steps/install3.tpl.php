<?php echo $this->lang('steps.install3.form.mail.title'); ?>
<input type="text" name="admin_mail" value="<?php echo htmlspecialchars(InstallCore::getRequest('admin_mail')); ?>">

<br/><br/>

<?php echo $this->lang('steps.install3.form.passwd.title'); ?>
<input type="password" name="admin_passwd" value="<?php echo htmlspecialchars(InstallCore::getRequest('admin_passwd')); ?>">

<br/><br/>