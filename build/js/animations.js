(()=>{"use strict";var r,e={977:(r,e,t)=>{var o=t(738);document.addEventListener("DOMContentLoaded",(function(){var r=document.querySelector(".site-header");r&&(0,o.qG)(r,0,!1);var e=document.querySelectorAll(".wp-block-navigation-item");e.length&&(0,o.lM)(e,.2,!1),document.querySelectorAll(".entry-content > *:not(.project):not(.intro-group):not(.introduction):not(.project-links)").forEach((function(r){o.os.from(r,{scrollTrigger:{trigger:r,start:"top 80%",toggleActions:"play none none reverse"},opacity:0,y:30,duration:.8,clearProps:"transform,opacity"})})),o.uY.refresh()})),window.addEventListener("unload",(function(){o.uY.getAll().forEach((function(r){return r.kill()}))}))},738:(r,e,t)=>{t.d(e,{lM:()=>f,os:()=>o.os,qG:()=>l,uY:()=>n.u});var o=t(880),n=t(575);function i(r){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(r){return typeof r}:function(r){return r&&"function"==typeof Symbol&&r.constructor===Symbol&&r!==Symbol.prototype?"symbol":typeof r},i(r)}function c(r,e){var t=Object.keys(r);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(r);e&&(o=o.filter((function(e){return Object.getOwnPropertyDescriptor(r,e).enumerable}))),t.push.apply(t,o)}return t}function a(r){for(var e=1;e<arguments.length;e++){var t=null!=arguments[e]?arguments[e]:{};e%2?c(Object(t),!0).forEach((function(e){u(r,e,t[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(r,Object.getOwnPropertyDescriptors(t)):c(Object(t)).forEach((function(e){Object.defineProperty(r,e,Object.getOwnPropertyDescriptor(t,e))}))}return r}function u(r,e,t){return(e=function(r){var e=function(r){if("object"!=i(r)||!r)return r;var e=r[Symbol.toPrimitive];if(void 0!==e){var t=e.call(r,"string");if("object"!=i(t))return t;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(r)}(r);return"symbol"==i(e)?e:e+""}(e))in r?Object.defineProperty(r,e,{value:t,enumerable:!0,configurable:!0,writable:!0}):r[e]=t,r}o.os.registerPlugin(n.u),o.os.defaults({ease:"power2.out",duration:.8});var l=function(r){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,t=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];return o.os.from(r,a(a({opacity:0},t?{y:20}:{}),{},{duration:.8,delay:e,clearProps:t?"all":"opacity"}))},f=function(r){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:.2,t=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];return o.os.from(r,a(a({opacity:0},t?{y:20}:{}),{},{duration:.8,stagger:e,clearProps:t?"all":"opacity"}))}}},t={};function o(r){var n=t[r];if(void 0!==n)return n.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,o),i.exports}o.m=e,r=[],o.O=(e,t,n,i)=>{if(!t){var c=1/0;for(f=0;f<r.length;f++){for(var[t,n,i]=r[f],a=!0,u=0;u<t.length;u++)(!1&i||c>=i)&&Object.keys(o.O).every((r=>o.O[r](t[u])))?t.splice(u--,1):(a=!1,i<c&&(c=i));if(a){r.splice(f--,1);var l=n();void 0!==l&&(e=l)}}return e}i=i||0;for(var f=r.length;f>0&&r[f-1][2]>i;f--)r[f]=r[f-1];r[f]=[t,n,i]},o.d=(r,e)=>{for(var t in e)o.o(e,t)&&!o.o(r,t)&&Object.defineProperty(r,t,{enumerable:!0,get:e[t]})},o.o=(r,e)=>Object.prototype.hasOwnProperty.call(r,e),(()=>{var r={151:0,396:0};o.O.j=e=>0===r[e];var e=(e,t)=>{var n,i,[c,a,u]=t,l=0;if(c.some((e=>0!==r[e]))){for(n in a)o.o(a,n)&&(o.m[n]=a[n]);if(u)var f=u(o)}for(e&&e(t);l<c.length;l++)i=c[l],o.o(r,i)&&r[i]&&r[i][0](),r[i]=0;return o.O(f)},t=globalThis.webpackChunkre_theme=globalThis.webpackChunkre_theme||[];t.forEach(e.bind(null,0)),t.push=e.bind(null,t.push.bind(t))})();var n=o.O(void 0,[770],(()=>o(977)));n=o.O(n)})();