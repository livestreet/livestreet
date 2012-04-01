<div class="modal modal-image-upload" id="window_upload_img">
	<header>
		<h3>{$aLang.uploadimg}</h3>
		<a href="#" class="close jqmClose"></a>
	</header>
	
	<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" onsubmit="return false;">
		<p><label for="img_file">{$aLang.uploadimg_file}:</label>
		<input type="file" name="img_file" id="img_file" value="" class="input-text input-width-full" /></p>
		
		<p><label for="img_url">{$aLang.uploadimg_url}:</label>
		<input type="text" name="img_url" id="img_url" value="http://" class="input-text input-width-full" /></p>
		
		{hook run="uploadimg_source"}
		
		<p>
			<label for="align">{$aLang.uploadimg_align}:</label>
			<select name="align" class="input-width-full">
				<option value="">{$aLang.uploadimg_align_no}</option>
				<option value="left">{$aLang.uploadimg_align_left}</option>
				<option value="right">{$aLang.uploadimg_align_right}</option>
				<option value="center">{$aLang.uploadimg_align_center}</option>
			</select>
		</p>
		
		<p><label for="title">{$aLang.uploadimg_title}:</label>
		<input type="text" name="title" id="title" value="" class="input-text input-width-full" /></p>
		
		{hook run="uploadimg_additional"}
		
		<button class="button button-primary" onclick="ls.ajaxUploadImg('form_upload_img','{$sToLoad}');">{$aLang.uploadimg_submit}</button>
		<button class="button jqmClose">{$aLang.uploadimg_cancel}</button>
	</form>
</div>
	