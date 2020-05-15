<div class="creaturelistcontainer">
	<div class="creaturelist">
	<?php
	foreach($creatures as $creature):
		?>
		<div class="creaturecontainer">
			<div class="creatureicon">
				<img src="/images/NH-Icon-<?=$creature->sanitisedname?>.png"/>
			</div>
		</div>
		<?php
	endforeach;
	?>
	</div>
</div>