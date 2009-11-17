<h3>___LANG_DB_PARAMS___</h3>

<input type="hidden" name="install_db_params" value="1" />

<label for="install_db_server">___LANG_DB_PARAMS_HOST___:</label><br />
<p><input type="text" class="input-text" name="install_db_server" value="___INSTALL_DB_SERVER___" id="install_db_server" />
</p>

<label for="install_db_server">___LANG_DB_PARAMS_PORT___:</label><br />
<p><input type="text" class="input-text" name="install_db_port" value="___INSTALL_DB_PORT___" id="install_db_port" />
<span class="input-note">___LANG_DB_PARAMS_PORT_NOTICE___</span></p>

<label for="install_db_name">___LANG_DB_PARAMS_NAME___:</label><br />
<p><input type="text" class="input-text" name="install_db_name" value="___INSTALL_DB_NAME___" id="install_db_name" />
<input type="checkbox" class="checkbox" name="install_db_create" value="1" ___INSTALL_DB_CREATE_CHECK___ /> &mdash; ___LANG_DB_PARAMS_CREATE___<br />
<input type="checkbox" class="checkbox" name="install_db_convert" value="1" ___INSTALL_DB_CONVERT_CHECK___ /> &mdash; ___LANG_DB_PARAMS_CONVERT___
</p>

<label for="install_db_user">___LANG_DB_PARAMS_USER___:</label><br />
<p><input type="text" class="input-text" name="install_db_user" value="___INSTALL_DB_USER___" id="install_db_user" />
</p>

<label for="install_db_password">___LANG_DB_PARAMS_PASSWORD___:</label><br />
<p><input type="text" class="input-text" name="install_db_password" value="___INSTALL_DB_PASSWORD___" id="install_db_password" />
</p>

<label for="install_db_name">___LANG_DB_PARAMS_PREFIX___:</label><br />
<p><input type="text" class="input-text" name="install_db_prefix" value="___INSTALL_DB_PREFIX___" id="install_db_prefix" />
<span class="input-note">___LANG_DB_PARAMS_PREFIX_NOTICE___</span></p>


<label for="install_db_engine">___LANG_DB_PARAMS_ENGINE___:</label><br />
<p>
<select name="install_db_engine" id="install_db_engine" value="___INSTALL_DB_ENGINE___">
	<option value="InnoDB" ___INSTALL_DB_ENGINE_INNODB___>InnoDB</option>
	<option value="MyISAM" ___INSTALL_DB_ENGINE_MYISAM___>MyISAM</option>
</select>
<span class="input-note">___LANG_DB_PARAMS_ENGINE_NOTICE___</span></p>