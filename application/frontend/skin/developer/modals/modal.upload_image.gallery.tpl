{**
 * Добавление медиа-файла / Список изображений
 *
 * @styles css/modals.css
 *}

<div id="upload-gallery-image">

<label for="upload-file" class="form-input-file form-input-file-media js-media-upload-area">
	<span>Перетащите сюда файлы или кликните по этому тексту</span>
	<input type="file" name="filedata" class="js-media-upload-file" id="upload-file" multiple>
</label>

<div class="modal-upload-image-gallery">

	<ul class="modal-upload-image-gallery-list js-media-upload-gallery-list">

	</ul>

	<div class="modal-upload-image-gallery-info">
		<div class="js-media-detail-area" style="display: none;">
			<img src="" alt="" class="js-media-detail-preview">
			<ul class="mb-20">
				<li><strong class="js-media-detail-name"></strong></li>
				<li class="js-media-detail-date"></li>
				<li><span class="js-media-detail-dimensions"></span>, <span class="js-media-detail-file-size"></span></li>
				<li><a href="#" onclick="if (confirm('Удалить текущий файл?')) { ls.media.removeCurrentFile(); }; return false;">Удалить файл</a></li>
			</ul>

			{* Title *}
			{include file='forms/form.field.text.tpl'
					 sFieldName  = 'title'
					 sFieldLabel = $aLang.uploadimg_title}
        </div>

		<div class="js-media-settings-mode" id="media-settings-mode-insert" style="display: none;">
			Опции вставки
			{* Align *}
            <p>
                <label>{$aLang.uploadimg_align}:</label>
                <select name="align" class="width-full">
                    <option value="">{$aLang.uploadimg_align_no}</option>
                    <option value="left">{$aLang.uploadimg_align_left}</option>
                    <option value="right">{$aLang.uploadimg_align_right}</option>
                    <option value="center" selected="selected">{$aLang.uploadimg_align_center}</option>
                </select>
            </p>
			<p>
				{$aImageSizes=Config::Get('module.media.image_sizes')}
                <label>Размер:</label>
                <select name="size" class="width-full">
					{foreach $aImageSizes as $aSize}
                        <option value="{$aSize.w}{if $aSize.crop}crop{/if}">{$aSize.w} × {if $aSize.h}{$aSize.h}{else}*{/if}</option>
					{/foreach}
                    <option value="original">Оригинал</option>
                </select>
			</p>
		</div>

        <div class="js-media-settings-mode" id="media-settings-mode-create-photoset" style="display: none;">
			Опции фотосета
			<p>
				<label>
                    <input type="checkbox" name="use_thumbs" value="1" checked="checked"> &mdash; показывать ленту с превьюшками
				</label>
			</p>
            <p>
                <label>
                    <input type="checkbox" name="show_caption" value="1"> &mdash; показывать описания фотографий
                </label>
            </p>
        </div>

	</div>
</div>

</div>