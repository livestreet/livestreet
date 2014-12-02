<?php

class InstallStepUpdateVersion extends InstallStep
{

    protected $aVersionConvert = array(
        '1.0.3'
    );

    public function init()
    {
        /**
         * Полчаем данные коннекта к БД из конфига
         */
        InstallConfig::$sFileConfig = dirname(INSTALL_DIR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.local.php';
    }

    public function show()
    {
        $this->assign('from_version', InstallCore::getStoredData('update_from_version'));
        $this->assign('convert_versions', $this->aVersionConvert);
    }

    public function process()
    {
        /**
         * Коннект к серверу БД
         */
        if (!$oDb = $this->getDBConnection(InstallConfig::get('db.params.host'), InstallConfig::get('db.params.port'),
            InstallConfig::get('db.params.user'), InstallConfig::get('db.params.pass'), true)
        ) {
            return false;
        }
        /**
         * Выбираем БД
         */
        if (!@mysqli_select_db($oDb, InstallConfig::get('db.params.dbname'))) {
            return $this->addError(InstallCore::getLang('db.errors.db_query'));
        }

        $this->setDbParams(array(
            'prefix' => InstallConfig::get('db.table.prefix'),
            'engine' => InstallConfig::get('db.tables.engine'),
        ));
        $sVersion = InstallCore::getRequestStr('from_version');
        /**
         * Проверяем наличие конвертора
         * Конвертор представляет собой отдельный метод вида converFrom_X1_Y1_Z1_to_X2_Y2_Z2
         */
        $sMethod = 'convertFrom_' . str_replace('.', '_', $sVersion) . '_to_' . str_replace('.', '_', VERSION);
        if (!method_exists($this, $sMethod)) {
            return $this->addError(InstallCore::getLang('steps.updateVersion.errors.not_found_convert'));
        }
        InstallCore::setStoredData('update_from_version', $sVersion);
        /**
         * Запускаем конвертор
         */
        return call_user_func_array(array($this, $sMethod), array($oDb));
    }


    /**
     * Конвертор версии 1.0.3 в 2.0.0
     *
     * @param $oDb
     *
     * @return bool
     */
    public function convertFrom_1_0_3_to_2_0_0($oDb)
    {
        /**
         * Запускаем SQL патч
         */
        $sFile = 'sql' . DIRECTORY_SEPARATOR . 'patch_1.0.3_to_2.0.0.sql';
        list($bResult, $aErrors) = array_values($this->importDumpDB($oDb, InstallCore::getDataFilePath($sFile), array(
            'engine'      => InstallConfig::get('db.tables.engine'),
            'prefix'      => InstallConfig::get('db.table.prefix'),
            'check_table' => 'cron_task'
        )));
        if ($bResult) {
            /**
             * Здесь нужно выполнить конвертацию данных
             * 1. конвертация типов топиков - link, question, photoset -> topic
             * 2. создание дефолтных прав RBAC для пользователей
             * 3. конвертировать пути до аватаров/фото пользователей/блогов в БД и изменить имена файлов
             */

            /**
             * Конвертируем опросы
             * Сначала проверяем необходимость конвертации опросов
             */
            if ($this->dbCheckTable("prefix_topic_question_vote")) {
                $iPage = 1;
                $iLimitCount = 50;
                $iLimitStart = 0;
                while ($aTopics = $this->dbSelect("SELECT t.*, c.topic_extra FROM prefix_topic as t, prefix_topic_content as c WHERE topic_type = 'question' and t.topic_id = c.topic_id LIMIT {$iLimitStart},{$iLimitCount}")) {
                    $iPage++;
                    $iLimitStart = ($iPage - 1) * $iLimitCount;
                    /**
                     * Топики
                     */
                    foreach ($aTopics as $aTopic) {
                        $aPollData = @unserialize($aTopic['topic_extra']);
                        if (!isset($aPollData['answers'])) {
                            continue;
                        }
                        /**
                         * Создаем опрос
                         */
                        $aFields = array(
                            'user_id'          => $aTopic['user_id'],
                            'target_type'      => 'topic',
                            'target_id'        => $aTopic['topic_id'],
                            'title'            => htmlspecialchars($aTopic['topic_title']),
                            'count_answer_max' => 1,
                            'count_vote'       => isset($aPollData['count_vote']) ? $aPollData['count_vote'] : 0,
                            'count_abstain'    => isset($aPollData['count_vote_abstain']) ? $aPollData['count_vote_abstain'] : 0,
                            'date_create'      => $aTopic['topic_date_add'],
                        );
                        if ($iPollId = $this->dbInsertQuery('prefix_poll', $aFields)) {
                            foreach ($aPollData['answers'] as $iAnswerIdOld => $aAnswer) {
                                /**
                                 * Создаем вариант ответа
                                 */
                                $aFields = array(
                                    'poll_id'     => $iPollId,
                                    'title'       => htmlspecialchars($aAnswer['text']),
                                    'count_vote'  => htmlspecialchars($aAnswer['count']),
                                    'date_create' => $aTopic['topic_date_add'],
                                );
                                if ($iAnswerId = $this->dbInsertQuery('prefix_poll_answer', $aFields)) {
                                    /**
                                     * Получаем список кто голосовал за этот вариант
                                     */
                                    if ($aVotes = $this->dbSelect("SELECT * FROM prefix_topic_question_vote WHERE topic_id = '{$aTopic['topic_id']}' AND answer = '{$iAnswerIdOld}' ")) {
                                        foreach ($aVotes as $aVote) {
                                            /**
                                             * Добавляем новый факт голосования за вариант
                                             */
                                            $aFields = array(
                                                'poll_id'     => $iPollId,
                                                'user_id'     => $aVote['user_voter_id'],
                                                'answers'     => serialize(array($iAnswerId)),
                                                'date_create' => $aTopic['topic_date_add'],
                                            );
                                            $this->dbInsertQuery('prefix_poll_vote', $aFields);
                                        }
                                    }
                                }
                            }
                            /**
                             * Добавляем факты голосования воздержавшихся
                             */
                            /**
                             * Получаем список кто голосовал за этот вариант
                             */
                            if ($aVotes = $this->dbSelect("SELECT * FROM prefix_topic_question_vote WHERE topic_id = '{$aTopic['topic_id']}' AND answer = -1 ")) {
                                foreach ($aVotes as $aVote) {
                                    /**
                                     * Добавляем новый факт воздержания
                                     */
                                    $aFields = array(
                                        'poll_id'     => $iPollId,
                                        'user_id'     => $aVote['user_voter_id'],
                                        'answers'     => serialize(array()),
                                        'date_create' => $aTopic['topic_date_add'],
                                    );
                                    $this->dbInsertQuery('prefix_poll_vote', $aFields);
                                }
                            }
                        }
                        /**
                         * Меняем тип топика
                         */
                        $this->dbQuery("UPDATE prefix_topic SET topic_type = 'topic' WHERE topic_id ='{$aTopic['topic_id']}'");
                        /**
                         * Убираем лишние данные из topic_extra
                         */
                        unset($aPollData['answers']);
                        unset($aPollData['count_vote_abstain']);
                        unset($aPollData['count_vote']);
                        $sExtra = mysqli_escape_string($this->rDbLink, serialize($aPollData));
                        $this->dbQuery("UPDATE prefix_topic_content SET topic_extra = '{$sExtra}' WHERE topic_id ='{$aTopic['topic_id']}'");
                    }
                }
                /**
                 * Удаляем старые таблицы
                 */
                if (!$this->getErrors()) {
                    $this->dbQuery('DROP TABLE prefix_topic_question_vote');
                }
            }

            return true;
        }
        return $this->addError(join('<br/>', $aErrors));
    }
}