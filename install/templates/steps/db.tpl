<p>Заполните указаные ниже поля.</p>
<br />
<h2>Настройка базы данных</h2>
<input type="hidden" name="install_db_params" value="1" />
<table>
	<tr>
		<td width="250px">Имя сервера БД или DSN</td>
		<td><input type="text" name="install_db_server" value="___INSTALL_DB_SERVER___" /></td>
	</tr>
	<tr>
		<td>Порт сервера БД</td>
		<td><input type="text" name="install_db_port" value="___INSTALL_DB_PORT___" /></td>
	</tr>
	<tr>	
		<td>Название базы данных</td>
		<td>
			<input type="text" name="install_db_name" value="___INSTALL_DB_NAME___" /><br />
			<input type="checkbox" name="install_db_create" value="1" ___INSTALL_DB_CREATE_CHECK___ /> <i>Создать базу данных</i>
		</td>
	</tr>
	<tr>	
		<td>Имя пользователя</td>
		<td><input type="text" name="install_db_user" value="___INSTALL_DB_USER___" /></td>
	</tr>
	<tr>	
		<td>Пароль к БД</td>
		<td><input type="text" name="install_db_password" value="___INSTALL_DB_PASSWORD___" /></td>
	</tr>
	<tr>	
		<td>Префикс таблиц</td>
		<td><input type="text" name="install_db_prefix" value="___INSTALL_DB_PREFIX___" /></td>
	</tr>	
</table>