			
			<div class="block stat nostyle">
				<h1>Статистика</h1>
				<ul class="users">
					<li>Всего пользователей: {$aStat.count_all}</li>
					<li>Активные: {$aStat.count_active}</li>
					<li class="last">Заблудившиеся: {$aStat.count_inactive}</li>
				</ul>

				<div class="gender">
					<ul id="chart_gender_data">
						<li><div class="mark" style="background: #70aae0;"></div>Мужчины: <span>{$aStat.count_sex_man}</span></li>
						<li><div class="mark" style="background: #ff68cf;"></div>Женщины: <span>{$aStat.count_sex_woman}</span></li>
						<li class="last"><div class="mark" style="background: #c5c5c5;"></div>Пол не указан: <span>{$aStat.count_sex_other}</span></li>
					</ul>
					<div class="chart">						
						<div id="chart_gender_area"></div>	
						{literal}
						<script>
							window.addEvent('domready', function(){
								new PieChart($('chart_gender_data'),$('chart_gender_area'));
							});
						</script>
						{/literal}					
					</div>
				</div>
			</div>
			
			{insert name="block" block='tagsCountry'}
			
			{insert name="block" block='tagsCity'}