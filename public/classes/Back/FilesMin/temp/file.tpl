<?php

	use \Back\Files\Config;

	$info = 'Тип файла: '.strtoupper($file->ext)."\r\n";
	$info .= 'MIME-тип: '.$file->type."\r\n";
	$info .= 'Дата загрузки: '.date('d.m.Y',$file->date)."\r\n";
	$info .= 'Пользователь: '.$file->user."\r\n";
	$info .= 'Размер: '.ceil($file->size/1024/1024).' Мb';

	$href = Config::PREFIX_PATH.'/'.$file->id.'.'.$file->ext;

	if ($file->isPicture() || $file->isVPicture())
		$drag = 'ondragstart="FilesMin.dragFile(event, \'image\', \''.$href.'\');"';
	else $drag = 'ondragstart="FilesMin.dragFile(event, \'href\', \''.$href.'\', \''.$file->title.'.'.$file->ext.'\');"';

?>
<input 
	ext="<?= $file->ext; ?>"
	type="<?= $this->options->multiple ? 'checkbox' : 'radio'; ?>"
	class="hidden"
	name="file-<?= $this->uniqid; ?><?= $this->options->multiple ? '[]' : ''; ?>"
	value="<?= $file->id; ?>"
	id="mini-file-<?= $file->id; ?>-<?= $this->uniqid; ?>"
	<?php

	$onchange = '';

	if ($this->options->setValue) {
		$onchange .= 'FilesMin.setValue(\''.$this->options->setValue.'\', this.value);';
	}

	$onchange .= $this->options->onchange;

	?>
	onchange="<?= $onchange; ?>" 
/>
<label 
	<?= $this->options->checkable ? 'for="mini-file-'.$file->id.'-'.$this->uniqid.'"' : ''; ?>
	class="file-block" title="<?= $info; ?>"
	draggable="<?= $this->options->draggable ? 'true' : 'false'; ?>" <?= $drag; ?> >
	<?php
		if ($file->isPicture()) {

			$picture = $file->getSmallPicture();
			$picture = $picture ? Config::PREFIX_PATH.'/'.$picture : 'icons/'.$file->getIcon();
			echo ' <div class="file-block-icon" style="background-image: url('.$picture.');"></div> ';

		} elseif ($file->isVPicture()) {

			$picture = Config::PREFIX_PATH.'/'.$file->getName() ;
			echo ' <div class="file-block-icon" style="background-image: url('.$picture.');"></div> ';

		} elseif ($file->isVideo()) {

			$preview = $file->getVideoPreview('50x50');
			$preview = Config::PREFIX_PATH.'/'.$preview;

			echo ' <div class="file-block-icon" style="background-image: url('.$preview.');"></div> ';

		}
		else echo ' <div class="file-block-icon" style="background-image: url(icons/'.$file->getIcon().');"></div> ';
	?>
	<div class="file-block-title" title="<?= $file->title; ?>"><?= $file->title; ?></div>
</label>
