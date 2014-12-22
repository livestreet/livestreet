<ul class="groups">
    <?php foreach ($this->get('groups') as $group) { ?>
        <li>
            <h2><a href="?group=<?php echo $group; ?>">
                <?php echo $this->lang("groups.{$group}.title"); ?>
            </a></h2>
        </li>
    <?php } ?>
</ul>