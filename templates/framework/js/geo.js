var ls = ls || {};

/**
 * Гео-объекты
 */
ls.geo = (function ($) {

	/**
	 * Инициализация селектов выбора гео-объекта
	 */
	this.initSelect = function() {
		$.each($('.js-geo-select'),function(k,v){
			$(v).find('.js-geo-country').bind('change',function(e){
				this.loadRegions($(e.target));
			}.bind(this));
			$(v).find('.js-geo-region').bind('change',function(e){
				this.loadCities($(e.target));
			}.bind(this));
		}.bind(this));
	};

	this.loadRegions = function($country) {
		$region=$country.parents('.js-geo-select').find('.js-geo-region');
		$city=$country.parents('.js-geo-select').find('.js-geo-city');
		$region.empty();
		$region.append('<option value="">'+ls.lang.get('geo_select_region')+'</option>');
		$city.empty();
		$city.hide();

		if (!$country.val()) {
			$region.hide();
			return;
		}

		var url = aRouter['ajax']+'geo/get/regions/';
		var params = {country: $country.val()};
		ls.hook.marker('loadRegionsBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aRegions,function(k,v){
					$region.append('<option value="'+v.id+'">'+v.name+'</option>');
				});
				$region.show();
				ls.hook.run('ls_geo_load_regions_after',[$country, result]);
			}
		});
	};

	this.loadCities = function($region) {
		$city=$region.parents('.js-geo-select').find('.js-geo-city');
		$city.empty();
		$city.append('<option value="">'+ls.lang.get('geo_select_city')+'</option>');

		if (!$region.val()) {
			$city.hide();
			return;
		}

		var url = aRouter['ajax']+'geo/get/cities/';
		var params = {region: $region.val()};
		ls.hook.marker('loadCitiesBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$.each(result.aCities,function(k,v){
					$city.append('<option value="'+v.id+'">'+v.name+'</option>');
				});
				$city.show();
				ls.hook.run('ls_geo_load_cities_after',[$region, result]);
			}
		});
	};



	return this;
}).call(ls.geo || {},jQuery);