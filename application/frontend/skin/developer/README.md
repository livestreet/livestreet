# Корневая папка скина
В качестве шаблонизатора используется [Smarty](http://www.smarty.net/documentation).
## Структура скина
* **layouts / layout.base.tpl** - Базовый макет сайта
  * **actions / ActionIndex / index.tpl** - Шаблон главной страницы сайта. Подгружается в файле [ActionIndex.class.php](https://github.com/livestreet/livestreet/blob/master/application/classes/actions/ActionIndex.class.php#L314)
    * **topics / topic_list.tpl** - Шаблон списка топиков
      * **toolbar / toolbar.topic.tpl** - Шаблон кнопки прокручивания к следующему/предыдущему топику
      * **topics / topic_base.tpl** - Базовый шаблон топика
      * **pagination.tpl** - Шаблон нумерации страниц
      * **alert.tpl** - Уведомления
  * **actions / ActionBlog / topic.tpl** - Шаблон страницы топика. Подгружается в файле [ActionBlog.class.php](https://github.com/livestreet/livestreet/blob/master/application/classes/actions/ActionBlog.class.php#L845)
    * **topics / topic.tpl** - Шаблон тела топика
    * **comments / comment_tree.tpl** - Шаблон комментариев на странице топика
