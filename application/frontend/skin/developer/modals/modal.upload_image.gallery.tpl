{**
 * Добавление медиа-файла / Список файлов
 *
 * @styles css/modals.css
 *}

<div id="upload-gallery-image" class="media-gallery js-media-gallery">
	{* Drag & drop зона *}
	<label for="upload-file" class="form-input-file form-input-file-media js-media-upload-area">
		<span>Перетащите сюда файлы или кликните по этому тексту</span>
		<input type="file" name="filedata" class="js-media-upload-file" id="upload-file" multiple>
	</label>

	{* Галерея *}
	<div class="media-gallery-content">
		{* Список файлов *}
		<ul class="media-gallery-list js-media-upload-gallery-list"></ul>

		{* Информация о выделенном файле *}
		<div class="media-gallery-item-info js-media-item-info">
			{* Блок отображаемый когда нет выделенных файлов *}
			<div class="media-item-info-empty js-media-item-info-empty" style="display: none;">Выберите файл</div>

			{* Основная информация о файле *}
			<div class="js-media-detail-area" style="display: none;">
				{* Превью *}
				<img src="" alt="" class="media-gallery-item-info-image js-media-detail-preview" width="100" height="100">

				{* Информация *}
				<ul class="mb-20">
					<li><strong class="word-wrap js-media-detail-name"></strong></li>
					<li class="js-media-detail-date"></li>
					<li><span class="js-media-detail-dimensions"></span>, <span class="js-media-detail-file-size"></span></li>
					<li>
						<a href="#" class="link-dotted js-media-item-info-remove">{$aLang.delete}</a>
						&nbsp;&nbsp;
						{if $aTargetParams.allow_preview}
							<a href="#" class="link-dotted js-media-item-info-create-preview">Создать превью</a>
							<a href="#" class="link-dotted js-media-item-info-remove-preview">Удалить превью</a>
						{/if}
					</li>
				</ul>

				{* Описание *}
				{include file='forms/fields/form.field.text.tpl'
						 sFieldName  = 'title'
						 sFieldLabel = $aLang.uploadimg_title}
	        </div>

			{* Основные настройки *}
			<div class="js-media-settings-mode" id="media-settings-mode-insert" style="display: none;">
				<h3>Опции вставки</h3>

				{* Выравнивание *}
				{include file='forms/fields/form.field.select.tpl'
						 sFieldName  = 'align'
						 sFieldLabel = $aLang.uploadimg_align
						 aFieldItems = $aSelectImageAlign}

	            {* Размер *}
	            {$aSelectImageSizes = [ [ 'value' => 'original', 'text' => 'Оригинал' ] ]}
				{$aImageSizes = Config::Get('module.media.image.sizes')}

				{foreach $aImageSizes as $aSize}
					{$aSelectImageSizes[] = [
						'value' => "{$aSize.w}x{$aSize.h}{if $aSize.crop}crop{/if}",
						'text' => "{$aSize.w} × {if $aSize.h}{$aSize.h}{else}*{/if}"
					]}
				{/foreach}

				{include file='forms/fields/form.field.select.tpl'
						 sFieldName          = 'size'
						 sFieldLabel         = 'Размер'
						 sFieldSelectedValue = $_aRequest.blog_category
						 aFieldItems         = $aSelectImageSizes}
			</div>

			{* Опции фотосета *}
	        <div class="js-media-settings-mode" id="media-settings-mode-create-photoset" style="display: none;">
				<h3>Опции фотосета</h3>

				{* Показывать ленту с превьюшками *}
				{include file='forms/fields/form.field.checkbox.tpl'
						 sFieldName    = 'use_thumbs'
						 bFieldChecked = true
						 sFieldLabel   = 'Показывать ленту с превьюшками'}

				{* Показывать описания фотографий *}
				{include file='forms/fields/form.field.checkbox.tpl'
						 sFieldName    = 'show_caption'
						 sFieldLabel   = 'Показывать описания фотографий'}
	        </div>
		</div>
	</div>
</div>