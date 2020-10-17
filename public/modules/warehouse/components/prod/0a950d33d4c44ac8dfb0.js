(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{"FV/6":function(t,e,s){"use strict";s.r(e);var r={data:function(){return{record:{id:"",warehouse_product_id:"",warehouse_id:"",type_search:"",institution_id:"",mes_id:"",year:"",start_date:"",end_date:""},warehouses:[],warehouse_products:[],records:[],errors:[],columns:["code","description","inventory"],mes:[{id:"",text:"Todos"},{id:1,text:"Enero"},{id:2,text:"Febrero"},{id:3,text:"Marzo"},{id:4,text:"Abril"},{id:5,text:"Mayo"},{id:6,text:"Junio"},{id:7,text:"Julio"},{id:8,text:"Agosto"},{id:9,text:"Septiempre"},{id:10,text:"Octubre"},{id:11,text:"Noviembre"},{id:12,text:"Diciembre"}],institutions:[]}},methods:{reset:function(){this.record={id:"",warehouse_product_id:"",warehouse_id:"",type_search:"",institution_id:"",mes_id:"",year:"",start_date:"",end_date:""}},getWarehouseProducts:function(){var t=this;axios.get("/warehouse/get-warehouse-products").then(function(e){t.warehouse_products=e.data})},createReport:function(t){var e=this;e.loading=!0;var s={};for(var r in this.record)s[r]=this.record[r];s.current=t,axios.post("/warehouse/reports/inventory-products/create",s).then(function(t){0==t.data.result?location.href=t.data.redirect:void 0!==t.data.redirect?window.open(t.data.redirect,"_blank"):e.reset(),e.loading=!1}).catch(function(t){void 0!==t.response&&console.log("error"),e.loading=!1})},loadInventoryProduct:function(t){var e=this;e.loading=!0;var s={};for(var r in this.record)s[r]=this.record[r];s.current=t,axios.post("/warehouse/reports/inventory-products/vue-list",s).then(function(t){void 0!==t.data.records&&(e.records=t.data.records),e.loading=!1}).catch(function(t){void 0!==t.response&&console.log("error"),e.loading=!1})}},created:function(){this.table_options.headings={code:"Código",description:"Descripción",inventory:"Inventario"},this.table_options.sortable=["code","description","inventory"],this.table_options.filterable=["code","description","inventory"]},mounted:function(){this.switchHandler("type_search"),this.getInstitutions(),this.getWarehouseProducts(),this.getWarehouses(),this.loadInventoryProduct("inventory-products")}},o=s("KHd+"),i=Object(o.a)(r,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("section",{attrs:{id:"WarehouseReportForm"}},[s("div",{staticClass:"card-body"},[t.errors.length>0?s("div",{staticClass:"alert alert-danger"},[s("ul",t._l(t.errors,function(e){return s("li",[t._v(t._s(e))])}),0)]):t._e(),t._v(" "),s("div",{staticClass:"row"},[t._m(0),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Producto:")]),t._v(" "),s("select2",{attrs:{options:t.warehouse_products},model:{value:t.record.warehouse_product_id,callback:function(e){t.$set(t.record,"warehouse_product_id",e)},expression:"record.warehouse_product_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Institución:")]),t._v(" "),s("select2",{attrs:{options:t.institutions},model:{value:t.record.institution_id,callback:function(e){t.$set(t.record,"institution_id",e)},expression:"record.institution_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Almacén:")]),t._v(" "),s("select2",{attrs:{options:t.warehouses},model:{value:t.record.warehouse_id,callback:function(e){t.$set(t.record,"warehouse_id",e)},expression:"record.warehouse_id"}})],1)])]),t._v(" "),t._m(1),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:"mes"==this.record.type_search,expression:"this.record.type_search == 'mes'"}]},[s("div",{staticClass:"row justify-content-center"},[s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Mes:")]),t._v(" "),s("select2",{attrs:{options:t.mes},model:{value:t.record.mes_id,callback:function(e){t.$set(t.record,"mes_id",e)},expression:"record.mes_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Año:")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.year,expression:"record.year"}],staticClass:"form-control input-sm",attrs:{type:"number","data-toggle":"tooltip",min:"0",title:"Indique el año de busqueda"},domProps:{value:t.record.year},on:{input:function(e){e.target.composing||t.$set(t.record,"year",e.target.value)}}})])])])]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:"date"==this.record.type_search,expression:"this.record.type_search == 'date'"}]},[s("div",{staticClass:"row justify-content-center"},[s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Desde:")]),t._v(" "),s("div",{staticClass:"input-group input-sm"},[t._m(2),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.start_date,expression:"record.start_date"}],staticClass:"form-control input-sm",attrs:{type:"date","data-toggle":"tooltip",title:"Indique la fecha minima de busqueda"},domProps:{value:t.record.start_date},on:{input:function(e){e.target.composing||t.$set(t.record,"start_date",e.target.value)}}})])])]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Hasta:")]),t._v(" "),s("div",{staticClass:"input-group input-sm"},[t._m(3),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.end_date,expression:"record.end_date"}],staticClass:"form-control input-sm",attrs:{type:"date","data-toggle":"tooltip",title:"Indique la fecha maxima de busqueda"},domProps:{value:t.record.end_date},on:{input:function(e){e.target.composing||t.$set(t.record,"end_date",e.target.value)}}})])])])])]),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-12"},[s("button",{staticClass:"btn btn-sm btn-info float-right",attrs:{type:"button",title:"Buscar registro","data-toggle":"tooltip"},on:{click:function(e){return t.loadInventoryProduct("inventory-products")}}},[s("i",{staticClass:"fa fa-search"})])])]),t._v(" "),s("hr"),t._v(" "),s("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"description",fn:function(e){return s("div",{},[s("span",[s("b",[t._v(" "+t._s(e.row.warehouse_product?"Nombre: ":"")+" ")]),t._v("\n\t\t\t\t\t\t\t"+t._s(e.row.warehouse_product?e.row.warehouse_product.name+".":"")+" "),s("br"),t._v("\n\t\t\t\t\t\t"+t._s(e.row.warehouse_product?e.row.warehouse_product.description:"")+" "),s("br")]),t._v(" "),s("span",[t._l(e.row.warehouse_product_values,function(e){return s("div",[s("b",[t._v(t._s(e.warehouse_product_attribute.name+": "))]),t._v(" "+t._s(e.value)+" "),s("br")])}),t._v(" "),s("b",[t._v("Valor:")]),t._v(" "+t._s(e.row.unit_value)+" "+t._s(e.row.currency?e.row.currency.acronym:"")+"\n\t\t\t\t\t")],2)])}},{key:"inventory",fn:function(e){return s("div",{},[s("span",[s("b",[t._v("Almacén:")]),t._v(" "+t._s(e.row.warehouse_institution_warehouse.warehouse.name)+" "),s("br"),t._v(" "),s("b",[t._v("Existencia:")]),t._v(" "+t._s(e.row.exist)),s("br"),t._v(" "),s("b",[t._v("Reservados:")]),t._v(" "+t._s(null===e.row.reserved?"0":e.row.reserved)+"\n\t\t\t\t\t")])])}}])})],1),t._v(" "),s("div",{staticClass:"card-footer text-right"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-3 offset-md-9",attrs:{id:"helpParamButtons"}},[s("button",{staticClass:"btn btn-sm btn-primary btn-custom",attrs:{type:"button"},on:{click:function(e){return t.createReport("inventory-products")}}},[s("i",{staticClass:"fa fa-file-pdf-o"}),t._v(" "),s("span",[t._v("Generar Reporte")])])])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-md-12"},[e("strong",[this._v("Filtros")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row text-center"},[e("div",{staticClass:"col-md-6"},[e("div",{staticClass:"form-group"},[e("label",[this._v("Busqueda por Periodo")]),this._v(" "),e("div",{staticClass:"col-12"},[e("div",{staticClass:"col-12 bootstrap-switch-mini"},[e("input",{staticClass:"form-control bootstrap-switch bootstrap-switch-mini sel_type_search",attrs:{type:"radio",name:"type_search",value:"date",id:"sel_search_date","data-on-label":"SI","data-off-label":"NO"}})])])])]),this._v(" "),e("div",{staticClass:"col-md-6"},[e("div",{staticClass:" form-group"},[e("label",[this._v("Busqueda por Mes")]),this._v(" "),e("div",{staticClass:"col-12"},[e("div",{staticClass:"col-12 bootstrap-switch-mini"},[e("input",{staticClass:"form-control bootstrap-switch bootstrap-switch-mini sel_type_search",attrs:{type:"radio",name:"type_search",value:"mes",id:"sel_search_mes","data-on-label":"SI","data-off-label":"NO"}})])])])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon"},[e("i",{staticClass:"now-ui-icons ui-1_calendar-60"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon"},[e("i",{staticClass:"now-ui-icons ui-1_calendar-60"})])}],!1,null,null,null);e.default=i.exports},"KHd+":function(t,e,s){"use strict";function r(t,e,s,r,o,i,a,n){var d,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=s,c._compiled=!0),r&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),a?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},c._ssrRegister=d):o&&(d=n?function(){o.call(this,this.$root.$options.shadowRoot)}:o),d)if(c.functional){c._injectStyles=d;var l=c.render;c.render=function(t,e){return d.call(e),l(t,e)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,d):[d]}return{exports:t,options:c}}s.d(e,"a",function(){return r})}}]);