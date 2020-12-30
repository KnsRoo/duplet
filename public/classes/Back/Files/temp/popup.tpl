<div class="options-popup absolute hidden">
	<div class="popup-titl relative">Дополнительные опции<label for="dop-options" class="icon absolute" title="Скрыть дополнительные опции"></label></div>
	<div class="popup-cont">
		<div class="count">
			<p>Отображать по </p>
			<select class="select-count" name="right-items-count">
				<option value="20" selected>20</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="all">Все</option>
			</select>
			<p> на одной странице</p>
			<input type="submit" class="goodBtn" value="Применить" />
		</div>
		<div class="calend">
			<p>Отображать файлы загруженные в определенное время:</p>
			<span>День:</span><input type="text" class="text-text" name="sort-day" value="" placeholder="1 - 31" />
			<span>Месяц:</span><input type="text" class="text-text" name="sort-month" value="" placeholder="1 - 12" />
			<span>Год:</span><input type="text" class="text-text" name="sort-year" value="" placeholder="0 - 9999" />
			<input type="submit" class="goodBtn" value="Применить" />
		</div>
		<span onclick="layout.checkAll();">Отметить все файлы</span><span>&ensp;/&ensp;</span><span onclick="layout.uncheckAll();">Снять отметки со всех файлов</span>

		<?php if ($this->permitions['editing-files'] == 'on'): ?>
		<p>Дейсвия с отмеченными файломи:</p>
		<input type="submit" class="goodBtn" name="delete_checked" value="Удалить" />
		<input type="submit" class="goodBtn" name="unvisibility_checked" value="Скрыть" />
		<input type="submit" class="goodBtn" name="visibility_checked" value="Показать" />
		<input type="submit" class="goodBtn" name="download_checked" value="Скачать" />
		<input type="submit" class="goodBtn" name="crypt_checked" value="Зашифровать" />
		<?php endif; ?>
	</div>
</div>
