Нужно выбрать группу:

<br/>
<br/>

<?php
foreach ($this->get('groups') as $group) {
    ?>
    <a href="?group=<?php echo $group; ?>"><?php echo $this->lang("groups.{$group}.title"); ?></a>
<?php } ?>
