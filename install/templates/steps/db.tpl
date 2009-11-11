<h3>Настройка базы данных</h3>

<input type="hidden" name="install_db_params" value="1" />

<label for="install_db_server">Имя сервера БД или DSN:</label><br />
<p><input type="text" class="input-text" name="install_db_server" value="___INSTALL_DB_SERVER___" id="install_db_server" />
</p>

<label for="install_db_server">Порт сервера БД:</label><br />
<p><input type="text" class="input-text" name="install_db_port" value="___INSTALL_DB_PORT___" id="install_db_port" />
<span class="input-note">Скорее всего правильным решение будет оставить 3306 :)</span></p>

<label for="install_db_name">Название базы данных:</label><br />
<p><input type="text" class="input-text" name="install_db_name" value="___INSTALL_DB_NAME___" id="install_db_name" />
<input type="checkbox" class="checkbox" name="install_db_create" value="1" ___INSTALL_DB_CREATE_CHECK___ /> &mdash; Создать базу данных
</p>

<label for="install_db_user">Имя пользователя:</label><br />
<p><input type="text" class="input-text" name="install_db_user" value="___INSTALL_DB_USER___" id="install_db_user" />
</p>

<label for="install_db_password">Пароль:</label><br />
<p><input type="text" class="input-text" name="install_db_password" value="___INSTALL_DB_PASSWORD___" id="install_db_password" />
</p>

<label for="install_db_name">Префикс таблиц:</label><br />
<p><input type="text" class="input-text" name="install_db_prefix" value="___INSTALL_DB_PREFIX___" id="install_db_prefix" />
<span class="input-note">Указанный префикс будет приставлен к названию всех таблиц</span></p>