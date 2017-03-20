<?php

class InstallStepUpdateVersion extends InstallStep
{

    protected $aVersionConvert = array(
        '2.0.0',
        '1.0.3',
    );

    public function init()
    {
        /**
         * Получаем данные коннекта к БД из конфига
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
        set_time_limit(0);
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
     * Конвертор версии 2.0.0 в 2.0.1
     *
     * @param $oDb
     *
     * @return bool
     */
    public function convertFrom_2_0_0_to_2_0_1($oDb)
    {
        /**
         * Запускаем SQL патч
         */
        $sFile = 'sql' . DIRECTORY_SEPARATOR . 'patch_2.0.0_to_2.0.1.sql';
        list($bResult, $aErrors) = array_values($this->importDumpDB($oDb, InstallCore::getDataFilePath($sFile), array(
            'engine'         => InstallConfig::get('db.tables.engine'),
            'prefix'         => InstallConfig::get('db.table.prefix'),
            'skip_fk_errors' => true
        )));
        if ($bResult) {
            return true;
        }
        return $this->addError(join('<br/>', $aErrors));
    }

    /**
     * Конвертор версии 1.0.3 в 2.0.1
     *
     * @param $oDb
     *
     * @return bool
     */
    public function convertFrom_1_0_3_to_2_0_1($oDb)
    {
        $mResult = $this->convertFrom_1_0_3_to_2_0_0($oDb);
        if ($mResult == true) {
            $mResult = $this->convertFrom_2_0_0_to_2_0_1($oDb);
        }
        return $mResult;
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
            'engine'         => InstallConfig::get('db.tables.engine'),
            'prefix'         => InstallConfig::get('db.table.prefix'),
            'check_table'    => 'cron_task',
            'skip_fk_errors' => true
        )));
        if ($bResult) {
            /**
             * Проверяем необходимость конвертировать таблицу плагина Page
             */
            if ($this->dbCheckTable("prefix_page")) {
                $sFile = 'sql' . DIRECTORY_SEPARATOR . 'patch_page_1.3_to_2.0.sql';
                list($bResult, $aErrors) = array_values($this->importDumpDB($oDb, InstallCore::getDataFilePath($sFile),
                    array(
                        'engine'            => InstallConfig::get('db.tables.engine'),
                        'prefix'            => InstallConfig::get('db.table.prefix'),
                        'check_table_field' => array('prefix_page', 'id'),
                        'skip_fk_errors'    => true
                    )));
                if (!$bResult) {
                    return $this->addError(join('<br/>', $aErrors));
                }
            }
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

            /**
             * Конвертируем топик-ссылки
             */
            $iPage = 1;
            $iLimitCount = 50;
            $iLimitStart = 0;
            while ($aTopics = $this->dbSelect("SELECT t.*, c.topic_extra, c.topic_text, c.topic_text_short, c.topic_text_source FROM prefix_topic as t, prefix_topic_content as c WHERE topic_type = 'link' and t.topic_id = c.topic_id LIMIT {$iLimitStart},{$iLimitCount}")) {
                $iPage++;
                $iLimitStart = ($iPage - 1) * $iLimitCount;
                /**
                 * Топики
                 */
                foreach ($aTopics as $aTopic) {
                    $aData = @unserialize($aTopic['topic_extra']);
                    if (!isset($aData['url'])) {
                        continue;
                    }
                    /**
                     * Переносим ссылку в текст топика
                     */
                    $sUrl = $aData['url'];
                    if (strpos($sUrl, '://') === false) {
                        $sUrl = 'http://' . $sUrl;
                    }
                    $sUrl = htmlspecialchars($sUrl);
                    $sTextAdd = "\n<br/><br/><a href=\"{$sUrl}\">{$sUrl}</a>";
                    $aTopic['topic_text'] .= $sTextAdd;
                    $aTopic['topic_text_short'] .= $sTextAdd;
                    $aTopic['topic_text_source'] .= $sTextAdd;
                    unset($aData['url']);
                    $sExtra = mysqli_escape_string($this->rDbLink, serialize($aData));
                    $sText = mysqli_escape_string($this->rDbLink, $aTopic['topic_text']);
                    $sTextShort = mysqli_escape_string($this->rDbLink, $aTopic['topic_text_short']);
                    $sTextSource = mysqli_escape_string($this->rDbLink, $aTopic['topic_text_source']);
                    $this->dbQuery("UPDATE prefix_topic_content SET topic_extra = '{$sExtra}', topic_text = '{$sText}', topic_text_short = '{$sTextShort}', topic_text_source = '{$sTextSource}'  WHERE topic_id ='{$aTopic['topic_id']}'");
                    /**
                     * Меняем тип топика
                     */
                    $this->dbQuery("UPDATE prefix_topic SET topic_type = 'topic' WHERE topic_id ='{$aTopic['topic_id']}'");
                }
            }

            /**
             * Конвертируем топик-фотосеты
             */
            if ($this->dbCheckTable("prefix_topic_photo")) {
                $iPage = 1;
                $iLimitCount = 50;
                $iLimitStart = 0;
                while ($aTopics = $this->dbSelect("SELECT t.*, c.topic_extra, c.topic_text, c.topic_text_short, c.topic_text_source FROM prefix_topic as t, prefix_topic_content as c WHERE topic_type = 'photoset' and t.topic_id = c.topic_id LIMIT {$iLimitStart},{$iLimitCount}")) {
                    $iPage++;
                    $iLimitStart = ($iPage - 1) * $iLimitCount;
                    /**
                     * Топики
                     */
                    foreach ($aTopics as $aTopic) {
                        $aData = @unserialize($aTopic['topic_extra']);
                        if (!isset($aData['main_photo_id'])) {
                            continue;
                        }
                        /**
                         * Получаем фото
                         */
                        if ($aPhotos = $this->dbSelect("SELECT * FROM prefix_topic_photo WHERE topic_id = '{$aTopic['topic_id']}' ")) {
                            $aMediaItems = array();
                            foreach ($aPhotos as $aPhoto) {
                                /**
                                 * Необходимо перенести изображение в media и присоеденить к топику
                                 */
                                $sFileSource = $this->convertPathWebToServer($aPhoto['path']);
                                /**
                                 * Формируем список старых изображений для удаления
                                 */
                                $sMask = pathinfo($sFileSource,
                                        PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($sFileSource,
                                        PATHINFO_FILENAME) . '_*';
                                $aFilesForRemove = array();
                                if ($aPaths = glob($sMask)) {
                                    foreach ($aPaths as $sPath) {
                                        $aFilesForRemove[$sPath] = $sPath;
                                    }
                                }

                                if ($oImage = $this->createImageObject($sFileSource)) {
                                    $iWidth = $oImage->getSize()->getWidth();
                                    $iHeight = $oImage->getSize()->getHeight();
                                    if ($this->resizeImage($oImage, 1000)) {
                                        if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_1000x')) {
                                            unset($aFilesForRemove[$sFileSave]);
                                        }
                                    }

                                    if ($oImage = $this->createImageObject($sFileSource)) {
                                        if ($this->resizeImage($oImage, 500)) {
                                            if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_500x')) {
                                                unset($aFilesForRemove[$sFileSave]);
                                            }
                                        }
                                    }

                                    if ($oImage = $this->createImageObject($sFileSource)) {
                                        if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 100)) {
                                            if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_100x100crop')) {
                                                unset($aFilesForRemove[$sFileSave]);
                                            }
                                        }
                                    }

                                    if ($oImage = $this->createImageObject($sFileSource)) {
                                        if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 50)) {
                                            if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_50x50crop')) {
                                                unset($aFilesForRemove[$sFileSave]);
                                            }
                                        }
                                    }

                                    /**
                                     * Добавляем запись в медиа
                                     */
                                    $aDataMedia = array(
                                        'image_sizes' => array(
                                            array(
                                                'w'    => 1000,
                                                'h'    => null,
                                                'crop' => false,
                                            ),
                                            array(
                                                'w'    => 500,
                                                'h'    => null,
                                                'crop' => false,
                                            ),
                                            array(
                                                'w'    => 100,
                                                'h'    => 100,
                                                'crop' => true,
                                            ),
                                            array(
                                                'w'    => 50,
                                                'h'    => 50,
                                                'crop' => true,
                                            ),
                                        ),
                                    );
                                    if ($aPhoto['description']) {
                                        $aDataMedia['title'] = htmlspecialchars($aPhoto['description']);
                                    }
                                    $aFields = array(
                                        'user_id'     => $aTopic['user_id'],
                                        'type'        => 1,
                                        'target_type' => 'topic',
                                        'file_path'   => '[relative]' . str_replace(dirname(dirname(INSTALL_DIR)), '',
                                                $sFileSource),
                                        'file_name'   => pathinfo($sFileSource, PATHINFO_FILENAME),
                                        'file_size'   => filesize($sFileSource),
                                        'width'       => $iWidth,
                                        'height'      => $iHeight,
                                        'date_add'    => $aTopic['topic_date_add'],
                                        'data'        => serialize($aDataMedia),
                                    );

                                    if ($iMediaId = $this->dbInsertQuery('prefix_media', $aFields)) {
                                        /**
                                         * Добавляем связь медиа с топиком
                                         */
                                        $aFields = array(
                                            'media_id'    => $iMediaId,
                                            'target_id'   => $aTopic['topic_id'],
                                            'target_type' => 'topic',
                                            'date_add'    => $aTopic['topic_date_add'],
                                            'data'        => '',
                                        );
                                        if ($iMediaTargetId = $this->dbInsertQuery('prefix_media_target', $aFields)) {
                                            $sFileWeb = InstallConfig::get('path.root.web') . str_replace(dirname(dirname(INSTALL_DIR)),
                                                    '',
                                                    $sFileSource);
                                            $aMediaItems[$iMediaId] = $sFileWeb;
                                        }
                                    }
                                    /**
                                     * Удаляем старые
                                     */
                                    foreach ($aFilesForRemove as $sFileRemove) {
                                        @unlink($sFileRemove);
                                    }
                                }
                            }

                            /**
                             * Добавляем в начало текста топика вывод фотосета
                             */
                            $sCodeRender = '';
                            $sCodeSource = '';
                            if ($aMediaItems) {
                                $sCodeSource = '<gallery items="' . join(',',
                                        array_keys($aMediaItems)) . '" nav="thumbs" caption="1" />' . "\n";
                                $sCodeRender = '<div class="fotorama"  data-nav="thumbs" >' . "\n";

                                foreach ($aMediaItems as $iId => $sFileWeb) {
                                    $sCodeRender .= '<img src="' . $sFileWeb . '"  />' . "\n";
                                }
                                $sCodeRender .= '</div>' . "\n";

                            }

                            unset($aData['main_photo_id']);
                            unset($aData['count_photo']);
                            $sExtra = mysqli_escape_string($this->rDbLink, serialize($aData));
                            $sText = mysqli_escape_string($this->rDbLink, $sCodeRender . $aTopic['topic_text']);
                            $sTextShort = mysqli_escape_string($this->rDbLink,
                                $sCodeRender . $aTopic['topic_text_short']);
                            $sTextSource = mysqli_escape_string($this->rDbLink,
                                $sCodeSource . $aTopic['topic_text_source']);
                            $this->dbQuery("UPDATE prefix_topic_content SET topic_extra = '{$sExtra}', topic_text = '{$sText}', topic_text_short = '{$sTextShort}', topic_text_source = '{$sTextSource}'  WHERE topic_id ='{$aTopic['topic_id']}'");
                            /**
                             * Меняем тип топика
                             */
                            $this->dbQuery("UPDATE prefix_topic SET topic_type = 'topic' WHERE topic_id ='{$aTopic['topic_id']}'");
                        }
                    }
                }
                /**
                 * Удаляем старые таблицы
                 */
                if (!$this->getErrors()) {
                    $this->dbQuery('DROP TABLE prefix_topic_photo');
                }
            }


            /**
             * Конвертируем урлы топиков к ЧПУ формату
             */
            $iPage = 1;
            $iLimitCount = 50;
            $iLimitStart = 0;
            while ($aTopics = $this->dbSelect("SELECT * FROM prefix_topic ORDER BY topic_id asc LIMIT {$iLimitStart},{$iLimitCount}")) {
                $iPage++;
                $iLimitStart = ($iPage - 1) * $iLimitCount;
                /**
                 * Топики
                 */
                foreach ($aTopics as $aTopic) {
                    if ($aTopic['topic_slug']) {
                        continue;
                    }
                    $sSlug = InstallCore::transliteration($aTopic['topic_title']);
                    $sSlug = $this->GetUniqueTopicSlug($sSlug, $aTopic['topic_id']);
                    $sSlug = mysqli_escape_string($this->rDbLink, $sSlug);
                    /**
                     * Меняем тип топика
                     */
                    $this->dbQuery("UPDATE prefix_topic SET topic_slug = '{$sSlug}' WHERE topic_id ='{$aTopic['topic_id']}'");
                }
            }


            /**
             * Конвертируем аватарки блогов
             */
            $iPage = 1;
            $iLimitCount = 50;
            $iLimitStart = 0;
            while ($aBlogs = $this->dbSelect("SELECT * FROM prefix_blog  WHERE blog_avatar <> '' and blog_avatar <> '0' and blog_avatar  IS NOT NULL LIMIT {$iLimitStart},{$iLimitCount}")) {
                $iPage++;
                $iLimitStart = ($iPage - 1) * $iLimitCount;

                foreach ($aBlogs as $aBlog) {
                    $sAvatar = $aBlog['blog_avatar'];

                    if (strpos($sAvatar, 'http') === 0) {
                        $sAvatar = preg_replace('#_\d{1,3}x\d{1,3}(\.\w{3,5})$#i', '\\1', $sAvatar);
                        $sFileSource = $this->convertPathWebToServer($sAvatar);
                        /**
                         * Формируем список старых изображений для удаления
                         */
                        $sMask = pathinfo($sFileSource,
                                PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($sFileSource,
                                PATHINFO_FILENAME) . '_[0-9]*';
                        $aFilesForRemove = array();
                        if ($aPaths = glob($sMask)) {
                            foreach ($aPaths as $sPath) {
                                $aFilesForRemove[$sPath] = $sPath;
                            }
                        }

                        /**
                         * Ресайзим к новым размерам
                         */
                        if ($oImage = $this->createImageObject($sFileSource)) {
                            if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 500)) {
                                if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_500x500crop')) {
                                    unset($aFilesForRemove[$sFileSave]);
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 100)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_100x100crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 64)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_64x64crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 48)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_48x48crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 24)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_24x24crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            /**
                             * Удаляем старые
                             */
                            foreach ($aFilesForRemove as $sFileRemove) {
                                @unlink($sFileRemove);
                            }
                            /**
                             * Меняем путь до аватара
                             */
                            $sAvatar = '[relative]' . str_replace(dirname(dirname(INSTALL_DIR)), '', $sFileSource);
                            $sAvatar = mysqli_escape_string($this->rDbLink, $sAvatar);
                            $this->dbQuery("UPDATE prefix_blog SET blog_avatar = '{$sAvatar}' WHERE blog_id ='{$aBlog['blog_id']}'");
                        }
                    }
                }
            }

            /**
             * Конвертируем аватарки и фото пользователей
             * Дополнительно добавляем роль для прав
             */
            /**
             * Получаем текущий список админов
             */
            $aUserAdmin = array();
            if ($this->dbCheckTable("prefix_user_administrator")) {
                if ($aAdmins = $this->dbSelect("SELECT * FROM prefix_user_administrator ")) {
                    foreach ($aAdmins as $aRow) {
                        $aUserAdmin[] = $aRow['user_id'];
                    }
                }
            }
            $iPage = 1;
            $iLimitCount = 50;
            $iLimitStart = 0;
            while ($aUsers = $this->dbSelect("SELECT * FROM prefix_user LIMIT {$iLimitStart},{$iLimitCount}")) {
                $iPage++;
                $iLimitStart = ($iPage - 1) * $iLimitCount;

                foreach ($aUsers as $aUser) {
                    $sAvatar = $aUser['user_profile_avatar'];
                    $sPhoto = $aUser['user_profile_foto'];

                    /**
                     * Аватарки
                     */
                    if (strpos($sAvatar, 'http') === 0) {
                        $sAvatar = preg_replace('#_\d{1,3}x\d{1,3}(\.\w{3,5})$#i', '\\1', $sAvatar);
                        $sFileSource = $this->convertPathWebToServer($sAvatar);
                        /**
                         * Формируем список старых изображений для удаления
                         */
                        $sMask = pathinfo($sFileSource,
                                PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($sFileSource,
                                PATHINFO_FILENAME) . '_[0-9]*';
                        $aFilesForRemove = array();
                        if ($aPaths = glob($sMask)) {
                            foreach ($aPaths as $sPath) {
                                $aFilesForRemove[$sPath] = $sPath;
                            }
                        }

                        /**
                         * Ресайзим к новым размерам
                         */
                        if ($oImage = $this->createImageObject($sFileSource)) {
                            if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 100)) {
                                if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_100x100crop')) {
                                    unset($aFilesForRemove[$sFileSave]);
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 64)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_64x64crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 48)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_48x48crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            if ($oImage = $this->createImageObject($sFileSource)) {
                                if ($this->cropImage($oImage, 1) and $this->resizeImage($oImage, 24)) {
                                    if ($sFileSave = $this->saveImage($oImage, $sFileSource, '_24x24crop')) {
                                        unset($aFilesForRemove[$sFileSave]);
                                    }
                                }
                            }

                            /**
                             * Удаляем старые
                             */
                            foreach ($aFilesForRemove as $sFileRemove) {
                                @unlink($sFileRemove);
                            }
                            /**
                             * Меняем путь до аватара
                             */
                            $sAvatar = '[relative]' . str_replace(dirname(dirname(INSTALL_DIR)), '', $sFileSource);

                        }
                    }

                    /**
                     * Фото
                     */
                    if (strpos($sPhoto, 'http') === 0) {
                        $sFileSource = $this->convertPathWebToServer($sPhoto);
                        /**
                         * Меняем путь до аватара
                         */
                        $sPhoto = '[relative]' . str_replace(dirname(dirname(INSTALL_DIR)), '', $sFileSource);
                    }

                    /**
                     * Права
                     */
                    if (!$this->dbSelectOne("SELECT * FROM prefix_rbac_role_user WHERE user_id = '{$aUser['user_id']}' and role_id = 2 ")) {
                        /**
                         * Добавляем
                         */
                        $aFields = array(
                            'user_id'     => $aUser['user_id'],
                            'role_id'     => 2,
                            'date_create' => date("Y-m-d H:i:s"),
                        );
                        $this->dbInsertQuery('prefix_rbac_role_user', $aFields);
                    }

                    /**
                     * Timezone
                     */
                    $sTzName = null;
                    if ($aUser['user_settings_timezone']) {
                        $sTzName = $this->convertTzOffsetToName($aUser['user_settings_timezone']);
                    }

                    /**
                     * Реферальный код
                     */
                    $sReferralCode = $aUser['user_referral_code'];
                    if (!$sReferralCode) {
                        $sReferralCode = md5($aUser['user_id'] . '_' . mt_rand());
                    }

                    /**
                     * Админы
                     */
                    $isAdmin = 0;
                    if (in_array($aUser['user_id'], $aUserAdmin) or $aUser['user_admin']) {
                        $isAdmin = 1;
                    }

                    /**
                     * Сохраняем в БД
                     */
                    $sAvatar = mysqli_escape_string($this->rDbLink, $sAvatar);
                    $sPhoto = mysqli_escape_string($this->rDbLink, $sPhoto);
                    $this->dbQuery("UPDATE prefix_user SET user_admin = '{$isAdmin}' , user_referral_code = '{$sReferralCode}' , user_settings_timezone = " . ($sTzName ? "'{$sTzName}'" : 'null') . " , user_profile_avatar = '{$sAvatar}', user_profile_foto = '{$sPhoto}' WHERE user_id ='{$aUser['user_id']}'");

                    /**
                     * Удаляем таблицы
                     */
                    if ($this->dbCheckTable("prefix_user_administrator")) {
                        $this->dbQuery('DROP TABLE prefix_user_administrator');
                    }
                }
            }


            if ($aErrors = $this->getErrors()) {
                return $this->addError(join('<br/>', $aErrors));
            }
            return true;
        }
        return $this->addError(join('<br/>', $aErrors));
    }

    protected function convertTzOffsetToName($fOffset)
    {
        $fOffset *= 3600;
        $aAbbrarray = DateTimeZone::listAbbreviations();
        foreach ($aAbbrarray as $aAbbr) {
            foreach ($aAbbr as $aCity) {
                if ($aCity['offset'] == $fOffset && $aCity['timezone_id']) {
                    $oNow = new DateTime(null, new DateTimeZone($aCity['timezone_id']));
                    if ($oNow->getOffset() == $aCity['offset']) {
                        return $aCity['timezone_id'];
                    }
                }
            }
        }
        return false;
    }

    protected function convertPathWebToServer($sFile)
    {
        /**
         * Конвертируем в серверный
         */
        if (preg_match('#^http.*(\/uploads\/images\/.*)$#i', $sFile, $aMatch)) {
            $sFile = dirname(dirname(INSTALL_DIR)) . str_replace('/', DIRECTORY_SEPARATOR, $aMatch[1]);
        }
        return $sFile;
    }

    protected function createImageObject($sFile)
    {
        try {
            $oImagine = new \Imagine\Gd\Imagine;
            return $oImagine->open($sFile);
        } catch (Exception $e) {
            $this->addError($e->getMessage());
            return false;
        }
    }

    protected function cropImage($oImage, $fProp, $sPosition = 'center')
    {
        try {
            $oBox = $oImage->getSize();
            $iWidth = $oBox->getWidth();
            $iHeight = $oBox->getHeight();
            /**
             * Если высота и ширина уже в нужных пропорциях, то возвращаем изначальный вариант
             */
            $iProp = round($fProp, 2);
            if (round($iWidth / $iHeight, 2) == $iProp) {
                return $this;
            }
            /**
             * Вырезаем прямоугольник из центра
             */
            if (round($iWidth / $iHeight, 2) <= $iProp) {
                $iNewWidth = $iWidth;
                $iNewHeight = round($iNewWidth / $iProp);
            } else {
                $iNewHeight = $iHeight;
                $iNewWidth = $iNewHeight * $iProp;
            }

            $oBoxCrop = new Imagine\Image\Box($iNewWidth, $iNewHeight);
            if ($sPosition == 'center') {
                $oPointStart = new Imagine\Image\Point(($iWidth - $iNewWidth) / 2, ($iHeight - $iNewHeight) / 2);
            } else {
                $oPointStart = new Imagine\Image\Point(0, 0);
            }
            $oImage->crop($oPointStart, $oBoxCrop);
            return true;
        } catch (Exception $e) {
            $this->addError($e->getMessage());
            return false;
        }
    }

    protected function resizeImage($oImage, $iWidthDest, $iHeightDest = null, $bForcedMinSize = true)
    {
        try {
            $oBox = $oImage->getSize();

            if ($bForcedMinSize) {
                if ($iWidthDest and $iWidthDest > $oBox->getWidth()) {
                    $iWidthDest = $oBox->getWidth();
                }
                if ($iHeightDest and $iHeightDest > $oBox->getHeight()) {
                    $iHeightDest = $oBox->getHeight();
                }
            }
            if (!$iHeightDest) {
                /**
                 * Производим пропорциональное уменьшение по ширине
                 */
                $oBoxResize = $oBox->widen($iWidthDest);
            } elseif (!$iWidthDest) {
                /**
                 * Производим пропорциональное уменьшение по высоте
                 */
                $oBoxResize = $oBox->heighten($iHeightDest);
            } else {
                $oBoxResize = new Imagine\Image\Box($iWidthDest, $iHeightDest);
            }

            $oImage->resize($oBoxResize);
            return true;
        } catch (Exception $e) {
            $this->addError($e->getMessage());
            return false;
        }
    }

    protected function saveImage($oImage, $sFileSource, $sFilePostfix)
    {
        $sDir = pathinfo($sFileSource, PATHINFO_DIRNAME);
        $sName = pathinfo($sFileSource, PATHINFO_FILENAME);
        $sFormat = pathinfo($sFileSource, PATHINFO_EXTENSION);
        $sFile = $sDir . DIRECTORY_SEPARATOR . $sName . $sFilePostfix . '.' . $sFormat;

        try {
            $oImage->save($sFile, array(
                'format'  => $sFormat,
                'quality' => 95,
            ));
            return $sFile;
        } catch (Exception $e) {
            $this->addError($e->getMessage());
            // TODO: fix exception for Gd driver
            if (strpos($e->getFile(), 'Imagine' . DIRECTORY_SEPARATOR . 'Gd')) {
                restore_error_handler();
            }
            return false;
        }
    }

    protected function GetUniqueTopicSlug($sSlug, $iSkipTopicId = null)
    {
        $iPostfix = 0;
        do {
            $sUrl = $sSlug . ($iPostfix ? '-' . $iPostfix : '');
            $iPostfix++;
        } while (($aTopic = $this->getTopicBySlug($sUrl)) and (is_null($iSkipTopicId) or $iSkipTopicId != $aTopic['topic_id']));

        return $sUrl;
    }

    protected function getTopicBySlug($sUrl)
    {
        $sUrl = mysqli_escape_string($this->rDbLink, $sUrl);
        return $this->dbSelectOne("SELECT * FROM prefix_topic WHERE topic_slug = '{$sUrl}' ");
    }
}