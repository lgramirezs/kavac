(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{"KHd+":function(t,e,i){"use strict";function o(t,e,i,o,s,r,n,a){var d,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=i,c._compiled=!0),o&&(c.functional=!0),r&&(c._scopeId="data-v-"+r),n?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},c._ssrRegister=d):s&&(d=a?function(){s.call(this,this.$root.$options.shadowRoot)}:s),d)if(c.functional){c._injectStyles=d;var l=c.render;c.render=function(t,e){return d.call(e),l(t,e)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,d):[d]}return{exports:t,options:c}}i.d(e,"a",function(){return o})},R86Q:function(t,e,i){"use strict";i.r(e);var o={data:function(){return{record:{id:"",name:"",description:""},errors:[],records:[],columns:["name","description","id"]}},methods:{reset:function(){this.record={id:"",name:"",description:""}}},created:function(){this.table_options.headings={name:"Nombre",description:"Descripción",id:"Acción"},this.table_options.sortable=["name"],this.table_options.filterable=["name"],this.table_options.columnsClasses={name:"col-md-5",description:"col-md-5",id:"col-md-2"}}},s=i("KHd+"),r=Object(s.a)(o,function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"text-center"},[i("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registros de grados de instrucción","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_payroll_instruction_degree","instruction-degrees",e)}}},[i("i",{staticClass:"icofont icofont-graduate-alt ico-3x"}),t._v(" "),i("span",[t._v("Grados de Instrucción")])]),t._v(" "),i("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_payroll_instruction_degree"}},[i("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[i("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),i("div",{staticClass:"modal-body"},[t.errors.length>0?i("div",{staticClass:"alert alert-danger"},[i("ul",t._l(t.errors,function(e){return i("li",[t._v(t._s(e))])}),0)]):t._e(),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-6"},[i("div",{staticClass:"form-group is-required"},[i("label",{attrs:{for:"name"}},[t._v("Nombre:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"name",placeholder:"Nombre","data-toggle":"tooltip",title:"Indique el nombre del grado de instrucción (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}}),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden",name:"id",id:"id"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})])]),t._v(" "),i("div",{staticClass:"col-md-6"},[i("div",{staticClass:"form-group is-required"},[i("label",{attrs:{for:"description"}},[t._v("Descripción:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.description,expression:"record.description"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"description",placeholder:"Descripción","data-toggle":"tooltip",title:"Indique la descripción del grado de instrucción (requerido)"},domProps:{value:t.record.description},on:{input:function(e){e.target.composing||t.$set(t.record,"description",e.target.value)}}})])])])]),t._v(" "),i("div",{staticClass:"modal-footer"},[i("div",{staticClass:"form-group"},[i("modal-form-buttons",{attrs:{saveRoute:"payroll/instruction-degrees"}})],1)]),t._v(" "),i("div",{staticClass:"modal-body modal-table"},[i("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return i("div",{staticClass:"text-center"},[i("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.initUpdate(e.row.id,i)}}},[i("i",{staticClass:"fa fa-edit"})]),t._v(" "),i("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.deleteRecord(e.row.id,"instruction-degrees")}}},[i("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-graduate-alt ico-3x"}),this._v("\n\t\t\t\t\t\t\tGrado de Instrucción\n\t\t\t\t\t\t")])])}],!1,null,null,null);e.default=r.exports}}]);