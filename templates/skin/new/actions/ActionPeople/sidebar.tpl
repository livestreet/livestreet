			
			<div class="block stat nostyle">
				<h1>Статистика</h1>
				<ul class="users">
					<li>Всего пользователей: {$aStat.count_all}</li>
					<li>Активные: {$aStat.count_active}</li>
					<li class="last">Заблудившиеся: {$aStat.count_inactive}</li>
				</ul>

				<div class="gender">
					<ul>
						<li><div class="mark" style="background: #70aae0;"></div>Мужчины: {$aStat.count_sex_man}</li>
						<li><div class="mark" style="background: #ff68cf;"></div>Женщины: {$aStat.count_sex_woman}</li>
						<li class="last"><div class="mark" style="background: #fff;"></div>Пол не указан: {$aStat.count_sex_other}</li>
					</ul>
					<div class="chart">
						<img src="{$DIR_STATIC_SKIN}/images/chart.gif" alt="" />
					</div>
				</div>
			</div>
			
			{insert name="block" block='tagsCountry'}
			
			{insert name="block" block='tagsCity'}