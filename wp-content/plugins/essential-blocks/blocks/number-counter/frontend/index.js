!function(){"use strict";window.addEventListener("DOMContentLoaded",(function(){var t=document.querySelectorAll(".eb-counter-wrapper  .eb-counter");function e(t,e,n){return"true"===e?t.toString().replace(/\B(?=(\d{3})+(?!\d))/g,n):t.toString()}t&&t.forEach((function(t){var n=!1,r=+t.getAttribute("data-target"),i=+t.getAttribute("data-duration"),o=+t.getAttribute("data-startValue"),a=t.getAttribute("data-isShowSeparator"),u=t.getAttribute("data-separator"),c=o<r?o:0,d=(r-c)/i*53,g=t.getBoundingClientRect(),l=g.height/2,f=g.top;function p(){c+=d,t.innerText=e(Math.floor(c),a,u),c<r?setTimeout((function(){p()}),53):t.innerText=e(r,a,u)}!n&&f+l<innerHeight&&(n=!0,p()),window.addEventListener("scroll",function(t){var e,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:10,r=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];return function(){var i=this,o=arguments;function a(){e=null,r||t.apply(i,o)}var u=r&&!e;clearTimeout(e),e=setTimeout(a,n),u&&t.apply(i,o)}}((function(){g=t.getBoundingClientRect(),f=g.top,!n&&f+l<innerHeight&&(n=!0,p())})))}))}))}();