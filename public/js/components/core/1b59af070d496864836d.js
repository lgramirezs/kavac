(window.webpackJsonp=window.webpackJsonp||[]).push([[27],{"KHd+":function(e,t,o){"use strict";function n(e,t,o,n,a,i,s,r){var l,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=o,c._compiled=!0),n&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),s?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},c._ssrRegister=l):a&&(l=r?function(){a.call(this,this.$root.$options.shadowRoot)}:a),l)if(c.functional){c._injectStyles=l;var p=c.render;c.render=function(e,t){return l.call(t),p(e,t)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,l):[l]}return{exports:e,options:c}}o.d(t,"a",(function(){return n}))},Lr05:function(e,t,o){"use strict";o.r(t);var n={data:function(){return{phones:[]}},watch:{phones:function(){localStorage.removeItem("phones"),this.phones&&(localStorage.phones=JSON.stringify(this.phones))}},props:["initial_data"],methods:{addPhone:function(){this.phones.push({type:"",area_code:"",number:"",extension:""})}},mounted:function(){this.initial_data&&(this.phones=JSON.parse(this.initial_data))}},a=o("KHd+"),i=Object(a.a)(n,(function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",[o("h6",{staticClass:"card-title"},[e._v("\n\t\tNúmeros Telefónicos "),o("i",{staticClass:"fa fa-plus-circle cursor-pointer",on:{click:e.addPhone}})]),e._v(" "),e._l(e.phones,(function(t,n){return o("div",{staticClass:"row"},[o("div",{staticClass:"col-3"},[o("div",{staticClass:"form-group is-required"},[o("select",{directives:[{name:"model",rawName:"v-model",value:t.type,expression:"phone.type"}],staticClass:"select2",attrs:{"data-toggle":"tooltip",name:"phone_type[]",title:"Seleccione el tipo de número telefónico"},on:{change:function(o){var n=Array.prototype.filter.call(o.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(t,"type",o.target.multiple?n:n[0])}}},[o("option",{attrs:{value:""}},[e._v("Seleccione...")]),e._v(" "),o("option",{attrs:{value:"M"}},[e._v("Móvil")]),e._v(" "),o("option",{attrs:{value:"T"}},[e._v("Teléfono")]),e._v(" "),o("option",{attrs:{value:"F"}},[e._v("Fax")])])])]),e._v(" "),o("div",{staticClass:"col-2"},[o("div",{staticClass:"form-group is-required"},[o("input",{directives:[{name:"model",rawName:"v-model",value:t.area_code,expression:"phone.area_code"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Cod. Area","data-toggle":"tooltip",name:"phone_area_code[]",title:"Indique el código de área"},domProps:{value:t.area_code},on:{input:function(o){o.target.composing||e.$set(t,"area_code",o.target.value)}}})])]),e._v(" "),o("div",{staticClass:"col-4"},[o("div",{staticClass:"form-group is-required"},[o("input",{directives:[{name:"model",rawName:"v-model",value:t.number,expression:"phone.number"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Número","data-toggle":"tooltip",name:"phone_number[]",title:"Indique el número telefónico"},domProps:{value:t.number},on:{input:function(o){o.target.composing||e.$set(t,"number",o.target.value)}}})])]),e._v(" "),o("div",{staticClass:"col-2"},[o("div",{staticClass:"form-group is-required"},[o("input",{directives:[{name:"model",rawName:"v-model",value:t.extension,expression:"phone.extension"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Extensión","data-toggle":"tooltip",name:"phone_extension[]",title:"Indique la extención telefónica (opcional)"},domProps:{value:t.extension},on:{input:function(o){o.target.composing||e.$set(t,"extension",o.target.value)}}})])]),e._v(" "),o("div",{staticClass:"col-1"},[o("div",{staticClass:"form-group"},[o("button",{staticClass:"btn btn-sm btn-danger btn-action",attrs:{type:"button",title:"Eliminar este dato","data-toggle":"tooltip"},on:{click:function(t){return e.removeRow(n,e.phones)}}},[o("i",{staticClass:"fa fa-minus-circle"})])])])])}))],2)}),[],!1,null,null,null);t.default=i.exports}}]);