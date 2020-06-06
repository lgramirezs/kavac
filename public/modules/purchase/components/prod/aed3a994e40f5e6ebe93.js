(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{DC4u:function(t,e,a){"use strict";a.r(e);var s={data:function(){return{records:[],columns:["date","purchase_type_operation.name","ut","active","id"],record:{date:"",purchase_type_operation_id:"",ut:"0.00",active:!1},type_operations:[],purchaseProcesses:[],edit:!1}},methods:{reset:function(){this.edit=!1,this.record={id:"",date:"",purchase_type_operation:"",ut:"",active:!1}},createRecord:function(t){var e=this;this.record.active=$("#active").prop("checked"),this.edit?this.edit&&this.record.id&&axios.put(t+"/"+this.record.id,this.record).then(function(t){e.records=t.data.records,e.showMessage("update"),e.reset()}):axios.post(t,this.record).then(function(t){e.records=t.data.records,e.showMessage("store"),e.reset()})},loadData:function(t){this.edit=!0,this.record=t}},created:function(){this.table_options.headings={date:"Fecha","purchase_type_operation.name":"Tipo",ut:"Unidades tributarias",active:"Estatus",id:"Acción"},this.table_options.sortable=["date","purchase_type_operation.name","ut","active"],this.table_options.filterable=["date","purchase_type_operation.name","ut","active"],this.table_options.columnsClasses={date:"col-xs-2 text-center","purchase_type_operation.name":"col-xs-4",ut:"col-xs-3",active:"col-xs-2",id:"col-xs-1"}},mounted:function(){var t=this;axios.get("/purchase/get-type-operations").then(function(e){t.type_operations=e.data.records})}},i=a("KHd+"),r=Object(i.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-xs-2 text-center"},[a("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"#",title:"Registros de tipos de compras","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_purchase_type_hiring","/purchase/type_hiring",e)}}},[a("i",{staticClass:"fa fa-tag ico-3x"}),t._v(" "),t._m(0)]),t._v(" "),a("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_purchase_type_hiring"}},[a("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[a("div",{staticClass:"modal-content"},[t._m(1),t._v(" "),a("div",{staticClass:"modal-body"},[a("purchase-show-errors",{ref:"purchaseTypesErrors"}),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{staticClass:"control-label",attrs:{for:"record_date"}},[t._v("Fecha\n                                ")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.date,expression:"record.date"}],staticClass:"form-control",attrs:{type:"date",id:"record_date",tabindex:"1"},domProps:{value:t.record.date},on:{input:function(e){e.target.composing||t.$set(t.record,"date",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group"},[a("label",{staticClass:"control-label",attrs:{for:"purchase_type_operation_id"}},[t._v("Tipo:")]),a("br"),t._v(" "),a("select2",{attrs:{options:t.type_operations,id:"purchase_type_operation_id",placeholder:"Tipo de contratación"},model:{value:t.record.purchase_type_operation_id,callback:function(e){t.$set(t.record,"purchase_type_operation_id",e)},expression:"record.purchase_type_operation_id"}})],1)]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"record_ut"}},[t._v("Unidades tributarias:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.ut,expression:"record.ut"}],staticClass:"form-control",attrs:{type:"number",id:"record_ut",title:"Indique las unidades tributarias"},domProps:{value:t.record.ut},on:{input:function(e){e.target.composing||t.$set(t.record,"ut",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group"},[a("label",{staticClass:"control-label"},[t._v("Activa")]),t._v(" "),a("div",{staticClass:"col-12"},[a("div",{staticClass:"col-12 bootstrap-switch-mini"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.active,expression:"record.active"}],staticClass:"form-control bootstrap-switch",attrs:{id:"active","data-on-label":"SI","data-off-label":"NO",name:"active",type:"checkbox"},domProps:{checked:Array.isArray(t.record.active)?t._i(t.record.active,null)>-1:t.record.active},on:{change:function(e){var a=t.record.active,s=e.target,i=!!s.checked;if(Array.isArray(a)){var r=t._i(a,null);s.checked?r<0&&t.$set(t.record,"active",a.concat([null])):r>-1&&t.$set(t.record,"active",a.slice(0,r).concat(a.slice(r+1)))}else t.$set(t.record,"active",i)}}})])])])])])],1),t._v(" "),a("div",{staticClass:"modal-footer"},[a("div",{staticClass:"form-group"},[a("modal-form-buttons",{attrs:{saveRoute:"/purchase/type_hiring"}})],1)]),t._v(" "),a("div",{staticClass:"modal-body modal-table"},[a("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"date",fn:function(e){return a("div",{},[a("strong",[t._v(t._s(t.format_date(e.row.date)))])])}},{key:"ut",fn:function(e){return a("div",{},[a("strong",[t._v(t._s(e.row.ut+" UT"))])])}},{key:"active",fn:function(e){return a("div",{staticClass:"text-center"},[e.row.active?a("div",[a("span",{staticClass:"badge badge-success"},[a("strong",[t._v("Activa")])])]):a("div",[a("span",{staticClass:"badge badge-warning"},[a("strong",[t._v("Inactiva")])])])])}},{key:"id",fn:function(e){return a("div",{staticClass:"text-center"},[a("div",{staticClass:"d-inline-flex"},[a("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip"},on:{click:function(a){return t.loadData(e.row)}}},[a("i",{staticClass:"fa fa-edit"})]),t._v(" "),a("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip"},on:{click:function(a){return t.deleteRecord(e.index,"/purchase/type_hiring")}}},[a("i",{staticClass:"fa fa-trash-o"})])])])}}])},[t._v("}\n                        ")])],1)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("span",[this._v("Tipos de"),e("br"),this._v("contratación")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-box inline-block"}),this._v("\n                        Tipo de contratación\n                    ")])])}],!1,null,null,null);e.default=r.exports},"KHd+":function(t,e,a){"use strict";function s(t,e,a,s,i,r,o,c){var n,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=a,d._compiled=!0),s&&(d.functional=!0),r&&(d._scopeId="data-v-"+r),o?(n=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},d._ssrRegister=n):i&&(n=c?function(){i.call(this,this.$root.$options.shadowRoot)}:i),n)if(d.functional){d._injectStyles=n;var l=d.render;d.render=function(t,e){return n.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,n):[n]}return{exports:t,options:d}}a.d(e,"a",function(){return s})}}]);