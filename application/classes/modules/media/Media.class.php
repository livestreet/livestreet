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
 * Модуль управления медиа-данными (изображения, видео и т.п.)
 *
 * @package application.modules.media
 * @since 2.0
 */
class ModuleMedia extends ModuleORM
{
    /**
     * Список типов медиа
     * Свои кастомные типы необходимо нумеровать с 1000
     */
    const MEDIA_TYPE_IMAGE = 1;
    const MEDIA_TYPE_VIDEO = 2;
    /**
     * Список типов для проверки доступа
     */
    const TYPE_CHECK_ALLOW_ADD = 'add';
    const TYPE_CHECK_ALLOW_REMOVE = 'remove';
    const TYPE_CHECK_ALLOW_UPDATE = 'update';
    const TYPE_CHECK_ALLOW_PREVIEW = 'preview'; // возможность создания превью для объекта
    const TYPE_CHECK_ALLOW_VIEW_LIST = 'view_list'; // просмотр списка медиа у объекта
    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent;

    protected $oMapper = null;

    protected $aTargetTypes = array(
        'topic'   => array(
            'allow_preview' => true,
        ),
        'comment' => array(),
        'blog'    => array(),
        'talk'    => array(),
    );

    /**
     * Список доступных типов медиа
     *
     * @var array
     */
    protected $aMediaTypes = array(
        self::MEDIA_TYPE_IMAGE,
        self::MEDIA_TYPE_VIDEO
    );

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Возвращает список типов объектов
     *
     * @return array
     */
    public function GetTargetTypes()
    {
        return $this->aTargetTypes;
    }

    /**
     * Добавляет в разрешенные новый тип
     *
     * @param string $sTargetType Тип
     * @param array $aParams Параметры
     * @return bool
     */
    public function AddTargetType($sTargetType, $aParams = array())
    {
        if (!array_key_exists($sTargetType, $this->aTargetTypes)) {
            $this->aTargetTypes[$sTargetType] = $aParams;
            return true;
        }
        return false;
    }

    /**
     * Проверяет разрешен ли данный тип
     *
     * @param string $sTargetType Тип
     * @return bool
     */
    public function IsAllowTargetType($sTargetType)
    {
        return in_array($sTargetType, array_keys($this->aTargetTypes));
    }

    /**
     * Возвращает парметры нужного типа
     *
     * @param string $sTargetType
     *
     * @return array|null
     */
    public function GetTargetTypeParams($sTargetType)
    {
        if ($this->IsAllowTargetType($sTargetType)) {
            return $this->aTargetTypes[$sTargetType];
        }
        return null;
    }

    /**
     * Возвращает конкретный параметр нужного типа
     *
     * @param string $sTargetType
     * @param string $sName
     *
     * @return mixed|null
     */
    public function GetTargetTypeParam($sTargetType, $sName)
    {
        $aParams = $this->GetTargetTypeParams($sTargetType);
        if ($aParams and array_key_exists($sName, $aParams)) {
            return $aParams[$sName];
        }
        return null;
    }

    /**
     * Проверяет разрешен ли тип медиа
     *
     * @param string $sType
     *
     * @return bool
     */
    public function IsAllowMediaType($sType)
    {
        return in_array($sType, $this->aMediaTypes);
    }

    /**
     * Возвращает тип медиа по имени файла (имя файла должно содержать его расширение)
     *
     * @param $sFile
     * @return int|null
     */
    public function GetMediaTypeByFileName($sFile)
    {
        $aPathInfo = pathinfo($sFile);
        $sExtension = isset($aPathInfo['extension']) ? $aPathInfo['extension'] : 'unknown';
        return $this->GetMediaTypeByFileExtension($sExtension);
    }

    /**
     * Возвращает тип медиа по расширению файла
     *
     * @param $sExtension
     * @return int|null
     */
    public function GetMediaTypeByFileExtension($sExtension)
    {
        $sExtension = strtolower($sExtension);
        if (in_array($sExtension, array('jpg', 'jpeg', 'gif', 'png'))) {
            return self::MEDIA_TYPE_IMAGE;
        }
        return null;
    }

    /**
     * Возвращает название медиа типа по его значению
     * Название получается автоматически из константы с определением типа
     *
     * @param $iType
     * @return null|string
     */
    public function GetMediaTypeName($iType)
    {
        $oRefl = new ReflectionObject($this);
        foreach ($oRefl->getConstants() as $sName => $mValue) {
            if (strpos($sName, 'MEDIA_TYPE_') === 0 and $mValue == $iType) {
                return strtolower(substr($sName, strlen('MEDIA_TYPE_')));
            }
        }
        return null;
    }

    /**
     * Проверка объекта target - владелец медиа
     *
     * @param string $sTargetType Тип
     * @param int $iTargetId ID владельца
     * @param string $sAllowType
     * @param array $aParams
     * @return bool
     */
    public function CheckTarget($sTargetType, $iTargetId = null, $sAllowType = null, $aParams = array())
    {
        if (!$this->IsAllowTargetType($sTargetType)) {
            return false;
        }
        $sMethod = 'CheckTarget' . func_camelize($sTargetType);
        if (method_exists($this, $sMethod)) {
            if (!array_key_exists('user', $aParams)) {
                $aParams['user'] = $this->oUserCurrent;
            }
            return $this->$sMethod($iTargetId, $sAllowType, $aParams);
        }
        return false;
    }

    public function NotifyCreatePreviewTarget($sTargetType, $iTargetId, $oRelationTarget)
    {
        if (!$this->IsAllowTargetType($sTargetType)) {
            return false;
        }
        $sMethod = 'NotifyCreatePreviewTarget' . func_camelize($sTargetType);
        if (method_exists($this, $sMethod)) {
            return $this->$sMethod($iTargetId, $oRelationTarget);
        }
        return false;
    }

    public function NotifyRemovePreviewTarget($sTargetType, $iTargetId, $oRelationTarget)
    {
        if (!$this->IsAllowTargetType($sTargetType)) {
            return false;
        }
        $sMethod = 'NotifyRemovePreviewTarget' . func_camelize($sTargetType);
        if (method_exists($this, $sMethod)) {
            return $this->$sMethod($iTargetId, $oRelationTarget);
        }
        return false;
    }

    /**
     * Возвращает параметр конфига с учетом текущего target_type
     *
     * @param string $sParam Ключ конфига относительно module.media
     * @param string $sTargetType Тип
     *
     * @return mixed
     */
    public function GetConfigParam($sParam, $sTargetType)
    {
        $mValue = Config::Get("module.media.type.{$sTargetType}.{$sParam}");
        if (!$mValue) {
            $mValue = Config::Get("module.media.{$sParam}");
        }
        return $mValue;
    }

    /**
     * Проверяем файл на максимальный размер с учетом типа медиа
     *
     * @param $iSize
     * @param $iMediaType
     * @param $sTargetType
     * @return bool
     */
    public function CheckFileUploadSize($iSize, $iMediaType, $sTargetType)
    {
        $sConfigParam = 'max_size';
        $sMediaTypeName = $this->Media_GetMediaTypeName($iMediaType);
        if (is_null($iMaxSizeKb = $this->GetConfigParam($sMediaTypeName . '.' . $sConfigParam, $sTargetType))) {
            $iMaxSizeKb = $this->GetConfigParam($sConfigParam, $sTargetType);
        }
        if ($iSize > $iMaxSizeKb * 1024) {
            return $this->Lang_Get('media.error.too_large', array('size' => $iMaxSizeKb));
        }
        return true;
    }

    public function Upload($aFile, $sTargetType, $sTargetId, $sTargetTmp = null)
    {
        if (is_string($aFile)) {
            return $this->UploadUrl($aFile, $sTargetType, $sTargetId, $sTargetTmp);
        } else {
            return $this->UploadLocal($aFile, $sTargetType, $sTargetId, $sTargetTmp);
        }
    }

    public function UploadLocal($aFile, $sTargetType, $sTargetId, $sTargetTmp = null)
    {
        if (!is_array($aFile) || !isset($aFile['error']) || !isset($aFile['tmp_name']) || !is_string($aFile['tmp_name']) || !isset($aFile['name']) || !isset($aFile['size'])) {
            return false;
        }

        if ($aFile['error'] != UPLOAD_ERR_OK) {
            switch ($aFile['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    return $this->Lang_Get('media.error.too_large', array('size' => @func_ini_return_bytes(ini_get('upload_max_filesize')) / 1024));
                default:
                    return $this->Lang_Get('media.error.upload');
            }
        }

        $aPathInfo = pathinfo($aFile['name']);
        $sExtension = isset($aPathInfo['extension']) ? $aPathInfo['extension'] : 'unknown';
        $sFileName = $aPathInfo['filename'] . '.' . $sExtension;
        /**
         * Проверяем на размер файла
         */
        $iMediaType = $this->GetMediaTypeByFileExtension($sExtension);
        if (true !== ($mRes = $this->CheckFileUploadSize($aFile['size'], $iMediaType, $sTargetType))) {
            return $mRes;
        }
        /**
         * Копируем загруженный файл
         */
        $sDirTmp = Config::Get('path.tmp.server') . '/media/';
        if (!is_dir($sDirTmp)) {
            @mkdir($sDirTmp, 0777, true);
        }
        $sFileTmp = $sDirTmp . $sFileName;
        if (!move_uploaded_file($aFile['tmp_name'], $sFileTmp)) {
            return $this->Lang_Get('media.error.upload');
        }

        return $this->ProcessingFile($sFileTmp, $sTargetType, $sTargetId, $sTargetTmp);
    }

    public function UploadUrl($sFileUrl, $sTargetType, $sTargetId, $sTargetTmp = null)
    {
        /**
         * Проверяем, является ли файл изображением
         * TODO: файл может быть не только изображением, поэтому требуется рефакторинг
         */
        if (!$aImageInfo = (@getimagesize($sFileUrl))) {
            return $this->Lang_Get('media.error.not_image');
        }
        $aTypeImage = array(
            1 => 'gif',
            2 => 'jpg',
            3 => 'png'
        ); // see http://php.net/manual/en/function.exif-imagetype.php
        $sExtension = isset($aTypeImage[$aImageInfo[2]]) ? $aTypeImage[$aImageInfo[2]] : 'jpg';
        /**
         * Открываем файловый поток и считываем файл поблочно,
         * контролируя максимальный размер изображения
         */
        $rFile = fopen($sFileUrl, 'r');
        if (!$rFile) {
            return $this->Lang_Get('media.error.upload');
        }

        $iMaxSizeKb = $this->GetConfigParam('max_size', $sTargetType);
        $iSizeKb = 0;
        $sContent = '';
        while (!feof($rFile) and $iSizeKb < $iMaxSizeKb) {
            $sContent .= fread($rFile, 1024 * 2);
            $iSizeKb++;
        }
        /**
         * Если конец файла не достигнут,
         * значит файл имеет недопустимый размер
         */
        if (!feof($rFile)) {
            return $this->Lang_Get('media.error.too_large', array('size' => $iMaxSizeKb));
        }
        fclose($rFile);
        /**
         * Копируем загруженный файл
         */
        $sDirTmp = Config::Get('path.tmp.server') . '/media/';
        if (!is_dir($sDirTmp)) {
            @mkdir($sDirTmp, 0777, true);
        }
        $sFileTmp = $sDirTmp . func_generator() . '.' . $sExtension;
        $rFile = fopen($sFileTmp, 'w');
        fwrite($rFile, $sContent);
        fclose($rFile);

        return $this->ProcessingFile($sFileTmp, $sTargetType, $sTargetId, $sTargetTmp);
    }

    public function ProcessingFile($sFileTmp, $sTargetType, $sTargetId, $sTargetTmp = null)
    {
        /**
         * Определяем тип медиа по файлу и запускаем обработку
         */
        $iType = $this->GetMediaTypeByFileName($sFileTmp);
        if ($iType == self::MEDIA_TYPE_IMAGE) {
            return $this->ProcessingFileImage($sFileTmp, $sTargetType, $sTargetId, $sTargetTmp);
        }
        return $this->Lang_Get('media.error.incorrect_type');
    }

    public function ProcessingFileImage($sFileTmp, $sTargetType, $sTargetId, $sTargetTmp = null)
    {
        $aPathInfo = pathinfo($sFileTmp);
        $aParams = $this->Image_BuildParams('media.' . $sTargetType);
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_Open($sFileTmp, $aParams)) {
            $this->Fs_RemoveFileLocal($sFileTmp);
            return $this->Image_GetLastError();
        }
        $iWidth = $oImage->getWidth();
        $iHeight = $oImage->getHeight();

        $sPath = $this->GetSaveDir($sTargetType, $sTargetId);
        /**
         * Уникальное имя файла
         */
        $sFileName = func_generator(20);
        /**
         * Сохраняем оригинальную копию
         */
        if (!$sFileResult = $oImage->saveSmart($sPath, $sFileName)) {
            $this->Fs_RemoveFileLocal($sFileTmp);
            return $this->Image_GetLastError();
        }

        $aSizes = $this->GetConfigParam('image.sizes', $sTargetType);
        /**
         * Перед запуском генерации подчищаем память
         */
        unset($oImage);
        /**
         * Генерируем варианты с необходимыми размерами
         */
        $this->GenerateImageBySizes($sFileTmp, $sPath, $sFileName, $aSizes, $aParams);
        /**
         * Сохраняем медиа
         */
        $oMedia = Engine::GetEntity('ModuleMedia_EntityMedia');
        $oMedia->setUserId($this->oUserCurrent ? $this->oUserCurrent->getId() : null);
        $oMedia->setType(self::MEDIA_TYPE_IMAGE);
        $oMedia->setTargetType($sTargetType);
        $oMedia->setFilePath($sFileResult);
        $oMedia->setFileName($aPathInfo['filename']);
        $oMedia->setFileSize(filesize($sFileTmp));
        $oMedia->setWidth($iWidth);
        $oMedia->setHeight($iHeight);
        $oMedia->setDataOne('image_sizes', $aSizes);
        /**
         * Теперь можно удалить временный файл
         */
        $this->Fs_RemoveFileLocal($sFileTmp);
        /**
         * Добавляем в БД
         */
        if ($oMedia->Add()) {
            /**
             * Создаем связь с владельцем
             */
            $oTarget = Engine::GetEntity('ModuleMedia_EntityTarget');
            $oTarget->setMediaId($oMedia->getId());
            $oTarget->setTargetType($sTargetType);
            $oTarget->setTargetId($sTargetId ? $sTargetId : null);
            $oTarget->setTargetTmp($sTargetTmp ? $sTargetTmp : null);
            if ($oTarget->Add()) {
                $oMedia->_setData(array('_relation_entity' => $oTarget));
                return $oMedia;
            }
        }
        return false;
    }

    /**
     * Создает набор отресайзанных изображений
     * Варианты наименований результирующих файлов в зависимости от размеров:
     *    file_100x150 - w=100 h=150 crop=false
     *    file_100x150crop - w=100 h=150 crop=true
     *    file_x150 - w=null h=150 crop=false
     *    file_100x - w=100 h=null crop=false
     *
     * @param      $sFileSource
     * @param      $sDirDist
     * @param      $sFileName
     * @param      $aSizes
     * @param null $aParams
     */
    public function GenerateImageBySizes($sFileSource, $sDirDist, $sFileName, $aSizes, $aParams = null)
    {
        if (!$aSizes) {
            return;
        }
        /**
         * Преобразуем упрощенную запись списка размеров в полную
         */
        foreach ($aSizes as $k => $v) {
            if (!is_array($v)) {
                $aSizes[$k] = $this->ParsedImageSize($v);
            }
        }
        $sFileResult = null;
        foreach ($aSizes as $aSize) {
            /**
             * Для каждого указанного в конфиге размера генерируем картинку
             */
            $sNewFileName = $sFileName . '_' . $aSize['w'] . 'x' . $aSize['h'];
            if ($oImage = $this->Image_Open($sFileSource, $aParams)) {
                if ($aSize['crop']) {
                    $oImage->cropProportion($aSize['w'] / $aSize['h'], 'center');
                    $sNewFileName .= 'crop';
                }
                if (!$sFileResult = $oImage->resize($aSize['w'], $aSize['h'], true)->saveSmart($sDirDist,
                    $sNewFileName)
                ) {
                    // TODO: прерывать и возвращать false?
                }
            }
        }
        /**
         * Возвращаем путь до последнего созданного файла
         */
        return $sFileResult;
    }

    public function RemoveImageBySizes($sPath, $aSizes, $bRemoveOriginal = true)
    {
        if ($aSizes) {
            /**
             * Преобразуем упрощенную запись списка размеров в полную
             */
            foreach ($aSizes as $k => $v) {
                if (!is_array($v)) {
                    $aSizes[$k] = $this->ParsedImageSize($v);
                }
            }
            foreach ($aSizes as $aSize) {
                $sSize = $aSize['w'] . 'x' . $aSize['h'];
                if ($aSize['crop']) {
                    $sSize .= 'crop';
                }
                $this->Image_RemoveFile($this->GetImagePathBySize($sPath, $sSize));
            }
        }
        /**
         * Удаляем оригинал
         */
        if ($bRemoveOriginal) {
            $this->Image_RemoveFile($sPath);
        }
    }

    /**
     * Возвращает каталог для сохранения контента медиа
     *
     * @param string $sTargetType
     * @param string|null $sTargetId Желательно для одного типа при формировании каталога для загрузки выбрать что-то одно - использовать $sTargetId или нет
     * @param string $sPostfix Дополнительный каталог для сохранения в конце цепочки
     *
     * @return string
     */
    public function GetSaveDir($sTargetType, $sTargetId = null, $sPostfix = '')
    {
        $sPostfix = trim($sPostfix, '/');
        return Config::Get('path.uploads.base') . "/media/{$sTargetType}/" . date('Y/m/d/H/') . ($sPostfix ? "{$sPostfix}/" : '');
    }

    public function BuildCodeForEditor($oMedia, $aParams)
    {
        $sCode = '';
        if ($oMedia->getType() == self::MEDIA_TYPE_IMAGE) {
            $aSizes = (array)$oMedia->getDataOne('image_sizes');

            $sSizeParam = isset($aParams['size']) ? (string)$aParams['size'] : '';
            $sSize = 'original';
            $bNeedHref = false;
            /**
             * Проверяем корректность размера
             */
            foreach ($aSizes as $aSizeAllow) {
                $sSizeKey = $aSizeAllow['w'] . 'x' . $aSizeAllow['h'] . ($aSizeAllow['crop'] ? 'crop' : '');
                if ($sSizeKey == $sSizeParam) {
                    $sSize = $sSizeKey;
                    /**
                     * Необходимость лайтбокса
                     */
                    if ($aSizeAllow['w'] < $oMedia->getWidth()) {
                        $bNeedHref = true;
                    }
                }
            }

            $sPath = $oMedia->getFileWebPath($sSize == 'original' ? null : $sSize);
            $aParams['image_url'] = (isset($aParams['relative_web']) and $aParams['relative_web']) ? $this->GetPathRelativeWebFromWeb($sPath) : $sPath;
            $aParams['href_url'] = (isset($aParams['relative_web']) and $aParams['relative_web']) ? $this->GetPathRelativeWebFromWeb($oMedia->getFileWebPath()) : $oMedia->getFileWebPath();
            $aParams['need_href'] = $bNeedHref;
            if (!isset($aParams['title'])) {
                $aParams['title'] = $oMedia->getDataOne('title');
            }
            /**
             * Формируем HTML изображения
             */
            $sCode = $this->BuildImageCodeForEditor($aParams);
        }

        return $sCode;
    }

    /**
     * Возвращает относительный веб путь до изображения
     *
     * @param string $sPath Полный веб путь до изображения
     * @return string
     */
    public function GetPathRelativeWebFromWeb($sPath)
    {
        return '/' . ltrim(parse_url($sPath, PHP_URL_PATH), '/');
    }

    /**
     * Формирует HTML изображения
     *
     * @param $aParams
     * @return string
     */
    public function BuildImageCodeForEditor($aParams)
    {
        $sCode = '<img src="' . htmlspecialchars($aParams['image_url']) . '" ';
        if (!isset($aParams['skip_title']) and isset($aParams['title']) and $aParams['title']) {
            $sCode .= ' title="' . htmlspecialchars($aParams['title']) . '" ';
            $sCode .= ' alt="' . htmlspecialchars($aParams['title']) . '" ';
        }
        if (isset($aParams['align']) and in_array($aParams['align'], array('left', 'right', 'center'))) {
            if ($aParams['align'] == 'center') {
                $sCode .= ' class="image-center"';
            } else {
                $sCode .= ' align="' . htmlspecialchars($aParams['align']) . '" ';
            }
        }
        $sDataParams = '';
        if (isset($aParams['data']) and is_array($aParams['data'])) {
            foreach ($aParams['data'] as $sDataName => $sDataValue) {
                if ($sDataValue) {
                    $sDataParams .= ' data-' . $sDataName . '="' . htmlspecialchars($sDataValue) . '"';
                }
            }
        }
        if (isset($aParams['need_href']) and $aParams['need_href']) {
            $sCode .= ' />';
            $sLbxGroup = '';
            if (isset($aParams['lbx_group'])) {
                $sLbxGroup = ' data-rel="' . htmlspecialchars($aParams['lbx_group']) . '"';
            }
            $sCode = '<a class="js-lbx" ' . $sLbxGroup . ' href="' . htmlspecialchars($aParams['href_url']) . '" ' . $sDataParams . '>' . $sCode . '</a>';
        } else {
            $sCode .= $sDataParams . ' />';
        }
        return $sCode;
    }

    public function GetMediaByTarget($sTargetType, $iTargetId, $iUserId = null)
    {
        return $this->oMapper->GetMediaByTarget($sTargetType, $iTargetId, $iUserId);
    }

    public function GetMediaByTargetTmp($sTargetTmp, $iUserId = null)
    {
        return $this->oMapper->GetMediaByTargetTmp($sTargetTmp, $iUserId);
    }

    /**
     * Выполняет удаление файлов медиа-объекта
     *
     * @param $oMedia
     */
    public function DeleteFiles($oMedia)
    {
        /**
         * Сначала удаляем все файлы
         */
        if ($oMedia->getType() == self::MEDIA_TYPE_IMAGE) {
            $aSizes = $oMedia->getDataOne('image_sizes');
            $this->RemoveImageBySizes($oMedia->getFilePath(), $aSizes);
        }
    }

    /**
     * Возвращает список media с учетов прав доступа текущего пользователя
     * В методе происходит проверка на права:
     * 1. разрешаем объекты, которые создал пользователь
     * 2. разрешаем объекты, которые связаны таргетом, к которому у пользователя есть доступ на редактирование
     * @param array $aId
     *
     * @return array
     */
    public function GetAllowMediaItemsById($aId)
    {
        $aIdItems = array();
        foreach ((array)$aId as $iId) {
            $aIdItems[] = (int)$iId;
        }
        $aIdItemsAll = $aIdItems;

        if (is_array($aIdItems) and count($aIdItems)) {
            $iUserId = $this->oUserCurrent ? $this->oUserCurrent->getId() : null;
            $aMediaItems = $this->Media_GetMediaItemsByFilter(array(
                    '#where' => array(
                        'id in (?a) AND ( user_id is null OR user_id = ?d )' => array(
                            $aIdItems,
                            $iUserId
                        )
                    ),
                    '#index-from-primary'
                )
            );

            /**
             * Смотрим что осталось
             */
            if (!is_null($iUserId) and $aIdItems = array_diff($aIdItems, array_keys($aMediaItems))) {
                $aMediaAllowIds = array();
                $aTargetMediaGroup = $this->GetTargetItemsByFilter(array(
                    'media_id in'  => $aIdItems,
                    '#index-group' => 'media_id'
                ));
                $_this = $this;
                foreach ($aTargetMediaGroup as $iMediaId => $aTargetMedia) {
                    /**
                     * Проверяем каждый таргет
                     */
                    foreach ($aTargetMedia as $oTargetMedia) {
                        if ($this->Cache_Remember("media_check_target_{$oTargetMedia->getTargetType()}_{$oTargetMedia->getTargetId()}",
                            function () use ($_this, $oTargetMedia) {
                                return $_this->CheckTarget($oTargetMedia->getTargetType(), $oTargetMedia->getTargetId(),
                                    self::TYPE_CHECK_ALLOW_ADD);
                            }, false, array(), 'life', true)
                        ) {
                            $aMediaAllowIds[] = $oTargetMedia->getMediaId();
                            break;
                        }
                    }
                }
                if ($aMediaAllowIds) {
                    $aMediaItems = $aMediaItems + $this->GetMediaItemsByFilter(array(
                            'id in' => $aMediaAllowIds,
                            '#index-from-primary'
                        ));
                }
            }
            /**
             * Нужно отсортировать по первоначальному массиву $aIdItems
             */
            $aReturn = array();
            foreach ($aIdItemsAll as $iId) {
                if (isset($aMediaItems[$iId])) {
                    $aReturn[$iId] = $aMediaItems[$iId];
                }
            }
            return $aReturn;
        }
        return array();
    }

    /**
     * Обработка тега gallery в тексте
     * <pre>
     * <gallery items="12,55,38" />
     * </pre>
     *
     * @param string $sTag Тег на ктором сработал колбэк
     * @param array $aParams Список параметров тега
     * @return string
     */
    public function CallbackParserTagGallery($sTag, $aParams)
    {
        if (isset($aParams['items'])) {
            $aItems = explode(',', $aParams['items']);
            $aItems = array_unique($aItems);
        } else {
            return '';
        }

        if (!($aMediaItems = $this->GetAllowMediaItemsById($aItems))) {
            return '';
        }

        $aParamsMedia = array(
            'size'         => '100crop',
            'skip_title'   => true,
            'relative_web' => true
        );
        $sProperties = '';
        if (isset($aParams['nav']) and in_array($aParams['nav'], array('thumbs'))) {
            $sProperties .= ' data-nav="' . $aParams['nav'] . '" ';
        }
        $sTextResult = '<div class="fotorama" ' . $sProperties . '>' . "\r\n";
        foreach ($aMediaItems as $oMedia) {
            if (isset($aParams['caption']) and $aParams['caption']) {
                $aParamsMedia['data']['caption'] = htmlspecialchars($oMedia->getDataOne('title'));
            }
            $sTextResult .= "\t" . $this->BuildCodeForEditor($oMedia, $aParamsMedia) . "\r\n";
        }
        $sTextResult .= "</div>\r\n";
        return $sTextResult;
    }

    /**
     * Заменяет временный идентификатор на необходимый ID объекта
     *
     * @param string $sTargetType
     * @param string $sTargetId
     * @param null|string $sTargetTmp Если не задан, то берется их куки "media_target_tmp_{$sTargetType}"
     */
    public function ReplaceTargetTmpById($sTargetType, $sTargetId, $sTargetTmp = null)
    {
        $sCookieKey = 'media_target_tmp_' . $sTargetType;
        if (is_null($sTargetTmp) and $this->Session_GetCookie($sCookieKey)) {
            $sTargetTmp = $this->Session_GetCookie($sCookieKey);
            $this->Session_DropCookie($sCookieKey);
        }
        if (is_string($sTargetTmp)) {
            $aTargetItems = $this->Media_GetTargetItemsByTargetTmpAndTargetType($sTargetTmp, $sTargetType);
            foreach ($aTargetItems as $oTarget) {
                $oTarget->setTargetTmp(null);
                $oTarget->setTargetId($sTargetId);
                $oTarget->Update();
                /**
                 * Уведомляем объект о создании превью
                 */
                if ($oTarget->getIsPreview()) {
                    $this->NotifyCreatePreviewTarget($oTarget->getTargetType(), $oTarget->getTargetId(), $oTarget);
                }
            }
        }
    }

    /**
     * Удаляет связь с медиа данными + при необходимости удаляет сами медиа данные
     *
     * @param string $sTargetType
     * @param int $sTargetId
     * @param bool $bMediaRemove Удалять медиа данные оставшиеся без связей
     */
    public function RemoveTarget($sTargetType, $sTargetId, $bMediaRemove = true)
    {
        /**
         * Получаем прикрепленные медиа
         */
        $aMediaItems = $this->GetMediaByTarget($sTargetType, $sTargetId);
        /**
         * Удаляем все связи текущего таргета
         */
        $this->RemoveTargetByTypeAndId($sTargetType, $sTargetId);
        if ($bMediaRemove and $aMediaItems) {
            /**
             * Проверяем с какими медиа данными еще остались связи
             */
            $aMediaIds = array();
            foreach ($aMediaItems as $oMediaItem) {
                $aMediaIds[] = $oMediaItem->getId();
            }
            $aTargetItems = $this->GetTargetItemsByFilter(array(
                'media_id in'  => $aMediaIds,
                '#index-group' => 'media_id'
            ));
            /**
             * Удаляем медиа данные без оставшихся связей
             */
            foreach ($aMediaItems as $oMediaItem) {
                if (!isset($aTargetItems[$oMediaItem->getId()])) {
                    $oMediaItem->Delete();
                }
            }
        }
    }

    public function RemoveTargetByTypeAndId($sTargetType, $iTargetId)
    {
        $bRes = $this->oMapper->RemoveTargetByTypeAndId($sTargetType, $iTargetId);
        /**
         * Сбрасываем кеши
         */
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
            'ModuleMedia_EntityTarget_delete'
        ));
        return $bRes;
    }

    public function GetFileWebPath($oMedia, $sSize = null)
    {
        if ($oMedia->getType() == self::MEDIA_TYPE_IMAGE) {
            /**
             * Проверяем необходимость автоматического создания превью нужного размера - если разрешено настройками и файл НЕ существует
             */
            if ($this->GetConfigParam('image.autoresize',
                    $oMedia->getTargetType()) and !$this->Image_IsExistsFile($this->GetImagePathBySize($oMedia->getFilePath(),
                    $sSize))
            ) {
                /**
                 * Запускаем генерацию изображения нужного размера
                 */
                $aSize = $this->ParsedImageSize($sSize);

                $aParams = $this->Image_BuildParams('media.' . $oMedia->getTargetType());
                $sNewFileName = $this->GetImagePathBySize($oMedia->getFilePath(), $sSize);
                if ($oImage = $this->Image_OpenFrom($oMedia->getFilePath(), $aParams)) {
                    if ($aSize['crop']) {
                        $oImage->cropProportion($aSize['w'] / $aSize['h'], 'center');
                    }
                    $oImage->resize($aSize['w'], $aSize['h'], true)->save($sNewFileName);
                    /**
                     * Обновляем список размеров
                     */
                    $aSizeOld = (array)$oMedia->getDataOne('image_sizes');
                    $aSizeOld[] = $aSize;
                    $oMedia->setDataOne('image_sizes', $aSizeOld);
                    $oMedia->Update();
                }
            }
            return $this->GetImageWebPath($oMedia->getFilePath(), $sSize);
        }
        return null;
    }

    /**
     * Возвращает веб путь до файла изображения
     *
     * @param $sPath
     * @param null $sSize
     * @return string
     */
    public function GetImageWebPath($sPath, $sSize = null)
    {
        $sPath = $this->Fs_GetPathWeb($sPath);
        if ($sSize) {
            return $this->GetImagePathBySize($sPath, $sSize);
        } else {
            return $sPath;
        }
    }

    /**
     * Возвращает путь до изображения конкретного размера
     * Варианты преобразования размеров в имя файла:
     *    100 - file_100x100
     *    100crop - file_100x100crop
     *    100x150 - file_100x150
     *  100x150crop - file_100x150crop
     *  x150 - file_x150
     *  100x - file_100x
     *
     * @param string $sPath
     * @param string $sSize
     *
     * @return string
     */
    public function GetImagePathBySize($sPath, $sSize)
    {
        $aPathInfo = pathinfo($sPath);
        if (is_array($sSize)) {
            $aSize = $sSize;
            $sSize = $aSize['w'] . 'x' . $aSize['h'];
            if ($aSize['crop']) {
                $sSize .= 'crop';
            }
        } else {
            if (preg_match('#^(\d+)([a-z]{2,10})?$#i', $sSize, $aMatch)) {
                $sSize = $aMatch[1] . 'x' . $aMatch[1];
                if (isset($aMatch[2])) {
                    $sSize .= strtolower($aMatch[2]);
                }
            }
        }
        return $aPathInfo['dirname'] . '/' . $aPathInfo['filename'] . '_' . $sSize . '.' . $aPathInfo['extension'];
    }

    /**
     * Парсит строку с размером изображения
     * Варианты входной строки:
     * 100
     * 100crop
     * 100x150
     * 100x150crop
     * x150
     * 100x
     *
     * @param string $sSize
     *
     * @return array    Массив вида array('w'=>100,'h'=>150,'crop'=>true)
     */
    public function ParsedImageSize($sSize)
    {
        $aSize = array(
            'w'    => null,
            'h'    => null,
            'crop' => false,
        );

        if (preg_match('#^(\d+)?(x)?(\d+)?([a-z]{2,10})?$#Ui', $sSize, $aMatch)) {
            $iW = (isset($aMatch[1]) and $aMatch[1]) ? $aMatch[1] : null;
            $iH = (isset($aMatch[3]) and $aMatch[3]) ? $aMatch[3] : null;
            $bDelim = (isset($aMatch[2]) and $aMatch[2]) ? true : false;
            $sMod = (isset($aMatch[4]) and $aMatch[4]) ? $aMatch[4] : '';

            if (!$bDelim) {
                $iW = $iH;
            }
            $aSize['w'] = $iW;
            $aSize['h'] = $iH;
            if ($sMod) {
                $aSize[$sMod] = true;
            }
        }
        return $aSize;
    }

    /**
     * Производит стандартнуе проверку на определенное действие с конкретным объектом Media
     *
     * @param $sAllowType
     * @param $aParams
     *
     * @return bool
     */
    public function CheckStandartMediaAllow($sAllowType, $aParams)
    {
        if (!$oUser = $aParams['user']) {
            return false;
        }
        $oMedia = isset($aParams['media']) ? $aParams['media'] : null;
        if (!$oMedia) {
            return false;
        }
        if (in_array($sAllowType, array(self::TYPE_CHECK_ALLOW_REMOVE, self::TYPE_CHECK_ALLOW_UPDATE))) {
            if ($oMedia->getUserId() == $oUser->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Создает превью у файла для определенного типа
     *
     * @param $oMedia
     * @param $oTarget
     *
     * @return bool|string
     */
    public function CreateFilePreview($oMedia, $oTarget)
    {
        if (!$this->GetTargetTypeParam($oTarget->getTargetType(), 'allow_preview')) {
            return false;
        }

        /**
         * Нужно удалить прошлое превью (если оно есть)
         */
        $this->RemoveFilePreview($oMedia, $oTarget);

        if ($oMedia->getType() == self::MEDIA_TYPE_IMAGE) {
            $aParams = $this->Image_BuildParams('media.preview_' . $oTarget->getTargetType());

            if (!$oImage = $this->Image_OpenFrom($oMedia->getFilePath(), $aParams)) {
                return $this->Image_GetLastError();
            }
            /**
             * Сохраняем во временный файл
             */
            if (!$sFileTmp = $oImage->saveTmp()) {
                return $this->Image_GetLastError();
            }
            unset($oImage);
            /**
             * Получаем список необходимых размеров превью
             */
            $aSizes = $this->GetConfigParam('image.preview.sizes', $oTarget->getTargetType());
            /**
             * Каталог для сохранения превью
             */
            $sPath = $this->GetSaveDir($oTarget->getTargetType(), $oTarget->getTargetId(), 'preview');
            /**
             * Уникальное имя файла
             */
            $sFileName = func_generator(20);
            /**
             * Генерируем варианты с необходимыми размерами
             */
            $sFileLast = $this->GenerateImageBySizes($sFileTmp, $sPath, $sFileName, $aSizes, $aParams);
            $aSizeLast = end($aSizes);
            $sReplaceSize = '_' . $aSizeLast['w'] . 'x' . $aSizeLast['h'];
            if ($aSizeLast['crop']) {
                $sReplaceSize .= 'crop';
            }
            $sFileLast = str_replace($sReplaceSize, '', $sFileLast);
            /**
             * Теперь можно удалить временный файл
             */
            $this->Fs_RemoveFileLocal($sFileTmp);
            /**
             * Сохраняем данные во связи
             */
            $oTarget->setDataOne('image_preview_sizes', $aSizes);
            $oTarget->setDataOne('image_preview', $sFileLast);
            $oTarget->setIsPreview(1);
            $oTarget->Update();

            /**
             * Уведомляем объект о создании нового превью
             */
            if ($oTarget->getTargetId()) {
                $this->NotifyCreatePreviewTarget($oTarget->getTargetType(), $oTarget->getTargetId(), $oTarget);
            }

            return true;
        }
    }

    public function RemoveFilePreview($oMedia, $oTarget)
    {
        if ($oMedia->getType() == self::MEDIA_TYPE_IMAGE) {
            if ($oTarget->getDataOne('image_preview')) {
                /**
                 * Уведомляем объект о удалении превью
                 */
                if ($oTarget->getTargetId()) {
                    $this->NotifyRemovePreviewTarget($oTarget->getTargetType(), $oTarget->getTargetId(), $oTarget);
                }
                $this->RemoveImageBySizes($oTarget->getDataOne('image_preview'),
                    $oTarget->getDataOne('image_preview_sizes'));
                $oTarget->setDataOne('image_preview', null);
                $oTarget->setDataOne('image_preview_sizes', array());
                $oTarget->setIsPreview(0);
                $oTarget->Update();
                return true;
            }
        }
    }

    /**
     * Удаляет все превью у конкретного объекта
     *
     * @param      $sTargetType
     * @param      $sTargetId
     * @param null $sTargetTmp
     */
    public function RemoveAllPreviewByTarget($sTargetType, $sTargetId, $sTargetTmp = null)
    {
        $aFilter = array(
            'target_type' => $sTargetType,
            'is_preview'  => 1,
            '#with'       => array('media')
        );
        if ($sTargetId) {
            $aFilter['target_id'] = $sTargetId;
        } else {
            $aFilter['target_tmp'] = $sTargetTmp;
        }
        $aTargetItems = $this->Media_GetTargetItemsByFilter($aFilter);
        foreach ($aTargetItems as $oTarget) {
            $this->RemoveFilePreview($oTarget->getMedia(), $oTarget);
        }
    }


    /**
     * Обработка создания превью для типа 'topic'
     * Название метода формируется автоматически
     *
     * @param int $iTargetId
     * @param ModuleMedia_EntityTarget $oRelationTarget
     */
    public function NotifyCreatePreviewTargetTopic($iTargetId, $oRelationTarget)
    {
        if ($oTopic = $this->Topic_GetTopicById($iTargetId)) {
            $oTopic->setPreviewImage($oRelationTarget->getDataOne('image_preview'));
            $this->Topic_UpdateTopic($oTopic);
        }
    }

    /**
     * Обработка удаления превью для типа 'topic'
     * Название метода формируется автоматически
     *
     * @param int $iTargetId
     * @param ModuleMedia_EntityTarget $oRelationTarget
     */
    public function NotifyRemovePreviewTargetTopic($iTargetId, $oRelationTarget)
    {
        if ($oTopic = $this->Topic_GetTopicById($iTargetId)) {
            $oTopic->setPreviewImage(null);
            $this->Topic_UpdateTopic($oTopic);
        }
    }

    /**
     * Проверка владельца с типом "topic"
     * Название метода формируется автоматически
     *
     * @param int|null $iTargetId ID владельца, для новых объектов может быть не определен
     * @param string $sAllowType Тип доступа, константа self::TYPE_CHECK_ALLOW_*
     * @param array $aParams Дополнительные параметры, всегда есть ключ 'user'
     *
     * @return bool
     */
    public function CheckTargetTopic($iTargetId, $sAllowType, $aParams)
    {
        if (!$oUser = $aParams['user']) {
            return false;
        }
        if (in_array($sAllowType,
            array(self::TYPE_CHECK_ALLOW_ADD, self::TYPE_CHECK_ALLOW_PREVIEW, self::TYPE_CHECK_ALLOW_VIEW_LIST))) {
            if (is_null($iTargetId)) {
                /**
                 * Разрешаем для всех новых топиков
                 */
                return true;
            }
            if ($oTopic = $this->Topic_GetTopicById($iTargetId)) {
                /**
                 * Проверяем права на редактирование топика
                 */
                if ($this->ACL_IsAllowEditTopic($oTopic, $oUser)) {
                    /**
                     * Дополнительно возможность исползования превью (настраивается отдельно для каждого типа топика)
                     */
                    if ($sAllowType == self::TYPE_CHECK_ALLOW_PREVIEW) {
                        if ($oTopicType = $this->Topic_GetTopicType($oTopic->getType())) {
                            if (!$oTopicType->getParam('allow_preview')) {
                                return false;
                            }
                        }
                    }
                    return true;
                }
            }
        } else {
            return $this->CheckStandartMediaAllow($sAllowType, $aParams);
        }
        return false;
    }

    /**
     * Проверка владельца с типом "comment"
     * Название метода формируется автоматически
     *
     * @param int|null $iTargetId ID владельца, для новых объектов может быть не определен
     * @param string $sAllowType Тип доступа, константа self::TYPE_CHECK_ALLOW_*
     * @param array $aParams Дополнительные параметры, всегда есть ключ 'user'
     *
     * @return bool
     */
    public function CheckTargetComment($iTargetId, $sAllowType, $aParams)
    {
        if (!$oUser = $aParams['user']) {
            return false;
        }
        if (in_array($sAllowType, array(self::TYPE_CHECK_ALLOW_ADD, self::TYPE_CHECK_ALLOW_VIEW_LIST))) {
            if (is_null($iTargetId)) {
                /**
                 * Разрешаем для всех новых комментариев
                 */
                return true;
            }
            if ($oComment = $this->Comment_GetCommentById($iTargetId)) {
                /**
                 * Проверяем права на редактирование комментария
                 */
                if ($this->ACL_IsAllowEditComment($oComment, $oUser)) {
                    return true;
                }
            }
        } else {
            return $this->CheckStandartMediaAllow($sAllowType, $aParams);
        }
        return false;
    }

    /**
     * Проверка владельца с типом "blog"
     * Название метода формируется автоматически
     *
     * @param int|null $iTargetId ID владельца, для новых объектов может быть не определен
     * @param string $sAllowType Тип доступа, константа self::TYPE_CHECK_ALLOW_*
     * @param array $aParams Дополнительные параметры, всегда есть ключ 'user'
     *
     * @return bool
     */
    public function CheckTargetBlog($iTargetId, $sAllowType, $aParams)
    {
        if (!$oUser = $aParams['user']) {
            return false;
        }
        if (in_array($sAllowType, array(self::TYPE_CHECK_ALLOW_ADD, self::TYPE_CHECK_ALLOW_VIEW_LIST))) {
            if (is_null($iTargetId)) {
                /**
                 * Разрешаем для всех новых блогов
                 */
                return true;
            }
            if ($oBlog = $this->Blog_GetBlogById($iTargetId)) {
                /**
                 * Проверяем права на редактирование блога
                 */
                if ($this->ACL_IsAllowEditBlog($oBlog, $oUser)) {
                    return true;
                }
            }
        } else {
            return $this->CheckStandartMediaAllow($sAllowType, $aParams);
        }
        return false;
    }

    /**
     * Проверка владельца с типом "talk"
     * Название метода формируется автоматически
     *
     * @param int|null $iTargetId ID владельца, для новых объектов может быть не определен
     * @param string $sAllowType Тип доступа, константа self::TYPE_CHECK_ALLOW_*
     * @param array $aParams Дополнительные параметры, всегда есть ключ 'user'
     *
     * @return bool
     */
    public function CheckTargetTalk($iTargetId, $sAllowType, $aParams)
    {
        if (!$oUser = $aParams['user']) {
            return false;
        }
        if (in_array($sAllowType, array(self::TYPE_CHECK_ALLOW_ADD, self::TYPE_CHECK_ALLOW_VIEW_LIST))) {
            if (is_null($iTargetId)) {
                /**
                 * Разрешаем для всех новых сообщений
                 */
                return true;
            }
            /**
             * Редактировать сообщения нельзя
             */
            return false;
        } else {
            return $this->CheckStandartMediaAllow($sAllowType, $aParams);
        }
        return false;
    }
}
