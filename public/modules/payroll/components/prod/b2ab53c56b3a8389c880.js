(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{"KHd+":function(t,e,o){"use strict";function i(t,e,o,i,s,a,n,r){var d,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=o,c._compiled=!0),i&&(c.functional=!0),a&&(c._scopeId="data-v-"+a),n?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},c._ssrRegister=d):s&&(d=r?function(){s.call(this,this.$root.$options.shadowRoot)}:s),d)if(c.functional){c._injectStyles=d;var l=c.render;c.render=function(t,e){return d.call(e),l(t,e)}}else{var p=c.beforeCreate;c.beforeCreate=p?[].concat(p,d):[d]}return{exports:t,options:c}}o.d(e,"a",function(){return i})},pCPJ:function(t,e,o){"use strict";o.r(e);var i={data:function(){return{record:{id:"",name:"",description:""},errors:[],records:[],columns:["name","description","id"]}},methods:{reset:function(){this.record={id:"",name:"",description:""}}},created:function(){this.table_options.headings={name:"Nombre",description:"Descripción",id:"Acción"},this.table_options.sortable=["name"],this.table_options.filterable=["name"],this.table_options.columnsClasses={name:"col-md-5",description:"col-md-5",id:"col-md-2"}}},s=o("KHd+"),a=Object(s.a)(i,function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"text-center"},[o("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registros de tipos de cargo","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_payroll_position_type","position-types",e)}}},[o("i",{staticClass:"icofont icofont-contact-add ico-3x"}),t._v(" "),o("span",[t._v("Tipos de Cargo")])]),t._v(" "),o("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_payroll_position_type"}},[o("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[o("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),o("div",{staticClass:"modal-body"},[t.errors.length>0?o("div",{staticClass:"alert alert-danger"},[o("ul",t._l(t.errors,function(e){return o("li",[t._v(t._s(e))])}),0)]):t._e(),t._v(" "),o("div",{staticClass:"row"},[o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"name"}},[t._v("Nombre:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"name",placeholder:"Nombre","data-toggle":"tooltip",title:"Indique el nombre del tipo de cargo (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}}),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden",name:"id",id:"id"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})])]),t._v(" "),o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"description"}},[t._v("Descripción:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.description,expression:"record.description"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"description",placeholder:"Descripción","data-toggle":"tooltip",title:"Indique la descripción del tipo de cargo (requerido)"},domProps:{value:t.record.description},on:{input:function(e){e.target.composing||t.$set(t.record,"description",e.target.value)}}})])])])]),t._v(" "),o("div",{staticClass:"modal-footer"},[o("div",{staticClass:"form-group"},[o("modal-form-buttons",{attrs:{saveRoute:"payroll/position-types"}})],1)]),t._v(" "),o("div",{staticClass:"modal-body modal-table"},[o("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return o("div",{staticClass:"text-center"},[o("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(o){return t.initUpdate(e.row.id,o)}}},[o("i",{staticClass:"fa fa-edit"})]),t._v(" "),o("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(o){return t.deleteRecord(e.row.id,"position-types")}}},[o("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-contact-add ico-3x"}),this._v("\n\t\t\t\t\t\t\tTipo de Cargo\n\t\t\t\t\t\t")])])}],!1,null,null,null);e.default=a.exports}}]);