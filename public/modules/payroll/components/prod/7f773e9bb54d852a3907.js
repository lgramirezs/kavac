(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{"KHd+":function(t,a,s){"use strict";function o(t,a,s,o,i,e,l,r){var n,d="function"==typeof t?t.options:t;if(a&&(d.render=a,d.staticRenderFns=s,d._compiled=!0),o&&(d.functional=!0),e&&(d._scopeId="data-v-"+e),l?(n=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(l)},d._ssrRegister=n):i&&(n=r?function(){i.call(this,this.$root.$options.shadowRoot)}:i),n)if(d.functional){d._injectStyles=n;var c=d.render;d.render=function(t,a){return n.call(a),c(t,a)}}else{var _=d.beforeCreate;d.beforeCreate=_?[].concat(_,n):[n]}return{exports:t,options:d}}s.d(a,"a",function(){return o})},wjNp:function(t,a,s){"use strict";s.r(a);var o={data:function(){return{records:[],record:[],columns:["payroll_staff.first_name","marital_status.name","id"]}},created:function(){this.table_options.headings={"payroll_staff.first_name":"Trabajador","marital_status.name":"Estado Civil",id:"Acción"},this.table_options.sortable=["payroll_staff.first_name","marital_status.name"],this.table_options.filterable=["payroll_staff.first_name","marital_status.name"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){},show_info:function(t){var a=this;axios.get("/payroll/socioeconomics/"+t).then(function(t){a.record=t.data.record,$("#payroll_staff").val(a.record.payroll_staff.first_name+" "+a.record.payroll_staff.last_name),$("#marital_status").val(a.record.marital_status.name),$("#full_name_twosome").val(a.record.full_name_twosome),$("#id_number_twosome").val(a.record.id_number_twosome),$("#birthdate_twosome").val(a.record.birthdate_twosome)}),$("#show_socioeconomic").modal("show")}}},i=s("KHd+"),e=Object(i.a)(o,function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",[s("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(a){return s("div",{staticClass:"text-center"},[t.route_show?s("button",{staticClass:"btn btn-info btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Ver registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(s){return t.show_info(a.row.id)}}},[s("i",{staticClass:"fa fa-eye"})]):t._e(),t._v(" "),a.row.assigned?t._e():s("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Modificar registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(s){return t.editForm(a.row.id)}}},[s("i",{staticClass:"fa fa-edit"})]),t._v(" "),s("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action btn-tooltip",attrs:{title:"Eliminar registro","data-toggle":"tooltip","data-placement":"bottom",type:"button"},on:{click:function(s){return t.deleteRecord(a.index,"")}}},[s("i",{staticClass:"fa fa-trash-o"})])])}}])}),t._v(" "),s("div",{staticClass:"modal fade",attrs:{tabindex:"-1",role:"dialog",id:"show_socioeconomic"}},[s("div",{staticClass:"modal-dialog modal-lg",attrs:{role:"document"}},[s("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),s("div",{staticClass:"modal-body"},[t._m(1),t._v(" "),t._m(2),t._v(" "),s("hr"),t._v(" "),s("div",{staticClass:"row"},[t._m(3),t._v(" "),t._l(t.record.payroll_childrens,function(a){return s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-3"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Nombres")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true"},domProps:{value:a.first_name}})])]),t._v(" "),s("div",{staticClass:"col-md-3"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Apellidos")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true"},domProps:{value:a.last_name}})])]),t._v(" "),s("div",{staticClass:"col-md-3"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Cédula de Identidad")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true"},domProps:{value:a.id_number}})])]),t._v(" "),s("div",{staticClass:"col-md-3"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Fecha de Nacimiento")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true"},domProps:{value:a.birthdate}})])])])})],2)])])])])],1)},[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"modal-header"},[a("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[a("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),a("h6",[a("i",{staticClass:"icofont icofont-read-book ico-2x"}),this._v("\n                        Información Detallada de Datos Socioeconómicos\n                    ")])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group"},[a("label",[this._v("Trabajador")]),this._v(" "),a("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true",id:"payroll_staff"}})])]),this._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group"},[a("label",[this._v("Estado Civil")]),this._v(" "),a("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true",id:"marital_status"}})])])])},function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Nombres y Apellidos de la Pareja del Trabajador")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true",id:"full_name_twosome"}})])]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Cédula de Identidad de la Pareja del Trabajador")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true",id:"id_number_twosome"}})])]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Fecha de Nacimiento de la Pareja del Trabajador")]),t._v(" "),s("input",{staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",disabled:"true",id:"birthdate_twosome"}})])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-md-3"},[a("h6",{staticClass:"card-title"},[this._v("\n                                Hijos del Trabajador\n                            ")])])}],!1,null,null,null);a.default=e.exports}}]);