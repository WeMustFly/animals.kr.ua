"use strict";document.querySelectorAll(".caption p").forEach(function(e){if(!(e.innerText.length<50)){var n=e.innerText.substring(0,40)+"...";e.innerText=n,console.log(n)}});