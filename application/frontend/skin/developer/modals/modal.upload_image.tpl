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
			<li data-type="tab" data-tab-target="tab-media-link"><a href="#">Вставить по ссылке</a></li>
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
					<p>
						<input type="text" name="url" value="http://" class="width-full" />
					</p>

                    <p>
						<label>
							{$aLang.uploadimg_title}:
                        	<input type="text" name="title" value="" class="width-full" />
                        </label>
					</p>

					<div style="display: none;" class="js-media-link-settings-image">
						<p>
							<img src="" width="200" class="js-media-link-settings-image-preview">
						</p>
                        <p>
                            <label>
								{$aLang.uploadimg_align}:
								<select name="align" class="width-full">
									<option value="">{$aLang.uploadimg_align_no}</option>
									<option value="left">{$aLang.uploadimg_align_left}</option>
									<option value="right">{$aLang.uploadimg_align_right}</option>
									<option value="center">{$aLang.uploadimg_align_center}</option>
								</select>
                            </label>
                        </p>
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
		</div>
	</div>
{/block}

{block name='modal_footer'}{/block}