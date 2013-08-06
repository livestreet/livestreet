<?php

class PluginExample_HookExample extends Hook {

    /*
     * Регистрация событий на хуки
     */
    public function RegisterHook() {



        /*
         * Хук в начало функции AddTopic() в модуле Topic (файл /classes/modules/topic/Topic.class.php , если этот модуль не переопределен в других плагинах):
         *
         * $this->AddHook('module_topic_addtopic_before','func_topic_addtopic_before');
         *
         * Будет вызвана функция func_topic_addtopic_before($aVars) , где $aVars - НЕассоциативный массив аргументов, переданных этой функции.
         * Передача результата в функцию AddTopic() делается путем изменения аргументов по ссылке - например, &$aVars[0]
         */


        /*
         * Хук в конец функции AddTopic() в модуле Topic (файл /classes/modules/topic/Topic.class.php , если этот модуль не переопределен в других плагинах):
         *
         * $this->AddHook('module_topic_addtopic_after','func_topic_addtopic_after');
         *
         * Будет вызвана функция func_topic_addtopic_after($Var) , где $Var - это то, что возвращает AddTopic() (т.е. или false или объект топика $oTopic)
         * Функция должна завершаться при помощи return $Var
         */


        /*
         * Хук в конкреное место движка
         *
         * $this->AddHook('init_action','func_init_action', __CLASS__, -5);
         *
         * Приоритет для вызова хука = -5. Этот приоритет так же можно указывать и в хуках на модели.
         * Будет вызвана функция func_init_action($Var) в том месте движка, где стоит данный хук
         */


        /*
         * Хук с делегированием
         *
         * $this->AddDelegateHook('module_topic_addtopic_before','func_topic_addtopic_new',__CLASS__);
         *
         * Полная подмена функции AddTopic() модуля Topic на свою.
         * Будет вызвана функция func_topic_addtopic_new($Var), где $aVars - НЕассоциативный массив аргументов.
         * Делегирование существует в движке только для обеспечения совместимости со старыми плагинами, рекомендуется вместо него использовать переопределение.
         */
    }
}
?>
