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
 * Contains text messages of installer.
 */
return array(
	"config_file_not_exists"=>"File %%path%% does not exist.",
	"config_file_not_writable"=>"File %%path%% is not writable.",
	
	'error_db_invalid'=>'Unable to select or create a database',
	'error_db_connection_invalid'=>'Connection to the database failed. Check if the settings you entered are correct.',
	'error_db_saved'=>'Data could not be saved in the database.',
	'error_db_no_data'=>"Data from the database could not be extracted.",
	
	'error_local_config_invalid'=>'Local configuration file config.local.php is not found.',
	
	'site_name_invalid'=>'Indicated site name is invalid.',
	'site_description_invalid'=>'Indicated site description is invalid.',
	'site_keywords_invalid'=>'Indicated keywords are invalid.',
	'skin_name_invalid'=>'Indicated template name is invalid.',
	'mail_sender_invalid'=>'Indicated e-mail is invalid.',
	'mail_name_invalid'=>'indicated name mail notice sender name is invalid.',
	'lang_current_invalid'=>'Indicated language is invalid.',
	'lang_default_invalid'=>'Indicated default language is invalid.',
	'admin_login_invalid'=>'Administrator\'s login is invalid.',
	'admin_mail_invalid'=>'Administrator\'s e-mail is invalid.',
	'admin_password_invalid'=>'Administrator\'s password is invalid.',
	'admin_repassword_invalid'=>'Password confirmation does not match the password.',
	
	'ok_db_created'=>'Database has been successfully created. All data has been recorded into the configuration file.',
	
	'yes' => 'Yes',
	'no' => 'No',
	'next' => 'Next',
	'prev' => 'Previous',
	
	'valid_mysql_server'=>'For proper functioning of LiveStreet, server MySQL of at least version 5 is required.',
	
	'install_title'=>'LiveStreet installation',
	'step'=>'Step',
	
	'start_paragraph'=>'<p>Welcome to the LiveStreet installation &copy; 0.4.1. See the results and follow the prompt messages.</p><p><b>Notice.</b> To successfully complete the installation, please rename the file /config/config.local.php.dist to config.local.php and enable the file for entering data.</p><p><b>Notice.</b> Directories /tmp, /logs, /uploads, /templates/compiled, /templates/cache must be enabled for entering data.</p>',
	
	'php_params'=>'Main PHP parameters',
	'php_params_version'=>'At least 5.1.2 PHP version',
	'php_params_safe_mode'=>'Safe mode is turned off',
	'php_params_utf8'=>'Compatibility of UTF8 by PCRE',
	'php_params_mbstring'=>'Mbstring compatibility',
	'php_params_simplexml'=>'SimpleXML compatibility',
	
	'local_config'=>'Local configuration',
	'local_config_file'=>'File config.local.php exists and is available for entering data',
	'local_temp_dir'=>'Directory /tmp exists and is available for entering data',
	'local_logs_dir'=>'Directory /logs exists and is available for entering data',
	'local_uploads_dir'=>'Directory /uploads exists and is available for entering data ',
	'local_templates_dir'=>'Directory /templates/compiled exists and is available for entering data ',
	'local_templates_cache_dir'=>'Directory /templates/cache exists and is available for entering data',
	'local_plugins_dir'=>'Directory /plug-in exists and is available for entering data',
	
	'db_params'=>'Database parameters',
	'db_params_host'=>'Database host server name',
	'db_params_port'=>'Database server port',
	'db_params_port_notice'=>'Most likely the correct solution should be left as 3306 :)',
	'db_params_name'=>'Database name',
	'db_params_create'=>'Create a dababase',
	'db_params_convert'=>'Convert the database 0.3.1 into 0.4.1',
	'db_params_user'=>'User name',
	'db_params_password'=>'Password',
	'db_params_prefix'=>'Table prefix',
	'db_params_prefix_notice'=>'Indicated prefix will be assigned to all tables names',
	'db_params_engine'=>'Tables engine',
	'db_params_engine_notice'=>'It\'s recommended to use InnoDB',
	
	'error_table_select'=>'Request for table %%table%% data selection failed',
	'error_database_converted_already'=>'Conversion has been cancelled as the database structure matches the version 0.4',
	
	'admin_params'=>'Administrator\'s parameters',
	'admin_params_login'=>'Login',
	'admin_params_mail'=>'E-mail',
	'admin_params_pass'=>'Password',
	'admin_params_repass'=>'Re-enter the password',
	
	'end_paragraph' => 'Congratulations! LiveStreet has been successfully installed.<br /> For safe system work, remove the directory Install.<br /><br />You can continue setup in an extended mode.<br /><br /><a href="../">Go to the main page</a><br /><br />',
	'extend_mode'=> 'Extended mode',
	
	'view_params'=> 'HTML view parameters',
	'view_params_name'=> 'Site name',
	'view_params_description'=> 'Site description',
	'view_params_keywords'=> 'Keywords',
	'view_params_skin'=> 'Template name',
	
	'mail_params'=> 'Mail notice parameters',
	'mail_params_sender'=> 'Notice sender e-mail',
	'mail_params_name'=> 'Notice sender name',
	
	'general_params'=> 'General parameters',
	'general_params_close'=> 'Use a closed mode of site\'s work',
	'general_params_active'=> 'Use activation during registration ',
	'general_params_invite'=> 'Use registration mode upon invitation',
	
	'language_params'=> 'Language parameters',
	'language_params_current'=> 'Current language',
	'language_params_default'=> 'Default language',
	
	'finish_paragraph' => 'Congratulations! LiveStreet has been successfully installed.<br /> For safe system work, remove the directory Install.<br /><br /><a href="../">Go to the main page</a>',
);