{**
 * Загрузка медиа-файлов
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-image-upload{/block}
{block name='modal_class'}modal-upload-image js-modal-default{/block}
{block name='modal_title'}Добавить медиа-файл{/block}
{block name='modal_attributes'}data-modal-center="false"{/block}

{block name='modal_content_after'}
	{* Массив со значениями для селекта Выравнивание *}
	{$aSelectImageAlign = [
		[ 'value' => '',       'text' => $aLang.uploadimg_align_no ],
		[ 'value' => 'left',   'text' => $aLang.uploadimg_align_left ],
		[ 'value' => 'right',  'text' => $aLang.uploadimg_align_right ],
		[ 'value' => 'center', 'text' => $aLang.uploadimg_align_center ]
	]}

	{$aTargetParams=$LS->Media_GetTargetTypeParams($sMediaTargetType)}

	<script type="text/javascript">
		jQuery(function($){
			ls.media.init({
				target_params: {json var=$aTargetParams},
				target_type: {json var=$sMediaTargetType},
				target_id: {json var=$sMediaTargetId},
				target_tmp: {json var=$sMediaTargetTmp}
			});
		});
	</script>

	<div class="grid-row">
		{* Side navigation *}
		<ul class="modal-upload-image-nav" data-type="tabs">
			<li data-type="tab" data-tab-target="tab-media-insert" data-media-mode="insert" class="active js-tab-show-gallery"><a href="#">Вставить</a></li>
            <li data-type="tab" data-tab-target="tab-media-create-photoset" data-media-mode="create-photoset" class="js-tab-show-gallery"><a href="#">Создать фотосет</a></li>
			<li data-type="tab" data-tab-target="tab-media-link"><a href="#">Вставить по ссылке</a></li>
			{if $aTargetParams.allow_preview}
				<li data-type="tab" data-tab-target="tab-media-preview"><a href="#">Превью</a></li>
			{/if}
		</ul>

		{* Side navigation content *}
		<div data-type="tab-panes" class="modal-upload-image-content">
			{**
			 * Загрузка
			 *}
			<div id="tab-media-insert" data-type="tab-pane" class="tab-pane modal-upload-image-pane" style="display: block">
				<div class="modal-content">
					{include file='modals/modal.upload_image.gallery.tpl'}
				</div>

				<div class="modal-footer">
					<button type="submit" class="button button-primary js-media-insert-button js-media-insert">Вставить</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</div>


			{**
			 * Ссылка
			 *}
			<form method="POST" action="" enctype="multipart/form-data" id="tab-media-link" onsubmit="return false;" data-type="tab-pane" class="tab-pane modal-upload-image-pane js-media-link-form">
				<div class="modal-content">
					{* Ссылка *}
					{include file='forms/fields/form.field.text.tpl'
							 sFieldName  = 'url'
							 sFieldValue = 'http://'
							 sFieldLabel = 'Ссылка'}

					{* Описание *}
					{include file='forms/fields/form.field.text.tpl'
							 sFieldName  = 'title'
							 sFieldLabel = $aLang.uploadimg_title}

					<div style="display: none;" class="js-media-link-settings-image">
						<p><img src="" width="200" class="js-media-link-settings-image-preview"></p>

						{* Выравнивание *}
						{include file='forms/fields/form.field.select.tpl'
								 sFieldName    = 'align'
								 sFieldClasses = 'width-200'
								 sFieldLabel   = $aLang.uploadimg_align
								 aFieldItems   = $aSelectImageAlign}
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="button button-primary js-media-link-insert-button">Вставить как ссылку</button>
					<button type="submit" class="button button-primary js-media-link-upload-button">Загрузить и вставить</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</form>


			{**
			 * Фотосет
			 *}
			<div id="tab-media-create-photoset" data-type="tab-pane" class="tab-pane modal-upload-image-pane">
				<div class="modal-content"></div>

				<div class="modal-footer">
					<button type="submit" class="button button-primary js-media-insert-button js-media-insert-photoset">Создать фотосет</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</div>

			{**
			 * Фотосет
			 *}
			<div id="tab-media-preview" data-type="tab-pane" class="tab-pane modal-upload-image-pane">
				<div class="modal-content">
					ffff
				</div>
			</div>
		</div>
	</div>
{/block}

{block name='modal_footer'}{/block}