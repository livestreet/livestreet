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
 * English language file.
 * 
 */
return array(
	"config_file_not_exists"=>"File %%path%% doesn't exist.",
	"config_file_not_writable"=>"File %%path%% is not writable.",
	
	'error_db_invalid'=>'Unable to choose or create Database',
	'error_db_connection_invalid'=>"Can't connect to the Database. Please check configuration details.",
	'error_db_saved'=>'Unable to save data into the DB.',
	'error_db_no_data'=>"Unable to get data from the DB.",
	
	'error_local_config_invalid'=>"Can't find local configuration file - config.local.php.",
	
	'site_name_invalid'=>'Chosen site name is not allowed.',
	'site_description_invalid'=>'Chosen site description is not allowed.',
	'site_keywords_invalid'=>'Chosen keywords are invalid.',
	'skin_name_invalid'=>'Chosen  skin name is invalid.',
	'mail_sender_invalid'=>'Chosen invalid e-mail address.',
	'mail_name_invalid'=>'Chosen invalid notification sender name.',
	'lang_current_invalid'=>'Chosen language is not allowed.',
	'lang_default_invalid'=>'Chosen default language is not allowed.',
	'admin_login_invalid'=>'Invalid Administrator login.',
	'admin_mail_invalid'=>'Invalid Administrator e-mail.',
	'admin_password_invalid'=>'Invalid Administrator password.',
	'admin_repassword_invalid'=>'Invalid password confirmation.',
	
	'ok_db_created'=>'Database created successfully. Configuration saved into configuration file.',
	
	'yes' => 'Yes',
	'no' => 'No',
	'next' => 'Next',
	'prev' => 'Back',
	
	'valid_mysql_server'=>'LiveStreet requires MySQL version 5 or higher.',
	
	'install_title'=>'LiveStreet Installation',
	'step'=>'Step',
	
	'start_paragraph'=>'<p>Welcome to LiveStreet &copy; 1.0 installation. </p><p><b>Notice:</b> You have to rename file - /config/config.local.php.dist to config.local.php and apply read-write (rw) permision to it.</p><p><b>Notice:</b> Make sure that the following directories have rw permissions: /tmp, /logs, /uploads, /templates/compiled, /templates/cache</p>',
	
	'php_params'=>'General PHP Configuration',
	'php_params_version'=>'PHP ver. 5.2.0 or higher',
	'php_params_safe_mode'=>'Safe mode is on',
	'php_params_utf8'=>'UTF8 support in PCRE',
	'php_params_mbstring'=>'Mbstring support',
	'php_params_simplexml'=>'SimpleXML support',
	
	'local_config'=>'Local Configuration',
	'local_config_file'=>'File config.local.php exists and writable',
	'local_temp_dir'=>'Directory /tmp exists and writable',
	'local_logs_dir'=>'Directory /logs exists and writable',
	'local_uploads_dir'=>'Directory /uploads exists and writable',
	'local_templates_dir'=>'Directory /templates/compiled exists and writable',
	'local_templates_cache_dir'=>'Directory /templates/cache exists and writable',
	'local_plugins_dir'=>'Directory /plugins exists and writable',
	
	'db_params'=>'Database (DB) configuration',
	'db_params_host'=>'DB hostname',
	'db_params_port'=>'DB port',
	'db_params_port_notice'=>'It might be a good choice to leave it as 3306 :)',
	'db_params_name'=>'DB name',
	'db_params_create'=>'Create DB',
	'db_params_convert'=>'Convert 0.5.1 DB to 1.0',
	'db_params_convert_from_10'=>'Convert 1.0 DB to 1.0.1',
	'db_params_user'=>'Username',
	'db_params_password'=>'Password',
	'db_params_prefix'=>"Table's prefix",
	'db_params_prefix_notice'=>'This prefix will be appended to all table names',
	'db_params_engine'=>'Tables engine',
	'db_params_engine_notice'=>'InnoDB is recommended',
	
	'error_table_select'=>'Query error whilst getting data from %%table%%',
	'error_database_converted_already'=>'DB structure suits v. 1.0 hence conversion aborted',
	
	'admin_params'=>'Administrator Details Configuration',
	'admin_params_login'=>'Login',
	'admin_params_mail'=>'E-mail',
	'admin_params_pass'=>'Password',
	'admin_params_repass'=>'Confirm password',
	
	'end_paragraph' => 'Congratulations! LiveStreet successfully installed.<br />To ensure that your installation is secure, please delete [Install] directory.<br /><br />You can continue configuration in extended mode.<br /><br /><a href="../">Go to the main page</a><br /><br />',
	'extend_mode'=> 'Extended mode',
	
	'view_params'=> 'HTML view configuration',
	'view_params_name'=> 'Site name',
	'view_params_description'=> 'Site description',
	'view_params_keywords'=> 'Keywords',
	'view_params_skin'=> 'Skin name',
	
	'mail_params'=> 'E-mail notification parameters',
	'mail_params_sender'=> 'Sent from E-mail address (for notifications)',
	'mail_params_name'=> 'Sent from Name (ex. John Doe)',
	
	'general_params'=> 'General Configuration',
	'general_params_close'=> 'Use "closed mode" for your site',
	'general_params_active'=> 'Use registration activation',
	'general_params_invite'=> 'Use registration by invitation',
	
	'language_params'=> 'Language settings',
	'language_params_current'=> 'Current language',
	'language_params_default'=> 'Default language',
	
	'finish_paragraph' => 'Congratulations! LiveStreet successfully installed.<br />To ensure that your installation is secure, please delete [Install] directory.<br /><br /><a href="../">Then to go to the main page by clicking this link.</a>',
);