(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{"KHd+":function(t,e,o){"use strict";function i(t,e,o,i,r,s,a,d){var n,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=o,c._compiled=!0),i&&(c.functional=!0),s&&(c._scopeId="data-v-"+s),a?(n=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},c._ssrRegister=n):r&&(n=d?function(){r.call(this,this.$root.$options.shadowRoot)}:r),n)if(c.functional){c._injectStyles=n;var l=c.render;c.render=function(t,e){return n.call(e),l(t,e)}}else{var p=c.beforeCreate;c.beforeCreate=p?[].concat(p,n):[n]}return{exports:t,options:c}}o.d(e,"a",(function(){return i}))},Spl0:function(t,e,o){"use strict";o.r(e);var i={data:function(){return{record:{id:"",sale_setting_product_type_id:"",name:"",code:"",description:"",price:"",iva:""},errors:[],sale_setting_product_type_id:[],records:[],columns:["sale_setting_product_type.name","name","code","description","price","iva","id"]}},methods:{reset:function(){this.record={id:"",sale_setting_product_type_id:"",name:"",code:"",description:"",price:"",iva:""}}},created:function(){this.table_options.headings={"sale_setting_product_type.name":"Tipo de producto",name:"Nombre",code:"Código",description:"Descripción",price:"Precio unitario",iva:"IVA",id:"Acción"},this.table_options.sortable=["sale_setting_product_type.name","name"],this.table_options.filterable=["sale_setting_product_type.name","name"],this.table_options.columnsClasses={sale_setting_product_type_id:"Tipo de producto",name:"Nombre",code:"Código",description:"col-md-5",price:"Precio unitario",iva:"IVA",id:"col-md-2"},this.getSaleSettingProductType()}},r=o("KHd+"),s=Object(r.a)(i,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"text-center"},[o("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registro de Productos","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_sale_setting_product","setting-product",e)}}},[o("i",{staticClass:"icofont icofont-social-dropbox ico-3x"}),t._v(" "),o("span",[t._v("Productos")])]),t._v(" "),o("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_sale_setting_product"}},[o("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[o("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),o("div",{staticClass:"modal-body"},[t.errors.length>0?o("div",{staticClass:"alert alert-danger"},[o("ul",t._l(t.errors,(function(e){return o("li",[t._v(t._s(e))])})),0)]):t._e(),t._v(" "),o("div",{staticClass:"row"},[o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",[t._v("Típo de producto")]),t._v(" "),o("select2",{attrs:{options:t.sale_setting_product_type,id:"sale_setting_product_type_id"},model:{value:t.record.sale_setting_product_type_id,callback:function(e){t.$set(t.record,"sale_setting_product_type_id",e)},expression:"record.sale_setting_product_type_id"}})],1)]),t._v(" "),o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"name"}},[t._v("Nombre:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"name",placeholder:"Nombre","data-toggle":"tooltip",title:"Indique el nombre (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}}),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden",name:"id",id:"id"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})])]),t._v(" "),o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"code"}},[t._v("Código:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.code,expression:"record.code"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"code",placeholder:"Código","data-toggle":"tooltip",title:"Indique el código (requerido)"},domProps:{value:t.record.code},on:{input:function(e){e.target.composing||t.$set(t.record,"code",e.target.value)}}})])]),t._v(" "),o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"description"}},[t._v("Descripción:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.description,expression:"record.description"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"description",placeholder:"Descripción","data-toggle":"tooltip",title:"Indique la descripción (requerido)"},domProps:{value:t.record.description},on:{input:function(e){e.target.composing||t.$set(t.record,"description",e.target.value)}}})])]),t._v(" "),o("div",{staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"price"}},[t._v("Precio unitario:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.price,expression:"record.price"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"price",placeholder:"Precio unitario","data-toggle":"tooltip",title:"Indique el precio unitario (requerido)"},domProps:{value:t.record.price},on:{input:function(e){e.target.composing||t.$set(t.record,"price",e.target.value)}}})])]),t._v(" "),o("div",{directives:[{name:"show",rawName:"v-show",value:1==t.record.sale_setting_product_type_id,expression:"record.sale_setting_product_type_id == 1"}],staticClass:"col-md-6"},[o("div",{staticClass:"form-group is-required"},[o("label",{attrs:{for:"iva"}},[t._v("IVA:")]),t._v(" "),o("input",{directives:[{name:"model",rawName:"v-model",value:t.record.iva,expression:"record.iva"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"iva",placeholder:"IVA","data-toggle":"tooltip",title:"Indique el IVA (requerido)"},domProps:{value:t.record.iva},on:{input:function(e){e.target.composing||t.$set(t.record,"iva",e.target.value)}}})])])])]),t._v(" "),o("div",{staticClass:"modal-footer"},[o("div",{staticClass:"form-group"},[o("modal-form-buttons",{attrs:{saveRoute:"sale/setting-product"}})],1),t._v(" "),o("div",{staticClass:"form-group"},[o("button",{staticClass:"btn btn-default btn-sm btn-round",attrs:{"data-toggle":"tooltip",type:"button",title:"Borrar datos del formulario"},on:{click:t.reset}},[t._v("Borrar\n\t\t\t\t\t\t\t")])])]),t._v(" "),o("div",{staticClass:"modal-body modal-table"},[o("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return o("div",{staticClass:"text-center"},[o("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(o){return t.initUpdate(e.row.id,o)}}},[o("i",{staticClass:"fa fa-edit"})]),t._v(" "),o("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(o){return t.deleteRecord(e.row.id,"setting-product")}}},[o("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-social-dropbox ico-3x"}),this._v("\n\t\t\t\t\t\t\tProductos\n\t\t\t\t\t\t")])])}],!1,null,null,null);e.default=s.exports}}]);