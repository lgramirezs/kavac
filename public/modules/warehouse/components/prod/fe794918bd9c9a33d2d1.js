(window.webpackJsonp=window.webpackJsonp||[]).push([[18],{"KHd+":function(t,e,s){"use strict";function a(t,e,s,a,o,n,i,r){var c,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=s,d._compiled=!0),a&&(d.functional=!0),n&&(d._scopeId="data-v-"+n),i?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},d._ssrRegister=c):o&&(c=r?function(){o.call(this,this.$root.$options.shadowRoot)}:o),c)if(d.functional){d._injectStyles=c;var l=d.render;d.render=function(t,e){return c.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:d}}s.d(e,"a",function(){return a})},mkW5:function(t,e,s){"use strict";s.r(e);var a={data:function(){return{records:[],errors:[],columns:["code","warehouse_inventory_product.warehouse_product.name","warehouse_inventory_product.warehouse_product.description","quantity","unit_value"]}},props:{request:Object},created:function(){this.table_options.headings={code:"Código","warehouse_inventory_product.warehouse_product.name":"Nombre","warehouse_inventory_product.warehouse_product.description":"Descripción",quantity:"Cantidad Agregada",unit_value:"Valor por Unidad"},this.table_options.sortable=["code","warehouse_inventory_product.warehouse_product.name","warehouse_inventory_product.warehouse_product.description","quantity","unit_value"],this.table_options.filterable=["code","warehouse_inventory_product.warehouse_product.name","warehouse_inventory_product.warehouse_product.description","quantity","unit_value"]},methods:{reset:function(){},initRecords:function(t,e){var s=this;this.errors=[],this.reset();var a=this,o={};document.getElementById("info_general").click(),axios.get(t).then(function(t){void 0!==t.data.records&&(o=t.data.records,$(".modal-body #id").val(o.id),document.getElementById("date_init").innerText=o.created_at?o.created_at:"",document.getElementById("department").innerText=o.department?o.department.name:"",document.getElementById("motive").innerText=o.motive?o.motive:"",document.getElementById("observations").innerText=o.observations?o.observations:"No definido",document.getElementById("state").innerText=o.state?o.state:"",s.records=o.warehouse_inventory_product_requests),$("#"+e).length&&$("#"+e).modal("show")}).catch(function(t){void 0!==t.response&&(403==t.response.status?a.showMessage("custom","Acceso Denegado","danger","screen-error",t.response.data.message):a.logs("resources/js/all.js",343,t,"initRecords"))})},loadProducts:function(){var t=this,e=$(".modal-body #id").val();axios.get("/warehouse/requests/info/"+e).then(function(e){t.records=e.data.records.warehouse_inventory_product_requests})}}},o=s("KHd+"),n=Object(o.a)(a,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("a",{staticClass:"btn btn-info btn-xs btn-icon btn-action",attrs:{href:"#",title:"Ver información del registro","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("view_warehouse_request",t.route_list,e)}}},[s("i",{staticClass:"fa fa-info-circle"})]),t._v(" "),s("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"view_warehouse_request"}},[s("div",{staticClass:"modal-dialog modal-lg"},[s("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),s("div",{staticClass:"modal-body"},[s("ul",{staticClass:"nav nav-tabs custom-tabs justify-content-center",attrs:{role:"tablist"}},[t._m(1),t._v(" "),s("li",{staticClass:"nav-item"},[s("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#equipment",role:"tab"},on:{click:function(e){return t.loadProducts()}}},[s("i",{staticClass:"ion-arrow-swap"}),t._v(" Equipos Solicitados\n                            ")])])]),t._v(" "),s("div",{staticClass:"tab-content"},[t._m(2),t._v(" "),s("div",{staticClass:"tab-pane",attrs:{id:"equipment",role:"tabpanel"}},[s("div",{staticClass:"modal-table"},[s("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"code",fn:function(e){return s("div",{staticClass:"text-center"},[s("span",[t._v("\n\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.warehouse_inventory_product.code)+" \n\t\t\t\t\t\t\t\t\t\t")])])}},{key:"quantity",fn:function(e){return s("div",{},[s("span",[t._v("\n\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.quantity)+" \n\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.warehouse_inventory_product.warehouse_product.measurement_unit?e.row.warehouse_inventory_product.warehouse_product.measurement_unit.acronym:"")+"\n\t\t\t\t\t\t\t\t\t\t")])])}},{key:"unit_value",fn:function(e){return s("div",{},[s("span",[t._v("\n\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.warehouse_inventory_product.unit_value)+" \n\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.warehouse_inventory_product.currency?e.row.warehouse_inventory_product.currency.symbol:"")+"\n\t\t\t\t\t\t\t\t\t\t")])])}}])})],1)])])]),t._v(" "),t._m(3)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-read-book ico-2x"}),this._v(" \n\t\t\t\t\t\tInformación de la Solicitud Registrada\n\t\t\t\t\t")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("li",{staticClass:"nav-item"},[e("a",{staticClass:"nav-link active",attrs:{"data-toggle":"tab",id:"info_general",href:"#general",role:"tab"}},[e("i",{staticClass:"ion-android-person"}),this._v(" Información General\n                            ")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"tab-pane active",attrs:{id:"general",role:"tabpanel"}},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6"},[s("div",{staticClass:"form-group"},[s("strong",[t._v("Fecha de Registro")]),t._v(" "),s("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[s("span",{staticClass:"col-md-12",attrs:{id:"date_init"}})]),t._v(" "),s("input",{attrs:{type:"hidden",id:"id"}})])]),t._v(" "),s("div",{staticClass:"col-md-6"},[s("div",{staticClass:"form-group"},[s("strong",[t._v("Departamento Solicitante")]),t._v(" "),s("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[s("span",{staticClass:"col-md-12",attrs:{id:"department"}})])])]),t._v(" "),s("div",{staticClass:"col-md-6"},[s("div",{staticClass:"form-group"},[s("strong",[t._v("Motivo")]),t._v(" "),s("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[s("span",{staticClass:"col-md-12",attrs:{id:"motive"}})])])]),t._v(" "),s("div",{staticClass:"col-md-6"},[s("div",{staticClass:"form-group"},[s("strong",[t._v("Estado de la Solicitud")]),t._v(" "),s("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[s("span",{staticClass:"col-md-12",attrs:{id:"state"}})])])]),t._v(" "),s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"form-group"},[s("strong",[t._v("Observaciones")]),t._v(" "),s("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[s("span",{staticClass:"col-md-12",attrs:{id:"observations"}})])])])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-default btn-sm btn-round btn-modal-close",attrs:{type:"button","data-dismiss":"modal"}},[this._v("\n                \t\tCerrar\n                \t")])])}],!1,null,null,null);e.default=n.exports}}]);