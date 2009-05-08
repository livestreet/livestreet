<div class="upload-form" id="window_load_img">
	<a href="#" class="close" onclick="hideImgUploadForm(); return false;"></a>
	<div class="content">
		<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img">
			<h3>{$aLang.uploadimg}</h3>
			<p><label for="img_file">{$aLang.uploadimg_file}:</label><input type="file" name="img_file" value="" class="w100p" /></p>
			<p><label for="img_url">{$aLang.uploadimg_url}:</label><input  type="text" name="img_url" value="http://" class="w100p" /></p>
			<p><label for="align">{$aLang.uploadimg_align}:</label>			
				<select name="align" class="w100p">
					<option value="">{$aLang.uploadimg_align_no}</option>
					<option value="left">{$aLang.uploadimg_align_left}</option>
					<option value="right">{$aLang.uploadimg_align_right}</option>
				</select>
			</p>
			<p><label for="title">{$aLang.uploadimg_title}:</label><input type="text" class="w100p" name="title" value="" /></p>
			
			<input type="button" value="{$aLang.uploadimg_submit}" onclick="ajaxUploadImg(document.getElementById('form_upload_img'),'{$sToLoad}');">
			<input type="button" value="{$aLang.uploadimg_cancel}" onclick="hideImgUploadForm(); return false;">
		</form>
	</div>
</div>