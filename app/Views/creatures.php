<div class="creaturelistcontainer">
	<div class="creaturelist">
	<?php
	foreach($creatures as $creature):
		$sanitisedname = strtolower($creature->name);
		$sanitisedname = preg_replace('/[^a-z]/', '', $sanitisedname);
		?>
		<div class="creaturecontainer">
			<div class="creatureicon">
				<img src="/images/NH-Icon-<?=$sanitisedname?>.png"/>
			</div>
			<div class="creatureinfo">
				<div class="icons">
					<div class="row">
						<img src="/images/icon-<?=preg_replace('/[^a-z]/', '', $creature->location);?>.png"/>
						<?php if($creature->fin === '1'): ?>
							<img src="/images/icon-fin.png"/>
						<?php endif; ?>
					</div>
					<div class="row">
						<img src="/images/icon-clock.png"/>
						<?php
						if($creature->time_start === '0' && $creature->time_end === '23'){
							print "All Day";
						}else{
							print $creature->time_start . ' ' . $creature->time_end;
						}
						?>
					</div>
					<div class="row">
						<img src="/images/icon-money.png"/>
						<?=$creature->sell?>
					</div>
				</div>
			</div>
		</div>
		<?php
	endforeach;
	?>
	</div>
</div>