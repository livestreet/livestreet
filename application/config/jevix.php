<?php

return array(
    'default' => array(
        // Разрешённые теги
        'cfgAllowTags'          => array(
            // вызов метода с параметрами
            array(
                array('ls', 'gallery'),
            ),
        ),
        // Коротие теги типа
        'cfgSetTagShort'        => array(
            array(
                array('ls', 'gallery')
            ),
        ),
        // Разрешённые параметры тегов
        'cfgAllowTagParams'     => array(
            array(
                'ls',
                array('user' => '#text')
            ),
            array(
                'gallery',
                array('items' => '#text', 'nav' => array('thumbs'), 'caption' => array('0', '1'))
            ),
            array(
                'a',
                array('data-rel' => '#text', 'class' => array('js-lbx'))
            ),
        ),
        'cfgSetTagCallbackFull' => array(
            array(
                'ls',
                array('_this_', 'Tools_CallbackParserTagLs'),
            ),
            array(
                'gallery',
                array('_this_', 'Media_CallbackParserTagGallery'),
            ),
        )
    ),
);