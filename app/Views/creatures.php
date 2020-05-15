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
		</div>
		<input type="radio" id="<?=$creature->sanitisedname?>" name="creatureselect" class="creaturecheckbox"/>
		<div class="creatureinfocontainer">
			<label class="closebutton" for="nocreatureselected">X</label>
			<?=$creature->name?><br/>
			<div class="creatureicon">
				<img src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
			</div>
			<?=$creature->sell?><br/>
			<?=$creature->size?><br/>
			<?=$creature->location?><br/>
			<?=$creature->timereadable?>
		</div>
		<?php
	endforeach;
	?>
	<input type="radio" id="nocreatureselected" name="creatureselect" class="creaturecheckbox"/>
	</div>
</div>