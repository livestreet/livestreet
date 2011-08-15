<form method="POST" action="" class="upload-form popup jqmWindow" enctype="multipart/form-data" id="form_upload_img">
	<h3>{$aLang.uploadimg}</h3>
	<p>
		<label>{$aLang.uploadimg_file}:<br />
		<input type="file" name="img_file" id="img_file" value="" class="input-wide" /></label>
	</p>
	<p>
		<label>{$aLang.uploadimg_url}:<br />
		<input type="text" name="img_url" value="http://" class="input-wide" /></label>
	</p>
	{hook run="uploadimg_source"}
	<p>
		<label for="align">{$aLang.uploadimg_align}:</label>
		<select name="align" class="input-wide">
			<option value="">{$aLang.uploadimg_align_no}</option>
			<option value="left">{$aLang.uploadimg_align_left}</option>
			<option value="right">{$aLang.uploadimg_align_right}</option>
			<option value="center">{$aLang.uploadimg_align_center}</option>
		</select>
	</p>
	<p><label>{$aLang.uploadimg_title}:<br />
	<input type="text" class="input-wide" name="title" value="" /></label></p>
	{hook run="uploadimg_additional"}
	<input type="button" value="{$aLang.uploadimg_submit}" class="button" onclick="ls.ajaxUploadImg('form_upload_img','{$sToLoad}');" />
	<input type="button" value="{$aLang.uploadimg_cancel}" class="button jqmClose" />
</form>
	