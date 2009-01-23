			
			<div class="block stat nostyle">
				<h1>Статистика</h1>
				
				<div class="gender">
					<ul id="chart_users_data">
						<li>Всего пользователей: {$aStat.count_all}</li>
						<li><div class="mark" style="background: #70aae0;"></div>Активные: <span>{$aStat.count_active}</span></li>
						<li class="last"><div class="mark" style="background: #ff68cf;"></div>Заблудившиеся: <span>{$aStat.count_inactive}</span></li>
					</ul>
					<div class="chart">						
						<div id="chart_users_area"></div>	
						{literal}
						<script>
							window.addEvent('domready', function(){
								new PieChart($('chart_users_data'),$('chart_users_area'),{index:1});
							});
						</script>
						{/literal}					
					</div>
				</div>
				
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
								new PieChart($('chart_gender_data'),$('chart_gender_area'),{index:2});
							});
						</script>
						{/literal}					
					</div>
				</div>
			</div>
			
			{insert name="block" block='tagsCountry'}
			
			{insert name="block" block='tagsCity'}