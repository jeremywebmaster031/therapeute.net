(()=>{"use strict";const t=window.wp.domReady;class Lightbox{constructor(t){void 0!==t&&(this.lightboxInitialized=!1,this.wrapperDiv=t,this.instanceId=this.wrapperDiv.dataset.id,this.lightboxElement=document.querySelector(`.ws-lightbox-${this.instanceId} .web-stories-list__lightbox`),this.player=this.lightboxElement.querySelector("amp-story-player"),void 0!==this.player&&void 0!==this.lightboxElement&&(this.player.isReady&&!this.lightboxInitialized&&this.initializeLightbox(),this.player.addEventListener("ready",(()=>{this.lightboxInitialized||this.initializeLightbox()})),this.player.addEventListener("amp-story-player-close",(()=>{this.player.rewind(),this.player.pause(),this.player.mute(),this.lightboxElement.classList.toggle("show"),document.body.classList.toggle("web-stories-lightbox-open")}))))}initializeLightbox(){this.stories=this.player.getStories(),this.bindStoryClickListeners(),this.lightboxInitialized=!0}bindStoryClickListeners(){this.wrapperDiv.querySelectorAll(".web-stories-list__story").forEach((t=>{t.addEventListener("click",(i=>{i.preventDefault();const e=this.stories.find((i=>i.href===t.querySelector("a").href));this.player.show(e.href),this.player.play(),this.lightboxElement.classList.toggle("show"),document.body.classList.toggle("web-stories-lightbox-open")}))}))}}t((()=>{!function(){const t=document.getElementsByClassName("web-stories-list");void 0!==t&&Array.from(t).forEach((t=>{new Lightbox(t)}))}()}))})();