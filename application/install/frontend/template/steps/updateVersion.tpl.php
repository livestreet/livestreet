<div class="alert alert--info">
    <div class="alert-title">Внимание!</div>
    Перед обновлением обязательно сделайте бекап БД
</div>


<p><label for="">Ваша текущая версия:</label>
<select name="from_version" class="width-100">
    <?php foreach ($this->get('convert_versions') as $version) { ?>
        <option <?php if ($this->get('from_version') == $version) { ?> selected="selected" <?php } ?> >
            <?php echo $version ?>
        </option>
    <?php } ?>
</select></p>