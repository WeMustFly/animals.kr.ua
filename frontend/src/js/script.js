'use strict';

(() => {
	let captions = document.querySelectorAll('.caption p');
	captions.forEach(el=>{
		if(el.innerText.length < 13) return;
		let text = el.innerText.substring(0, 40) + '...';
		el.innerText = text;
	});

	let menu = document.getElementById('mobileMenu');
	menu.onclick = (e) => {
		menu.classList.toggle('active');
	}

	let close = document.getElementById('closeModal');
	let open = document.getElementById('openModal');
	let modal = document.getElementById('modal');

	open.onclick = () => {
		modal.style.display = 'flex';
		document.body.scrollTop = document.documentElement.scrollTop = 0;
	}
	close.onclick = () => {
		modal.style.display = 'none';
	}
})();