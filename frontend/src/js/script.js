'use strict';

(() => {
	let captions = document.querySelectorAll('.caption p');
	captions.forEach(el=>{
		if(el.innerText.length < 13) return;
		let text = el.innerText.substring(0, 13) + '...';
		el.innerText = text;
	});

	let menu = document.getElementById('mobileMenu');
	menu.onclick = (e) => {
		menu.classList.toggle('active');
	}

	let close = document.getElementById('closeModal');
	let open = document.getElementById('openModal');
	let modal = document.getElementById('modal');
	let content = document.getElementById('content');
	open.onclick = () => {
		modal.style.display = 'flex';
		content.style.display = 'none';
		document.body.scrollTop = document.documentElement.scrollTop = 0;
	}
	close.onclick = () => {
		modal.style.display = 'none';
		content.style.display = 'block';
	}
})();