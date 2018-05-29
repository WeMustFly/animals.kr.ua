'use strict';

(() => {
	var captions = document.querySelectorAll('.caption p');
	captions.forEach(el=>{
		if(el.innerText.length < 50) return;
		var text = el.innerText.substring(0, 40) + '...';
		el.innerText = text;
	});

	var menu = document.getElementById('mobileMenu');
	menu.onclick = (e) => {
		menu.classList.toggle('active');
	}
})();