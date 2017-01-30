<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Установка LiveStreet <?php echo VERSION; ?></title>

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,700&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/helpers.css"/>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/button.css"/>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/alert.css"/>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/forms.css"/>
    <link rel="stylesheet" type="text/css" href="./frontend/template/assets/css/main.css"/>

    <script type="text/javascript" src="./frontend/template/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="./frontend/template/assets/js/main.js"></script>
</head>

<body>

<div class="container">
    <div class="header">
        <h1>Установка LiveStreet <?php echo VERSION; ?></h1>

        <?php if ($currentStep = $this->get('currentStep')) { ?>
            <h2><?php echo $currentStep->getGroupTitle(); ?></h2>
        <?php } ?>
    </div>


    <div class="content">
        <?php if ($currentStep = $this->get('currentStep')) { ?>
            <h2 class="page-header">
                <?php echo $currentStep->getStepTitle(); ?>
            </h2>

            <?php if ($errors = $currentStep->getErrors()) { ?>
                <div class="alert alert--error">
                    <?php foreach ($errors as $sMsg) { ?>

                        <?php echo $sMsg; ?><br/>

                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>

        <form action="" method="post">
            <div class="content-body">
                <?php echo $this->get('content'); ?>
            </div>

            <div class="step-buttons clearfix">
                <?php if (!$this->get('install_reset_hide')) { ?>
                    <a href="./?reset=1" class="button"><?php echo $this->lang('install_reset'); ?></a>
                <?php } ?>

                <?php if (!$this->get('next_step_hide')) { ?>
                    <button type="submit" class="button button--primary pull-right" name="action_next" id="action_next"
                        <?php if ($this->get('next_step_disable')) { ?>disabled="disabled" <?php } ?> >Дальше
                    </button>
                <?php } ?>

                <?php if (!$this->get('previous_step_hide')) { ?>
                    <button type="submit" class="button pull-right" name="action_previous" id="action_previous"
                        <?php if ($this->get('previous_step_disable')) { ?>disabled="disabled" <?php } ?> >Назад
                    </button>
                <?php } ?>
            </div>
        </form>
    </div>
</div>

</body>
</html>