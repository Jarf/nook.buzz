<div class="creaturelistcontainer">
	<div class="creaturelist">
	<?php
	foreach($creatures as $creature):
		?>
		<div class="creaturecontainer">
			<label for="<?=$creature->sanitisedname?>">
				<div class="creatureicon">
					<img src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
				</div>
			</label>
			<input type="radio" id="<?=$creature->sanitisedname?>" name="creatureselect" class="creaturecheckbox"/>
			<div class="creatureinfocontainer">
				<label class="closebutton" for="nocreatureselected">X</label>
				<div class="creatureicon">
					<img src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
					<span class="creaturetitle">
						<?=$creature->name?>
					</span>
				</div>
				<ul class="creatureinfo">
					<li>Value: <?=$creature->sell?></li>
					<?php if(!empty($creature->size)): ?>
					<li>Size: <?=$creature->sizereadable?></li>
					<?php endif; ?>
					<li>Location: <?=ucfirst($creature->location)?></li>
					<li>Time: <?=$creature->timereadable?></li>
				</ul>
			</div>
		</div>
		<?php
	endforeach;
	?>
	<input type="radio" id="nocreatureselected" name="creatureselect" class="creaturecheckbox"/>
	</div>
</div>