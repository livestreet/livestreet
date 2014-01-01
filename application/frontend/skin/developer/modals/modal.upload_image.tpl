{**
 * Загрузка медиа-файлов
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-image-upload{/block}
{block name='modal_class'}modal-upload-image js-modal-default{/block}
{block name='modal_title'}Добавить медиа-файл{/block}
{block name='modal_attributes'}data-modal-center="false"{/block}

{block name='modal_content_after'}

	<script type="text/javascript">
		jQuery(function($){
			ls.media.init({
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
			<!--<li data-type="tab" data-tab-target="tab-media-link"><a href="#">{$aLang.uploadimg_from_link}</a></li>-->
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
					<button type="submit" class="button button-primary js-media-insert">Вставить</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</div>


			{**
			 * Ссылка
			 *}
			<form method="POST" action="" enctype="multipart/form-data" id="tab-media-link" onsubmit="return false;" data-type="tab-pane" class="tab-pane modal-upload-image-pane">
				<div class="modal-content">
					<p><label for="img_url">{$aLang.uploadimg_url}:</label>
					<input type="text" name="img_url" id="img_url" value="http://" class="width-full" /></p>

					<p>
						<label for="form-image-url-align">{$aLang.uploadimg_align}:</label>
						<select name="align" id="form-image-url-align" class="width-full">
							<option value="">{$aLang.uploadimg_align_no}</option>
							<option value="left">{$aLang.uploadimg_align_left}</option>
							<option value="right">{$aLang.uploadimg_align_right}</option>
							<option value="center">{$aLang.uploadimg_align_center}</option>
						</select>
					</p>

					<p><label for="form-image-url-title">{$aLang.uploadimg_title}:</label>
					<input type="text" name="title" id="form-image-url-title" value="" class="width-full" /></p>
				</div>

				<div class="modal-footer">
					<button type="submit" class="button button-primary js-insert-image-button">Вставить</button>
					<button type="submit" class="button button-primary js-upload-image-button" data-form-id="tab-upload-link">{$aLang.uploadimg_link_submit_load}</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</form>


			{**
			 * Фотосет
			 *}
			<div id="tab-media-create-photoset" data-type="tab-pane" class="tab-pane modal-upload-image-pane">
				<div class="modal-content">

				</div>

				<div class="modal-footer">
					<button type="submit" class="button button-primary js-media-insert-photoset">Создать фотосет</button>
					<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
				</div>
			</div>
		</div>
	</div>
{/block}

{block name='modal_footer'}{/block}