'use strict';

(() => {
	var captions = document.querySelectorAll('.caption p');
	captions.forEach(el=>{
		if(el.innerText.length < 50) return;

		var text = el.innerText.substring(0, 40) + '...';

		el.innerText = text;

		console.log(text);
	});
})();