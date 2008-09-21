{include file='header.tpl'}




<BR>
<DIV class=tagsblock>

<h3>Статус</h3>
Текущая версия: <b>LiveStreet 0.1.2 &mdash; 18.09.2008</b>

<br>
<br>
<h3>Условия использования</h3>
Проект распространяется под лицензией <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html">GPLv2</a>(<b>GNU General Public License</b>).
Дополнительное просьба для тех, кто использует движок LiveStreet: присутствие ссылки <b>на главной</b> странице, ведущей на сайт <a href="http://livestreet.ru">livestreet.ru</a>

<br><br>
<h3>Скачать</h3>
Скачать последнюю версию всегда можно с <a href="http://sourceforge.net" target="_blank">SourceForge.net</a> на <a href="http://sourceforge.net/projects/livestreet/">странице проекта LiveStreet</a>

<br><br>
<h3>Установка</h3>
Для корректной работы движка необходим <b>PHP</b> не ниже <b>5</b> версии, <b>MySQL</b> с поддержкой <b>UTF-8</b> и <b>InnoDB</b>(хотя будет работать и на <b>MyISAM</b>, но возможны нарушения целостности данных).
Также для PHP необходимо установить расширение <b>mbstring</b>, для корректной работы с русскими строками в UTF-8.<br>
Что нужно сделать:
<ol>
<li>Скачать</li>
<li>Разархивировать в нужный каталог вашего сайта</li>
<li>Выполнить SQL дамп(<b>sql.sql</b>), предварительно создав базу данных</li>
<li>Настроить коннект к БД(<b>config/config.db.php</b>)</li>
<li>Дать права <b>777</b> каталогам: <b>logs, uploads, templates\compiled, templates\cache</b></li>
<li>Готово!</li>
</ol>


</DIV>


{include file='footer.tpl'}

