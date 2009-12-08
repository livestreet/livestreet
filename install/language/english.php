<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Английский языковой файл. 
 * Содержит текстовки инсталлятора.
 */
return array(
	"config_file_not_exists"=>"File %%path%% doesn`t exist.",
	"config_file_not_writable"=>"File %%path%% is not writable.",
	
	'error_db_invalid'=>'Impossible to create or to select database.',
	'error_db_connection_invalid'=>'Unable to connect to the database. Check the correctness of the settings you entered before.',
	'error_db_saved'=>'Unable to write data in database.',
	'error_db_no_data'=>"Unable to get data from database.",
	
	'error_local_config_invalid'=>'File of local configuration config.local.php doesn`t exist.',
	
	'site_name_invalid'=>'Invalid site name given.',
	'site_description_invalid'=>'Invalid site description given.',
	'site_keywords_invalid'=>'Invalid keywords given.',
	'skin_name_invalid'=>'Invalid skin name given.',
	'mail_sender_invalid'=>'Invalid e-mail given.',
	'mail_name_invalid'=>'Invalid senders` name given.',
	'lang_current_invalid'=>'Invalid language given.',
	'lang_default_invalid'=>'Invalid default language given.',
	'admin_login_invalid'=>'Administrators` login is invalid.',
	'admin_mail_invalid'=>'Administrators` e-mail is invalid.',
	'admin_password_invalid'=>'Administrators` password is invalid.',
	'admin_repassword_invalid'=>'Confirm password does not match the password itself.',
	
	'ok_db_created'=>'The database was successfully created. The data recorded in the configuration file.',
	
	'yes' => 'Yes',
	'no' => 'No',
	'next' => 'Next',
	'prev' => 'Previous',
	
	'valid_mysql_server'=>'For LiveStreet using MySQL higher than 5 is required.',
	
	'install_title'=>'Install LiveStreet',
	'step'=>'Step',
	
	'start_paragraph'=>'<p>Welcome to LiveStreet &copy; 0.4 installer. Read the results and follow the prompts.</p><p><b>Attention.</b> or successful installation you have to rename the file /config/config.local.php.dist to config.local.php and make it writable.</p><p><b>Attention.</b> Directories /tmp, /logs, /uploads, /templates/compiled, /templates/cache should be writable.</p>',
	
	'php_params'=>'General PHP configuration',
	'php_params_version'=>'PHP higher than 5.0',
	'php_params_safe_mode'=>'Safe mode off',
	'php_params_utf8'=>'UTF8 support in PCRE',
	'php_params_mbstring'=>'Mbstring support',
	
	'local_config'=>'Local configuration',
	'local_config_file'=>'File config.local.php exists and is writable',
	'local_temp_dir'=>'Directory /tmp exists and is writable',
	'local_logs_dir'=>'Directory /logs exists and is writable',
	'local_uploads_dir'=>'Directory /uploads exists and is writable',
	'local_templates_dir'=>'Directory /templates/compiled exists and is writable',
	'local_templates_cache_dir'=>'Directory /templates/cache exists and is writable',
	
	'db_params'=>'Database settings',
	'db_params_host'=>'Database server',
	'db_params_port'=>'Database port',
	'db_params_port_notice'=>'For default settings use 3306',
	'db_params_name'=>'Database name',
	'db_params_create'=>'Create database',
	'db_params_convert'=>'To convert database from 0.3.1 to 0.4',
	'db_params_user'=>'User name',
	'db_params_password'=>'Password',
	'db_params_prefix'=>'Table prefix',
	'db_params_prefix_notice'=>'Given prefix will be beginning of all tables',
	'db_params_engine'=>'Tables engine',
	'db_params_engine_notice'=>'InnoDB is recommended',
	
	'error_table_select'=>'Error select query data from a table %%table%%',
	'error_database_converted_already'=>'Given database already converted to v.0.4',
	
	'admin_params'=>'Administrator`s settings',
	'admin_params_login'=>'Login',
	'admin_params_mail'=>'E-mail',
	'admin_params_pass'=>'Password',
	'admin_params_repass'=>'One more time',
	
	'end_paragraph' => 'Congratulations! LiveStreet is successfully installed.<br />You can choose advanced install mode.<br /><br /><a href="/">Main site page</a><br /><br />',
	'extend_mode'=> 'Advanced mode',
	
	'view_params'=> 'HTML view settings',
	'view_params_name'=> 'Site name',
	'view_params_description'=> 'Site description',
	'view_params_keywords'=> 'Keywords',
	'view_params_skin'=> 'Skin',
	
	'mail_params'=> 'Mail settings',
	'mail_params_sender'=> 'Sender`s e-mail',
	'mail_params_name'=> 'Sender`s name',
	
	'general_params'=> 'General settings',
	'general_params_close'=> 'Use a closed mode',
	'general_params_active'=> 'Use the activation of the registration',
	'general_params_invite'=> 'Use invites for registration',
	
	'language_params'=> 'Language settings',
	'language_params_current'=> 'Current language',
	'language_params_default'=> 'Default language',
	
	'finish_paragraph' => 'Congratulations! LiveStreet is successfully installed.<br />To ensure the safety of the system, remove the directory Install.<br />',
);