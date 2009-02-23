<div class="login-popup" id="window_load_img">
	<div class="login-popup-top"><a href="#" class="close-block" onclick="return false;"></a></div>
	<div class="content">
		<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" >
		<h3>Вставка изображения</h3>
		<table  border="0">		
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
		</table>
		</form>
	</div>
	<div class="login-popup-bottom"></div>
</div>