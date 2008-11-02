<div id="window_load_img">
	<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" >
	<table width="500px"  border="0">
		<tr>
			<th align="center" colspan="2">Вставка изображения</th>			
		</tr>
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
				<input type="button" value="Отмена" onclick="closeWindow('window_load_img'); return false;">
			</td>
		</tr>
	</table>
	</form>
</div>