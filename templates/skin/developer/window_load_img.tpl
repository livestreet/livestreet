<div class="upload-form" id="window_load_img">
	<a href="#" class="close" onclick="hideImgUploadForm(); return false;"></a>
	<div class="content">
		<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img">
			<h3>{$aLang.uploadimg}</h3>

			<p><label>{$aLang.uploadimg_file}:<br />
			<input type="file" name="img_file" value="" class="input-wide" /></label></p>

			<p><label>{$aLang.uploadimg_url}:
			<input type="text" name="img_url" value="http://" class="input-wide" /></label></p>

			<p><label for="align">{$aLang.uploadimg_align}:</label>
			<select name="align" class="input-wide">
				<option value="">{$aLang.uploadimg_align_no}</option>
				<option value="left">{$aLang.uploadimg_align_left}</option>
				<option value="right">{$aLang.uploadimg_align_right}</option>
			</select></p>

			<p><label>{$aLang.uploadimg_title}:<br />
			<input type="text" class="input-wide" name="title" value="" /></label></p>

			<input type="button" value="{$aLang.uploadimg_submit}" onclick="ajaxUploadImg(document.getElementById('form_upload_img'),'{$sToLoad}');" />
			<input type="button" value="{$aLang.uploadimg_cancel}" onclick="hideImgUploadForm(); return false;" />
		</form>
	</div>
</div>