<?php echo $this->lang('steps.installAdmin.form.mail.title'); ?>
<input type="text" name="admin_mail" value="<?php echo htmlspecialchars(InstallCore::getRequestStr('admin_mail')); ?>">

<br/><br/>

<?php echo $this->lang('steps.installAdmin.form.passwd.title'); ?>
<input type="password" name="admin_passwd" value="<?php echo htmlspecialchars(InstallCore::getRequestStr('admin_passwd')); ?>">

<br/><br/>