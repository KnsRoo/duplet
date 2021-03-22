<?php foreach ($sliders as $key => $value) : ?>
	<div id = "<?=$value->id?>" 
		 data-id = "<?=$key?>"
		 data-link = "<?=$value->link?>" 
		 data-title = "<?=$value->title?>" 
		 data-description = "<?= $value->description?>">
	</div>
<?php endforeach ?>