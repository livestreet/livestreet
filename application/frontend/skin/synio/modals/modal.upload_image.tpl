{**
 * Загрузка изображения
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-image-upload{/block}
{block name='modal_class'}modal-image-upload js-modal-default{/block}
{block name='modal_title'}{$aLang.uploadimg}{/block}

{block name='modal_header_after'}
	<ul class="nav nav-pills nav-pills-tabs" data-type="tabs">
		<li data-type="tab" data-option-target="tab-upload-pc" class="active"><a href="#">{$aLang.uploadimg_from_pc}</a></li>
		<li data-type="tab" data-option-target="tab-upload-link"><a href="#">{$aLang.uploadimg_from_link}</a></li>
	</ul>
{/block}

{block name='modal_content_after'}
	<div data-type="tab-panes">
		{**
		 * Загрузка
		 *}
		<form method="POST" action="" enctype="multipart/form-data" id="tab-upload-pc" onsubmit="return false;" class="tab-pane" data-type="tab-pane" style="display: block">
			<div class="modal-content">
				<p><label for="img_file">{$aLang.uploadimg_file}:</label>
				<input type="file" name="img_file" id="img_file" value="" /></p>
				
				{hook run="uploadimg_source"}
				
				<p>
					<label for="form-image-align">{$aLang.uploadimg_align}:</label>
					<select name="align" id="form-image-align" class="width-full">
						<option value="">{$aLang.uploadimg_align_no}</option>
						<option value="left">{$aLang.uploadimg_align_left}</option>
						<option value="right">{$aLang.uploadimg_align_right}</option>
						<option value="center">{$aLang.uploadimg_align_center}</option>
					</select>
				</p>
				
				<p><label for="form-image-title">{$aLang.uploadimg_title}:</label>
				<input type="text" name="title" id="form-image-title" value="" class="width-full" /></p>
				
				{hook run="uploadimg_additional"}
			</div>
			
			<div class="modal-footer">
				<button type="submit" class="button button-primary js-upload-image-button" data-form-id="tab-upload-pc">{$aLang.uploadimg_submit}</button>
				<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
			</div>
		</form>

		{**
		 * Ссылка
		 *}
		<form method="POST" action="" enctype="multipart/form-data" id="tab-upload-link" class="tab-pane" onsubmit="return false;" data-type="tab-pane">
			<div class="modal-content">
				<p><label for="img_file">{$aLang.uploadimg_url}:</label>
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

				{hook run="uploadimg_link_additional"}
			</div>
			
			<div class="modal-footer">
				<button type="submit" class="button button-primary js-insert-image-button">{$aLang.uploadimg_link_submit_paste}</button>
				<button type="submit" class="button button-primary js-upload-image-button" data-form-id="tab-upload-link">{$aLang.uploadimg_link_submit_load}</button>
				<button type="button" class="button" data-type="modal-close">{$aLang.uploadimg_cancel}</button>
			</div>
		</form>
	</div>
{/block}

{block name='modal_footer'}{/block}