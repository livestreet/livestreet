<?php
/**
 * Основной файл центрального крона
 * Файл необходимо добавить на сервере в список cron процессов с периодом запуска 1 раз в 5 минут.
 * ВНИМАНИЕ! Крон необходимо добавить от имени пользователя, под которым работает ваш веб-сервер. Это позволит избежат проблем с правами.
 */


require_once(dirname(dirname(dirname(__DIR__))) . '/bootstrap/start.php');

class CronMain extends Cron
{
    /**
     * Производить логирование или нет
     *
     * @var bool
     */
    protected $bLogEnable = false;

    /**
     * Запускаем обработку
     */
    public function Client()
    {
        set_time_limit(0);
        $this->Cron_RunMain();
    }
}

/**
 * Создаем объект крон-процесса,
 * передавая параметром путь к лок-файлу
 */
$app = new CronMain(Config::Get('sys.cache.dir') . 'CronMain.lock');
print $app->Exec();