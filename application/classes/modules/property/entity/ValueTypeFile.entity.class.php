<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Объект управления типом file
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeFile extends ModuleProperty_EntityValueType
{

    public function getValueForDisplay()
    {
        return $this->getFileFullName();
    }

    public function isEmpty()
    {
        return $this->getFileFullName() ? false : true;
    }

    public function validate()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();
        $iPropertyId = $oProperty->getId();

        $bNeedRemove=false;
        $mValue = $this->getValueForValidate();
        if (isset($mValue['remove']) and $mValue['remove']) {
            $bNeedRemove=true;
            $this->setValueForValidate(array('remove' => true));
        }

        $sFileName = $this->_getValueFromFiles($iPropertyId, 'name');
        $sFileTmpName = $this->_getValueFromFiles($iPropertyId, 'tmp_name');
        $sFileError = $this->_getValueFromFiles($iPropertyId, 'error');
        $sFileSize = $this->_getValueFromFiles($iPropertyId, 'size');

        if (!$sFileTmpName) {
            if ($oProperty->getValidateRuleOne('allowEmpty')) {
                return true;
            } elseif ($aFilePrev = $oValue->getDataOne('file') and isset($aFilePrev['path']) and !$bNeedRemove) {
                return true;
            } else {
                return 'Необходимо выбрать файл';
            }
        }
        /**
         * Проверяем на ошибки
         */
        if ($sFileError and $sFileError != UPLOAD_ERR_NO_FILE) {
            return "При загрузке файла возникла ошибка - {$sFileError}";
        }
        /**
         * На корректность загрузки
         */
        if (!$sFileName or !$sFileTmpName) {
            return false;
        }
        /**
         * На ограничение по размеру файла
         */
        if ($iSizeKb = $oProperty->getValidateRuleOne('size_max') and $iSizeKb * 1024 < $sFileSize) {
            return "Превышен размер файла, максимальный {$iSizeKb}Kb";
        }
        /**
         * На допустимые типы файлов
         */
        $aPath = pathinfo($sFileName);
        if (!isset($aPath['extension']) or !$aPath['extension']) {
            return false;
        }
        if ($aTypes = $oProperty->getParam('types') and !in_array($aPath['extension'], $aTypes)) {
            return 'Неверный тип файла, допустимы ' . join(', ', $aTypes);
        }
        /**
         * Пробрасываем данные по файлу
         */
        $this->setValueForValidate(array(
            'name'     => $sFileName,
            'tmp_name' => $sFileTmpName,
            'error'    => $sFileError,
            'size'     => $sFileSize,
        ));
        return true;
    }

    protected function _getValueFromFiles($iId, $sName)
    {
        if (isset($_FILES['property'][$sName][$iId]['file'])) {
            return $_FILES['property'][$sName][$iId]['file'];
        }
        return null;
    }

    /**
     * Устанавливает значение после валидации конкретного поля, а не всех полей
     * Поэтому здесь нельзя сохранять файл, это нужно делать в beforeSaveValue()
     *
     * @param $aValue
     */
    public function setValue($aValue)
    {
        $oValue = $this->getValueObject();
        /**
         * Просто пробрасываем данные
         */
        if ($aValue) {
            $oValue->setDataOne('file_raw', $aValue);
        }
    }

    /**
     * Дополнительная обработка перед сохранением значения
     * Здесь нужно выполнять основную загрузку файла
     */
    public function beforeSaveValue()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();
        if (!$aFile = $oValue->getDataOne('file_raw')) {
            return true;
        }
        $oValue->setDataOne('file_raw', null);
        /**
         * Удаляем предыдущий файл
         */
        if (isset($aFile['remove']) or isset($aFile['name'])) {
            if ($aFilePrev = $oValue->getDataOne('file')) {
                $this->RemoveFile($aFilePrev['path']);
                $oValue->setDataOne('file', array());
                $oValue->setValueVarchar(null);
            }
        }

        if (isset($aFile['name'])) {
            /**
             * Выполняем загрузку файла
             */
            $aPathInfo = pathinfo($aFile['name']);
            $sExtension = isset($aPathInfo['extension']) ? $aPathInfo['extension'] : 'unknown';
            $sFileName = func_generator(20) . '.' . $sExtension;
            /**
             * Копируем загруженный файл
             */
            $sDirTmp = Config::Get('path.tmp.server') . '/property/';
            if (!is_dir($sDirTmp)) {
                @mkdir($sDirTmp, 0777, true);
            }
            $sFileTmp = $sDirTmp . $sFileName;

            if (move_uploaded_file($aFile['tmp_name'], $sFileTmp)) {
                $sDirSave = Config::Get('path.root.server') . $oProperty->getSaveFileDir();
                if (!is_dir($sDirSave)) {
                    @mkdir($sDirSave, 0777, true);
                }
                $sFilePath = $sDirSave . $sFileName;
                /**
                 * Сохраняем файл
                 */
                if ($sFilePathNew = $this->SaveFile($sFileTmp, $sFilePath, null, true)) {
                    /**
                     * Сохраняем данные о файле
                     */
                    $oValue->setDataOne('file', array(
                        'path'      => $sFilePathNew,
                        'size'      => filesize($sFilePath),
                        'name'      => htmlspecialchars($aPathInfo['filename']),
                        'extension' => htmlspecialchars($aPathInfo['extension']),
                    ));
                    /**
                     * Сохраняем уникальный ключ для доступа к файлу
                     */
                    $oValue->setValueVarchar(func_generator(32));
                    return true;
                }
            }
        }
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;

        if (isset($aRulesRaw['size_max']) and is_numeric($aRulesRaw['size_max'])) {
            $aRules['size_max'] = (int)$aRulesRaw['size_max'];
        }
        return $aRules;
    }

    public function prepareParamsRaw($aParamsRaw)
    {
        $aParams = array();

        $aParams['types'] = array();
        if (isset($aParamsRaw['types']) and is_array($aParamsRaw['types'])) {
            foreach ($aParamsRaw['types'] as $sType) {
                if ($sType) {
                    $aParams['types'][] = htmlspecialchars($sType);
                }
            }
        }
        $aParams['access_only_auth'] = isset($aParamsRaw['access_only_auth']) ? true : false;

        return $aParams;
    }

    public function getParamsDefault()
    {
        return array(
            'types' => array(
                'zip'
            ),
        );
    }

    public function removeValue()
    {
        $oValue = $this->getValueObject();
        /**
         * Удаляем файл
         */
        if ($aFilePrev = $oValue->getDataOne('file')) {
            $this->RemoveFile($aFilePrev['path']);
        }
    }

    public function getFileFullName()
    {
        $oValue = $this->getValueObject();
        if ($aFilePrev = $oValue->getDataOne('file')) {
            return $aFilePrev['name'] . '.' . $aFilePrev['extension'];
        }
        return null;
    }

    public function getCountDownloads()
    {
        $aStats=$this->oValue->getDataOne('stats');
        return isset($aStats['count_download']) ? $aStats['count_download'] : 0;
    }

    /**
     * Сохраняет(копирует) файл на сервер
     * Если переопределить данный метод, то можно сохранять файл, например, на Amazon S3
     *
     * @param string $sFileSource Полный путь до исходного файла
     * @param string $sFileDest Полный путь до файла для сохранения с типом, например, [server]/home/var/site.ru/book.pdf
     * @param int|null $iMode Права chmod для файла, например, 0777
     * @param bool $bRemoveSource Удалять исходный файл или нет
     * @return bool | string    При успешном сохранении возвращает относительный путь до файла с типом, например, [relative]/image.jpg
     */
    protected function SaveFile($sFileSource, $sFileDest, $iMode = null, $bRemoveSource = false)
    {
        if ($this->Fs_SaveFileLocal($sFileSource, $this->Fs_GetPathServer($sFileDest), $iMode, $bRemoveSource)) {
            return $this->Fs_MakePath($this->Fs_GetPathRelativeFromServer($sFileDest), ModuleFs::PATH_TYPE_RELATIVE);
        }
        return false;
    }

    /**
     * Удаляет файл
     * Если переопределить данный метод, то можно удалять файл, например, с Amazon S3
     *
     * @param string $sPathFile Полный путь до файла с типом, например, [relative]/book.pdf
     *
     * @return mixed
     */
    protected function RemoveFile($sPathFile)
    {
        $sPathFile = $this->Fs_GetPathServer($sPathFile);
        return $this->Fs_RemoveFileLocal($sPathFile);
    }

    public function DownloadFile()
    {
        $oValue = $this->getValueObject();
        if ($aFilePrev = $oValue->getDataOne('file')) {
            $this->Tools_DownloadFile($this->Fs_GetPathServer($aFilePrev['path']),
                $aFilePrev['name'] . '.' . $aFilePrev['extension'], $aFilePrev['size']);
        }
        return false;
    }
}