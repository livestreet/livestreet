<?php if ($requirements = $this->get('requirements')) { ?>

    <div class="alert alert--error">
        <div class="alert-title">Хостинг не удовлетворяет минимальным требованиям.</div>

        <ul>
            <?php foreach ($requirements as $requirement) { ?>
                <li>
                    <div>
                        <?php echo $this->lang('steps.checkRequirements.requirements.' . $requirement['name'] . '.title'); ?> &mdash; <?php echo $requirement['current']; ?>
                    </div>

                    <div>
                        <i><?php echo $this->lang('steps.checkRequirements.requirements.' . $requirement['name'] . '.solution'); ?></i>
                    </div>
                </li>
            <?php } ?>
        </ul>

    </div>

    <?php
    $additionalSolution = $this->get('additionalSolution');
    if ($additionalSolution) { ?>
        <div class="alert alert--error">
        <ul>
            <li><?php echo($additionalSolution); ?></li>
        </ul>
        </div>
    <?php } ?>

<?php } else { ?>

    <div class="loading"></div>

    <script type="text/javascript">
        jQuery(function ($) {
            setTimeout(install.goNextStep, 1000);
        });
    </script>

<?php } ?>