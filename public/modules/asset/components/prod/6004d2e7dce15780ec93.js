(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{I90X:function(t,a,e){"use strict";e.r(a);var s={data:function(){return{records:[],errors:[],columns:["inventory_serial","serial","marca","model"]}},props:{operation:Object},created:function(){this.table_options.headings={inventory_serial:"Código",serial:"Serial",marca:"Marca",model:"Modelo"},this.table_options.sortable=["inventory_serial","serial","marca","model"],this.table_options.filterable=!1,this.table_options.orderBy={column:"inventory_serial"}},methods:{reset:function(){},initRecords:function(t,a){var e=this;e.errors=[],e.reset(),document.getElementById("info_general").click(),$(".modal-body #url_search").val(e.operation.type_operation+"/"+e.operation.code),document.getElementById("created_at").innerText=e.operation.created_at?e.operation.created_at:"N/A",document.getElementById("type_operation").innerText="registers"==e.operation.type_operation?"Registro de bienes":"asignations"==e.operation.type_operation?"Asignación de bienes":"requests"==e.operation.type_operation?"Solicitud de bienes":"disincorporations"==e.operation.type_operation?"Desincorporación de bienes":"N/A",document.getElementById("description").innerText=e.operation.description?e.operation.description:"N/A",$("#"+a).length&&$("#"+a).modal("show")},loadEquipment:function(){var t=this,a=$(".modal-body #url_search").val();axios.get(this.route_list+"/"+a).then((function(a){t.records=a.data.records}))}}},i=e("KHd+"),n=Object(i.a)(s,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("a",{staticClass:"btn btn-info btn-xs btn-icon btn-action",attrs:{href:"#",title:"Ver información de la operación","data-toggle":"tooltip"},on:{click:function(a){return t.addRecord("view_operation",t.route_list,a)}}},[e("i",{staticClass:"fa fa-info-circle"})]),t._v(" "),e("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"view_operation"}},[e("div",{staticClass:"modal-dialog modal-lg"},[e("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),e("div",{staticClass:"modal-body"},[t.errors.length>0?e("div",{staticClass:"alert alert-danger"},[e("ul",t._l(t.errors,(function(a){return e("li",[t._v(t._s(a))])})),0)]):t._e(),t._v(" "),e("ul",{staticClass:"nav nav-tabs custom-tabs justify-content-center",attrs:{role:"tablist"}},[t._m(1),t._v(" "),e("li",{staticClass:"nav-item"},[e("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#equipment",role:"tab"},on:{click:function(a){return t.loadEquipment()}}},[e("i",{staticClass:"ion-arrow-swap"}),t._v(" Equipos\n                            ")])])]),t._v(" "),e("div",{staticClass:"tab-content"},[t._m(2),t._v(" "),e("div",{staticClass:"tab-pane",attrs:{id:"equipment",role:"tabpanel"}},[t._m(3),t._v(" "),e("div",{staticClass:"modal-table"},[e("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"inventory_serial",fn:function(a){return e("div",{staticClass:"text-center"},[e("span",[t._v("\n\t                                        "+t._s(a.row.asset?a.row.asset.inventory_serial:a.row.inventory_serial)+"\n\t                                    ")])])}},{key:"serial",fn:function(a){return e("div",{staticClass:"text-center"},[e("span",[t._v("\n\t                                        "+t._s(a.row.asset?a.row.asset.serial:a.row.serial)+"\n\t                                    ")])])}},{key:"marca",fn:function(a){return e("div",{staticClass:"text-center"},[e("span",[t._v("\n\t                                        "+t._s(a.row.asset?a.row.asset.marca:a.row.marca)+"\n\t                                    ")])])}},{key:"model",fn:function(a){return e("div",{staticClass:"text-center"},[e("span",[t._v("\n\t                                        "+t._s(a.row.asset?a.row.asset.model:a.row.model)+"\n\t                                    ")])])}}])})],1)])])]),t._v(" "),t._m(4)])])])])}),[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"modal-header"},[a("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[a("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),a("h6",[a("i",{staticClass:"icofont icofont-read-book ico-2x"}),this._v("\n\t\t\t\t\t\tInformación de la Operación Registrada\n\t\t\t\t\t")])])},function(){var t=this.$createElement,a=this._self._c||t;return a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link active",attrs:{"data-toggle":"tab",href:"#general",id:"info_general",role:"tab"}},[a("i",{staticClass:"ion-android-person"}),this._v(" Información General\n                            ")])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"tab-pane active",attrs:{id:"general",role:"tabpanel"}},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-md-6"},[e("div",{staticClass:"form-group"},[e("strong",[t._v("Fecha de la Operación")]),t._v(" "),e("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[e("span",{staticClass:"col-md-12",attrs:{id:"created_at"}})]),t._v(" "),e("input",{attrs:{type:"hidden",id:"url_search"}})])]),t._v(" "),e("div",{staticClass:"col-md-6"},[e("div",{staticClass:"form-group"},[e("strong",[t._v("Tipo de Operación")]),t._v(" "),e("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[e("span",{staticClass:"col-md-12",attrs:{id:"type_operation"}})])])]),t._v(" "),e("div",{staticClass:"col-md-12"},[e("div",{staticClass:"form-group"},[e("strong",[t._v("Descripción")]),t._v(" "),e("div",{staticClass:"row",staticStyle:{margin:"1px 0"}},[e("span",{staticClass:"col-md-12",attrs:{id:"description"}})])])])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("span",{staticClass:"text-muted"},[this._v("\n\t                                    A continuación se muestran los equipos asociados a la operación.\n\t                                ")])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"modal-footer"},[a("button",{staticClass:"btn btn-default btn-sm btn-round btn-modal-close",attrs:{type:"button","data-dismiss":"modal"}},[this._v("\n                \t\tCerrar\n                \t")])])}],!1,null,null,null);a.default=n.exports}}]);