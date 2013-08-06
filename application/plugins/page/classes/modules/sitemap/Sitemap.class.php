<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Module for plugin Sitemap
 */
class PluginPage_ModuleSitemap extends PluginPage_Inherit_PluginSitemap_ModuleSitemap {

	/**
	 * Change data for Sitemap Index
	 *
	 * @return array
	 */
	public function getExternalCounters() {
		$aCounters = parent::getExternalCounters();
		$aCounters['pages'] = ceil($this->PluginPage_Page_GetCountOfActivePages() / Config::Get('plugin.sitemap.objects_per_page'));

		return $aCounters;
	}

	/**
	 * Get data for static pages Sitemap
	 *
	 * @param integer $iCurrPage
	 * @return array
	 */
	public function getDataForPages($iCurrPage) {
		$iPerPage = Config::Get('plugin.sitemap.objects_per_page');
		$sCacheKey = "sitemap_pages_{$iCurrPage}_" . $iPerPage;

		if (false === ($aData = $this->Cache_Get($sCacheKey))) {
			$iCount = 0;
			$aPages = $this->PluginPage_Page_GetListOfActivePages($iCount, $iCurrPage, $iPerPage);

			$aData = array();
			foreach ($aPages as $oPage) {
				$aData[] = $this->PluginSitemap_Sitemap_GetDataForSitemapRow(
					Router::GetPath('page') . $oPage->getUrlFull(),
					$oPage->getDateEdit(),
					Config::Get('plugin.page.sitemap.sitemap_priority'),
					Config::Get('plugin.page.sitemap.sitemap_changefreq')
				);
			}

			$this->Cache_Set($aData, $sCacheKey, array('page_change'), Config::Get('plugin.page.sitemap.cache_lifetime'));
		}

		return $aData;
	}
}
