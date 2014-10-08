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
 * Объект управления типом video link
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeVideoLink extends ModuleProperty_EntityValueType
{

    const VIDEO_PROVIDER_YOUTUBE = 'youtube';
    const VIDEO_PROVIDER_VIMEO = 'vimeo';
    const VIDEO_PROVIDER_RUTUBE = 'rutube';

    public function getValueForDisplay()
    {
        return $this->getVideoCodeFrame();
    }

    public function validate()
    {
        $mRes = $this->validateStandart('url', array('defaultScheme' => 'http'));
        if ($mRes === true) {
            /**
             * Теперь проверяем на принадлежность к разным видео-хостингам
             */
            if ($this->getValueForValidate() and !$this->checkVideo($this->getValueForValidate())) {
                return 'Необходимо указать корректную ссылку на видео: YouTube, Vimeo';
            }
            return true;
        } else {
            return $mRes;
        }
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;
        return $aRules;
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oValue->setValueVarchar($mValue ? $mValue : null);
        /**
         * Получаем и сохраняем ссылку на превью
         */
        $this->retrievePreview($oValue);
    }

    protected function retrievePreview($oValue)
    {
        $sLink = $oValue->getValueVarchar();
        $sProvider = $this->getVideoProvider($sLink);
        $sId = $this->getVideoId($sLink);
        if ($sProvider == self::VIDEO_PROVIDER_YOUTUBE) {
            $oValue->setDataOne('preview_small', "http://img.youtube.com/vi/{$sId}/default.jpg");
            $oValue->setDataOne('preview_normal', "http://img.youtube.com/vi/{$sId}/0.jpg");
        } elseif ($sProvider == self::VIDEO_PROVIDER_VIMEO) {
            $aRetrieveData = @json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$sId}.json"), true);
            if (isset($aRetrieveData[0]['thumbnail_medium'])) {
                $oValue->setDataOne('preview_small', $aRetrieveData[0]['thumbnail_medium']);
                $oValue->setDataOne('preview_normal', $aRetrieveData[0]['thumbnail_large']);
            }
        } elseif ($sProvider == self::VIDEO_PROVIDER_RUTUBE) {
            $aRetrieveData = @json_decode(file_get_contents("http://rutube.ru/api/video/{$sId}/?format=json"), true);
            if (isset($aRetrieveData['thumbnail_url'])) {
                $oValue->setDataOne('preview_small', $aRetrieveData['thumbnail_url'] . '?size=s');
                $oValue->setDataOne('preview_normal', $aRetrieveData['thumbnail_url']);
            }
        }
    }

    public function checkVideo($sLink)
    {
        return $this->getVideoId($sLink) ? true : false;
    }

    public function getVideoId($sLink = null)
    {
        if (is_null($sLink)) {
            $sLink = $this->getValueObject()->getValueVarchar();
        }
        $sProvider = $this->getVideoProvider($sLink);
        /**
         * youtube
         * http://www.youtube.com/watch?v=LZaCb5Y9SyM
         * http://youtu.be/LZaCb5Y9SyM
         */
        if ($sProvider == self::VIDEO_PROVIDER_YOUTUBE) {
            if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $sLink,
                $aMatch)) {
                return $aMatch[0];
            }
        }
        /**
         * vimeo
         * http://vimeo.com/72359144
         */
        if ($sProvider == self::VIDEO_PROVIDER_VIMEO) {
            return substr(parse_url($sLink, PHP_URL_PATH), 1);
        }
        /**
         * rutube
         * http://rutube.ru/video/ee523c9164c8f9fc8b267c66a0a3adae/
         * http://rutube.ru/video/6fd81c1c212c002673280850a1c56415/#.UMQYln9yTWQ
         * http://rutube.ru/tracks/6032725.html
         * http://rutube.ru/video/embed/6032725
         */
        if ($sProvider == self::VIDEO_PROVIDER_RUTUBE) {
            if (preg_match('/(?:http|https)+:\/\/(?:www\.|)rutube\.ru\/video\/embed\/([a-zA-Z0-9_\-]+)/i', $sLink,
                    $aMatch) || preg_match('/(?:http|https)+:\/\/(?:www\.|)rutube\.ru\/(?:tracks|video)\/([a-zA-Z0-9_\-]+)(&.+)?/i',
                    $sLink, $aMatch)
            ) {
                return $aMatch[1];
            }
        }
        return null;
    }

    public function getVideoProvider($sLink)
    {
        if (preg_match("#(youtube\.)|(youtu\.be)#i", $sLink)) {
            return self::VIDEO_PROVIDER_YOUTUBE;
        }
        if (preg_match("#(vimeo\.)#i", $sLink)) {
            return self::VIDEO_PROVIDER_VIMEO;
        }
        if (preg_match("#(rutube\.ru)#i", $sLink)) {
            return self::VIDEO_PROVIDER_RUTUBE;
        }
        return null;
    }

    public function getVideoCodeFrame()
    {
        $sLink = $this->getValueObject()->getValueVarchar();
        $sProvider = $this->getVideoProvider($sLink);
        $sId = $this->getVideoId($sLink);
        if ($sProvider == self::VIDEO_PROVIDER_YOUTUBE) {
            return '
				<iframe style="max-width: 100%;width: 100%;height: 495px;" src="//www.youtube.com/embed/' . $sId . '" frameborder="0" allowfullscreen></iframe>
			';
        } elseif ($sProvider == self::VIDEO_PROVIDER_VIMEO) {
            return '
				<iframe src="http://player.vimeo.com/video/' . $sId . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=e6ae9e" style="max-width: 100%;width: 100%;height: 495px;" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			';
        } elseif ($sProvider == self::VIDEO_PROVIDER_RUTUBE) {
            return '
				<iframe src="http://rutube.ru/video/embed/' . $sId . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen style="max-width: 100%;width: 100%;height: 495px;"></iframe>
			';
        }
        return '';
    }

    public function getPreview($sType = 'small')
    {
        $oValue = $this->getValueObject();
        return $oValue->getDataOne("preview_{$sType}");
    }

    public function getCountView()
    {
        $oValue = $this->getValueObject();
        $sLink = $oValue->getValueVarchar();
        $sProvider = $this->getVideoProvider($sLink);
        $sId = $this->getVideoId($sLink);
        if ($sProvider == self::VIDEO_PROVIDER_YOUTUBE) {
            $iCount = (int)$oValue->getDataOne("count_view");
            $iCountViewLastTime = (int)$oValue->getDataOne("count_view_last_time");
            if (time() - $iCountViewLastTime > 60 * 60 * 1) {
                $aData = @json_decode(file_get_contents("https://gdata.youtube.com/feeds/api/videos/{$sId}?v=2&alt=json"),
                    true);
                if (isset($aData['entry']['yt$statistics']['viewCount'])) {
                    $iCount = $aData['entry']['yt$statistics']['viewCount'];
                }
                $oValue->setDataOne("count_view", $iCount);
                $oValue->setDataOne("count_view_last_time", time());
                $oValue->Update();
            }
            return $iCount;
        } elseif ($sProvider == self::VIDEO_PROVIDER_VIMEO) {

        } elseif ($sProvider == self::VIDEO_PROVIDER_RUTUBE) {

        }
        return null;
    }
}