<div id="uploadimg-form-content" style="display: none;">
<div class="login-popup upload-image" id="window_load_img">
	<div class="login-popup-top"><a href="#" class="close-block" onclick="return false;"></a></div>
	<div class="content">
		<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" >
			<h3>Вставка изображения</h3>
			<p><label for="img_file">Файл:</label><br /><input type="file" name="img_file" value="" /></p>
			<p><label for="img_url">Ссылка на изображение:</label><br /><input  type="text" name="img_url" value="http://" class="w100p"/></p>
			<p><label for="align">Выравнивание:</label><br />				
				<select name="align" class="w100p">
					<option value="">нет</option>
					<option value="left">слева</option>
					<option value="right">справа</option>
				</select>
			</p>
			<p style="margin-bottom: 20px;"><label for="title">Описание:</label><br /><input type="text" style="width: 400px;" name="title" value="" /></p>
			<input type="button" value="Загрузить" onclick="ajaxUploadImg(document.getElementById('form_upload_img'),'{$sToLoad}');">
			<input type="button" value="Отмена" onclick="hideImgUploadForm(); return false;">
		</form>
	</div>
	<div class="login-popup-bottom"></div>
</div>
</div>