(()=>{"use strict";var e,r={977:(e,r,t)=>{var o=t(738);function n(e){return function(e){if(Array.isArray(e))return a(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,r){if(e){if("string"==typeof e)return a(e,r);var t={}.toString.call(e).slice(8,-1);return"Object"===t&&e.constructor&&(t=e.constructor.name),"Map"===t||"Set"===t?Array.from(e):"Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t)?a(e,r):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(e,r){(null==r||r>e.length)&&(r=e.length);for(var t=0,o=Array(r);t<r;t++)o[t]=e[t];return o}document.addEventListener("DOMContentLoaded",(function(){var e=document.querySelector(".site-header.wp-block-template-part");e&&o.os.from(e,{opacity:0,duration:.75,ease:"power2.inOut"});var r=document.querySelectorAll(".wp-block-navigation-item"),t=document.querySelectorAll(".wp-block-social-links .wp-block-social-link"),a=[].concat(n(r),n(t));if(a.length){var i=o.os.timeline({defaults:{duration:.2}});a.forEach((function(e,r){var t=o.os.timeline({delay:.1*r});t.from(e,{opacity:0,duration:.2,ease:"power1.inOut"}).to(e,{y:-25,duration:.25,ease:"power3.out"}).to(e,{y:0,duration:.35,ease:"power2.inOut"}),i.add(t,.1*r)}))}})),window.addEventListener("pagehide",(function(){o.os.killTweensOf("*")}))},738:(e,r,t)=>{t.d(r,{os:()=>o.os});var o=t(880),n=t(575);o.os.registerPlugin(n.u),o.os.defaults({ease:"power2.out",duration:.8})}},t={};function o(e){var n=t[e];if(void 0!==n)return n.exports;var a=t[e]={exports:{}};return r[e](a,a.exports,o),a.exports}o.m=r,e=[],o.O=(r,t,n,a)=>{if(!t){var i=1/0;for(c=0;c<e.length;c++){for(var[t,n,a]=e[c],l=!0,u=0;u<t.length;u++)(!1&a||i>=a)&&Object.keys(o.O).every((e=>o.O[e](t[u])))?t.splice(u--,1):(l=!1,a<i&&(i=a));if(l){e.splice(c--,1);var s=n();void 0!==s&&(r=s)}}return r}a=a||0;for(var c=e.length;c>0&&e[c-1][2]>a;c--)e[c]=e[c-1];e[c]=[t,n,a]},o.d=(e,r)=>{for(var t in r)o.o(r,t)&&!o.o(e,t)&&Object.defineProperty(e,t,{enumerable:!0,get:r[t]})},o.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),(()=>{var e={151:0,396:0};o.O.j=r=>0===e[r];var r=(r,t)=>{var n,a,[i,l,u]=t,s=0;if(i.some((r=>0!==e[r]))){for(n in l)o.o(l,n)&&(o.m[n]=l[n]);if(u)var c=u(o)}for(r&&r(t);s<i.length;s++)a=i[s],o.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return o.O(c)},t=globalThis.webpackChunkre_theme=globalThis.webpackChunkre_theme||[];t.forEach(r.bind(null,0)),t.push=r.bind(null,t.push.bind(t))})();var n=o.O(void 0,[770],(()=>o(977)));n=o.O(n)})();