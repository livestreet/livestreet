<script type="text/javascript">
	ls.registry.set('tags-help-target-id','{$sTagsTargetId}');
</script>
<a href="#" class="link-dotted help-link" onclick="jQuery('#tags-help').toggle(); return false;">{$aLang.topic_create_text_notice}</a>

<dl class="help clearfix" id="tags-help">
	<dt class="help-col help-wide">
		<h3>Специальные теги</h3>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;cut&gt;</a></h4>
			Используется для больших текстов, скрывает под кат часть текста, следующую за тегом (будет написано «Читать дальше»).
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;cut name="Подробности"&gt;</a></h4>
			Так можно превратить надпись «Читать дальше» в любой текст.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link" data-insert="&lt;video&gt;&lt;/video&gt;">&lt;video&gt;http://...&lt;/video&gt;</a></h4>
			Добавляет в пост видео со следующих хостингов: YouTube, RuTube, Google video, Vimeo, Я.Видео и Видео@Mail.ru
			Вставляйте между тегами только прямую ссылку на видеоролик.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link" data-insert="&lt;ls user=&quot;&quot; /&gt;">&lt;ls user="Ник" /&gt;</a></h4>
			Выводит имя пользователя посреди текста.
		</div>
	</dt>
	<br />
	<br />
	<dt class="help-col help-wide">
		<h3>Стандартные теги</h3>
	</dt>
	<dt class="help-col help-left">
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;h4&gt;&lt;/h4&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;h5&gt;&lt;/h5&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;h6&gt;&lt;/h6&gt;</a></h4>
			Заголовки разного уровня.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;img src="" /&gt;</a></h4>
			Вставка изображения, в атрибуте src нужно указывать полный путь к изображению. Возможно выравнивание картинки атрибутом align.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link" data-insert="&lt;a href=&quot;&quot;&gt;&lt;/a&gt;">&lt;a href="http://..."&gt;Ссылка&lt;/a&gt;</a></h4>
			Вставка ссылки, в атрибуте href указывается желаемый интернет-адрес или якорь (anchor) для навигации по странице.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;b&gt;&lt;/b&gt;</a></h4>
			Выделение важного текста, на странице выделяется жирным начертанием.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;i&gt;&lt;/i&gt;</a></h4>
			Выделение важного текста, на странице выделяется курсивом.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;s>&lt;/s&gt;</a></h4>
			Текст между этими тегами будет отображаться как зачеркнутый.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;u&gt;&lt;/u&gt;</a></h4>
			Текст между этими тегами будет отображаться как подчеркнутый.
		</div>
	</dt>
	<dd class="help-col help-right">
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;hr /&gt;</a></h4>
			Тег для вставки горизонтальной линии.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;blockquote&gt;&lt;/blockquote&gt;</a></h4>
			Используйте этот тег для выделения цитат.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;table>&lt;/table&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;th>&lt;/th&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;td>&lt;/td&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;tr>&lt;/tr&gt;</a></h4>
			Набор тегов для создания таблицы. Тег &lt;td&gt; обозначает ячейку таблицы, тег &lt;th&gt; - ячейку в заголовке, &lt;tr&gt; - строчку таблицы. Все содержимое таблицы помещайте в тег &lt;table&gt;.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;ul&gt;&lt;/ul&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;li&gt;&lt;/li&gt;</a></h4>
			Ненумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ul&gt;.
		</div>
		<div class="help-item">
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;ol&gt;&lt;/ol&gt;</a></h4>
			<h4><a href="#" class="link-dashed js-tags-help-link">&lt;li&gt;&lt;/li&gt;</a></h4>
			Нумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ol&gt;.
		</div>
	</dd>
</dl>