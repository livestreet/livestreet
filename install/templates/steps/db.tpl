<h3>___LANG_DB_PARAMS___</h3>

<input type="hidden" name="install_db_params" value="1" />

<p><label for="install_db_server">___LANG_DB_PARAMS_HOST___:</label>
<input type="text" class="input-text input-width-300" class="input-text" name="install_db_server" value="___INSTALL_DB_SERVER___" id="install_db_server" />
</p>

<p><label for="install_db_server">___LANG_DB_PARAMS_PORT___:</label>
<input type="text" class="input-text input-width-300" name="install_db_port" value="___INSTALL_DB_PORT___" id="install_db_port" />
<small class="note">___LANG_DB_PARAMS_PORT_NOTICE___</small></p>

<p><label for="install_db_name">___LANG_DB_PARAMS_NAME___:</label>
<input type="text" class="input-text input-width-300" name="install_db_name" value="___INSTALL_DB_NAME___" id="install_db_name" />
</p>

<p>
	<label><input type="checkbox" class="input-checkbox" name="install_db_create" value="1" ___INSTALL_DB_CREATE_CHECK___ /> ___LANG_DB_PARAMS_CREATE___</label>
	<label><input type="checkbox" class="input-checkbox" name="install_db_convert" value="1" ___INSTALL_DB_CONVERT_CHECK___ /> ___LANG_DB_PARAMS_CONVERT___</label>
</p>

<p><label for="install_db_user">___LANG_DB_PARAMS_USER___:</label>
<input type="text" class="input-text input-width-300" name="install_db_user" value="___INSTALL_DB_USER___" id="install_db_user" />
</p>

<p><label for="install_db_password">___LANG_DB_PARAMS_PASSWORD___:</label>
<input type="text" class="input-text input-width-300" name="install_db_password" value="___INSTALL_DB_PASSWORD___" id="install_db_password" />
</p>

<p><label for="install_db_name">___LANG_DB_PARAMS_PREFIX___:</label>
<input type="text" class="input-text input-width-300" name="install_db_prefix" value="___INSTALL_DB_PREFIX___" id="install_db_prefix" />
<small class="note">___LANG_DB_PARAMS_PREFIX_NOTICE___</small></p>


<p><label for="install_db_engine">___LANG_DB_PARAMS_ENGINE___:</label>
<select name="install_db_engine" id="install_db_engine" value="___INSTALL_DB_ENGINE___" class="input-text input-width-300">
	<option value="InnoDB" ___INSTALL_DB_ENGINE_INNODB___>InnoDB</option>
	<option value="MyISAM" ___INSTALL_DB_ENGINE_MYISAM___>MyISAM</option>
</select>
<small class="note">___LANG_DB_PARAMS_ENGINE_NOTICE___</small></p>