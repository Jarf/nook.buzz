<div class="creaturelistcontainer">
	<div class="month">
		<a class="monthselect previousmonth" href="/<?=$type?>/<?=$monthprev?>"><</a>
		<?=$month?>
		<a class="monthselect nextmonth" href="/<?=$type?>/<?=$monthnext?>">></a>
	</div>
	<div class="creaturelist">
	<?php
	foreach($creatures as $creature):
		?>
		<div class="creaturecontainer" id="creature<?=$creature->id?>" data-id="<?=$creature->id?>">
			<label for="<?=$creature->sanitisedname?>">
				<div class="creatureicon">
					<img height="128" width="128" alt="<?=$creature->name?>" src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
				</div>
			</label>
			<input type="radio" id="<?=$creature->sanitisedname?>" name="creatureselect" class="creaturecheckbox"/>
			<div class="creatureinfocontainer">
				<label class="closebutton" for="nocreatureselected">X</label>
				<div class="creatureicon">
					<img height="128" width="128" alt="<?=$creature->name?>" src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
					<span class="creaturetitle">
						<?=$creature->name?>
					</span>
				</div>
				<ul class="creatureinfo">
					<li>Value: <?=$creature->sell?></li>
					<?php if($creature->size !== NULL): ?>
					<li>Size: <?=$creature->sizereadable?></li>
					<?php endif; ?>
					<li>Location: <?=ucfirst($creature->location)?></li>
					<li>Time: <?=$creature->timereadable?></li>
				</ul>
				<input type="checkbox" data-id="<?=$creature->id?>" id="found<?=$creature->id?>" class="creaturefoundcheckbox"/>
				<label for="found<?=$creature->id?>" class="creaturefoundbutton">
					Donated: <span class="yes">Yes</span><span class="no">No</span>
				</label>
			</div>
		</div>
		<?php
	endforeach;
	?>
	<input type="radio" id="nocreatureselected" name="creatureselect" class="creaturecheckbox"/>
	</div>
</div>

<script type="text/javascript" src="/js/creatures.js"></script>