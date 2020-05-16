var checkboxes = document.getElementsByClassName('creaturefoundcheckbox');
var creatures = document.getElementsByClassName('creaturecontainer');

let discovered = localStorage.getItem('discovered');

var getfound = function(){
	var found = [];
	if(discovered !== null){
		found = discovered.split(',');
	}
	return found;
}

var displayfound = function(){
	var found = getfound();
	Array.from(creatures).forEach(function(creature){
		var creatureid = creature.getAttribute('data-id');
		if(found.indexOf(creatureid) !== -1){
			creature.classList.add('discovered');
			console.log('found' + creatureid);
			var checkbox = document.getElementById('found' + creatureid);
			if(checkbox !== null){
				checkbox.checked = true;
			}
		}
	});
}

var updatefound = function(){
	var creatureid = this.getAttribute('data-id');
	var found = getfound();
	var creatureicon = document.getElementById('creature' + creatureid);
	if(this.checked){
		found.push(creatureid);
		if(creatureicon !== null){
			creatureicon.classList.add('discovered');
		}
	}else{
		found.splice(found.indexOf(creatureid), 1);
		if(creatureicon !== null){
			creatureicon.classList.remove('discovered');
		}
	}
	found = found.join(',');
	discovered = found;
	localStorage.setItem('discovered', discovered);
}

Array.from(checkboxes).forEach(function(checkbox){
	checkbox.addEventListener('click', updatefound);
});

displayfound();