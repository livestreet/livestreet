Здесь подробная информация об обновлении.
<br/>

<ul>
	<li>Обязательно бекап</li>
	<li>Обязательно бекап</li>
	<li>Обязательно бекап</li>
	<li>Обязательно бекап</li>
	<li>Обязательно бекап</li>
</ul>

<br/>
Ваша текущая версия:
<select name="from_version">
	<option value=""></option>
	<?php foreach($this->get('convert_versions') as $version) { ?>
		<option <?php if ($this->get('from_version')==$version) { ?> selected="selected" <?php } ?> ><?php echo $version ?></option>
	<?php } ?>
</select>