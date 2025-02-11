(()=>{"use strict";const e=window.wp.blocks,t=window.wp.i18n,l=window.React,n=window.wp.blockEditor,i=window.wp.components,s=window.wp.element;(0,e.registerBlockType)("re/link-list",{title:(0,t.__)("Link List","re"),description:(0,t.__)("Display a list of links with hover effects.","re"),category:"widgets",icon:"admin-links",attributes:{title:{type:"string",default:"Links"},links:{type:"array",default:[],items:{type:"object",properties:{text:{type:"string"},url:{type:"string"}}}}},edit:({attributes:e,setAttributes:r})=>{const{title:a,links:c}=e,o=(0,n.useBlockProps)(),[u,m]=(0,s.useState)(c.map((e=>e.text))),[p,k]=(0,s.useState)({});return(0,l.createElement)("div",{...o},(0,l.createElement)(n.RichText,{tagName:"h3",value:a,onChange:e=>{r({title:e})},placeholder:(0,t.__)("Enter list title...","re"),className:"link-list-title"}),(0,l.createElement)("ul",{className:"link-list"},c.map(((e,s)=>(0,l.createElement)("li",{key:`${s}-${e.url}`,className:"link-list-item"},(0,l.createElement)(i.Flex,null,(0,l.createElement)(i.FlexBlock,null,(0,l.createElement)("input",{type:"text",value:u[s],onChange:e=>((e,t)=>{const l=[...u];l[e]=t,m(l)})(s,e.target.value),onBlur:()=>(e=>{const t=[...c];t[e]={...t[e],text:u[e]},r({links:t})})(s),placeholder:(0,t.__)("Link text...","re"),className:"link-text-input"}),(0,l.createElement)("div",{className:"url-input-wrapper"},(0,l.createElement)(i.Button,{icon:"admin-links",className:"url-input-button",onClick:()=>k({...p,[s]:!0})},e.url?e.url:(0,t.__)("Insert Link","re")),p[s]&&(0,l.createElement)(i.Popover,{position:"bottom center",onClose:()=>k({...p,[s]:!1})},(0,l.createElement)("div",{className:"url-input-popover"},(0,l.createElement)(n.URLInput,{value:e.url,onChange:(e,t)=>((e,t,l=null)=>{const n=[...c];if(l&&!u[e]){const t=[...u];t[e]=l.title,m(t),n[e]={...n[e],text:l.title}}n[e]={...n[e],url:t},r({links:n})})(s,e,t),suggestions:!0,hasBorder:!0}))))),(0,l.createElement)(i.Button,{isDestructive:!0,onClick:()=>(e=>{r({links:c.filter(((t,l)=>l!==e))}),m(u.filter(((t,l)=>l!==e)));const t={...p};delete t[e],k(t)})(s),variant:"secondary"},(0,t.__)("Remove","re"))))))),(0,l.createElement)(i.Button,{variant:"secondary",onClick:()=>{r({links:[...c,{text:"",url:""}]}),m([...u,""])},className:"link-list-add-button"},(0,t.__)("Add Link","re")))},save:({attributes:e})=>{const{title:t,links:i}=e,s=n.useBlockProps.save();return(0,l.createElement)("div",{...s},(0,l.createElement)("h3",{className:"link-list-title"},t),(0,l.createElement)("ul",{className:"link-list"},i.map(((e,t)=>(0,l.createElement)("li",{key:t},(0,l.createElement)("a",{href:e.url},e.text))))))}})})();