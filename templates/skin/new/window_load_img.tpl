<div class="login-popup upload-image" id="window_load_img">
	<div class="login-popup-top"><a href="#" class="close-block" onclick="return false;"></a></div>
	<div class="content">
		<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" >
			<h3>Вставка изображения</h3>
			<p><label for="img_file">Файл:</label><br /><input type="file" name="img_file" value="" /></p>
			<p><label for="img_url">Ссылка:</label><br /><input  type="text" style="width: 400px;" name="img_url" value="http://" /></p>
			<p><label for="align">Выравнивание:</label><br />				
				<select name="align" style="width: 406px;">
					<option value="">нет</option>
					<option value="left">слева</option>
					<option value="right">справа</option>
				</select>
			</p>
			<p style="margin-bottom: 20px;"><label for="title">Описание:</label><br /><input type="text" style="width: 400px;" name="title" value="" /></p>
			<input type="button" value="Загрузить" onclick="ajaxUploadImg(document.getElementById('form_upload_img'),'{$sToLoad}');">
			<input type="button" value="Отмена" onclick="hideImgUploadForm(); return false;">
		<!--<table  border="0">		
		<tr>
			<td align="right">Файл:</td>
			<td width="100%"><input type="file" name="img_file" style="width: 100%;" value=""></td>
		</tr>
		<tr>
			<td align="right">Ссылка:</td>
			<td><input type="text" name="img_url" value="http://" style="width: 100%;">
		</tr>
		<tr>
			<td align="right">Выравнивание:</td>

			<td>
				<select name="align">
					<option value="">нет</option>
					<option value="left">слева</option>
					<option value="right">справа</option>
				</select>
		</tr>
		<tr>
			<td align="right">Описание:</td>
			<td><input type="text" name="title" style="width: 100%;"></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="button" value="Загрузить" onclick="ajaxUploadImg(document.getElementById('form_upload_img'),'{$sToLoad}');">
				<input type="button" value="Отмена" onclick="hideImgUploadForm(); return false;">
			</td>
		</tr>
		</table>-->
		</form>
	</div>
	<div class="login-popup-bottom"></div>
</div>