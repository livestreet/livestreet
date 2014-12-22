<p><label for=""><?php echo $this->lang('steps.installAdmin.form.mail.title'); ?></label>
<input type="text" name="admin_mail" value="<?php echo htmlspecialchars(InstallCore::getRequestStr('admin_mail')); ?>"></p>

<p><label for=""><?php echo $this->lang('steps.installAdmin.form.passwd.title'); ?></label>
<input type="password" name="admin_passwd" value="<?php echo htmlspecialchars(InstallCore::getRequestStr('admin_passwd')); ?>"></p>