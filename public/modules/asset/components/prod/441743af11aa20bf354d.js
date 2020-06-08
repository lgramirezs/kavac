(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{nbb9:function(t,a,i){"use strict";i.r(a);var e={data:function(){return{records:[],search:"",page:1,total:"",perPage:10,lastPage:"",pageValues:[1,2,3,4,5,6,7,8,9,10],perPageValues:[{id:10,text:"10"},{id:25,text:"25"},{id:50,text:"50"}],columns:["inventory_serial","institution","asset_condition","asset_status","serial","marca","model","id"]}},created:function(){this.table_options.headings={inventory_serial:"Código",institution:"Institución",asset_condition:"Condición Física",asset_status:"Estatus de Uso",serial:"Serial",marca:"Marca",model:"Modelo",id:"Acción"},this.table_options.sortable=["inventory_serial","institution","asset_condition","asset_status","serial","marca","model"],this.table_options.filterable=["inventory_serial","institution","asset_condition","asset_status","serial","marca","model"],this.table_options.orderBy={column:"id"}},mounted:function(){this.initRecords(this.route_list,"")},watch:{perPage:function(t){1==this.page?this.initRecords(this.route_list+"/"+t,""):this.changePage(1)},page:function(t){this.initRecords(this.route_list+"/"+this.perPage+"/"+t,"")}},methods:{reset:function(){},assignAsset:function(t){location.href="/asset/asignations/asset/"+t},disassignAsset:function(t){location.href="/asset/disincorporations/asset/"+t},changePage:function(t){this.page=t;for(var a=0;;){if(a+10>=this.page){a+=1;break}a+=10}this.pageValues=[];for(var i=0;i<10;i++)this.pageValues.push(a+i)},initRecords:function(t,a){this.errors=[],this.reset();var i=this;axios.get(t).then((function(t){void 0!==t.data.records&&(i.records=t.data.records,i.total=t.data.total,i.lastPage=t.data.lastPage,i.$refs.tableMax.setLimit(i.perPage)),$("#"+a).length&&$("#"+a).modal("show")})).catch((function(t){void 0!==t.response&&(403==t.response.status?i.showMessage("custom","Acceso Denegado","danger","screen-error",t.response.data.message):i.logs("resources/js/all.js",343,t,"initRecords"))}))}}},s=i("KHd+"),n=Object(s.a)(e,(function(){var t=this,a=t.$createElement,i=t._self._c||a;return i("section",[i("div",{staticClass:"form-group form-inline pull-left VueTables__search-2"},[i("div",{staticClass:"VueTables__search-field"},[i("label",{},[t._v("\n\t\t\t\t\tBuscar:\n\t\t\t\t")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.search,expression:"search"}],staticClass:"form-control",attrs:{type:"text",placeholder:"Buscar..."},domProps:{value:t.search},on:{input:function(a){a.target.composing||(t.search=a.target.value)}}})])]),t._v(" "),i("div",{staticClass:"form-group form-inline pull-right VueTables__limit-2"},[i("div",{staticClass:"VueTables__limit-field"},[i("label",{},[t._v("Registros")]),t._v(" "),i("select2",{attrs:{options:t.perPageValues},model:{value:t.perPage,callback:function(a){t.perPage=a},expression:"perPage"}})],1)]),t._v(" "),i("v-client-table",{ref:"tableMax",attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"institution",fn:function(a){return i("div",{staticClass:"text-center"},[i("span",[t._v("\n\t\t\t\t\t"+t._s(a.row.institution?a.row.institution.acronym:"N/A")+"\n\t\t\t\t")])])}},{key:"asset_condition",fn:function(a){return i("div",{staticClass:"text-center"},[i("span",[t._v("\n\t\t\t\t\t"+t._s(a.row.asset_condition?a.row.asset_condition.name:"N/A")+"\n\t\t\t\t")])])}},{key:"asset_status",fn:function(a){return i("div",{staticClass:"text-center"},[i("span",[t._v("\n\t\t\t\t\t"+t._s(a.row.asset_status?a.row.asset_status.name:"N/A")+"\n\t\t\t\t")])])}},{key:"id",fn:function(a){return i("div",{staticClass:"text-center"},[i("div",{staticClass:"d-inline-flex"},[i("asset-info",{attrs:{route_list:"registers/info/"+a.row.id}}),t._v(" "),i("button",{staticClass:"btn btn-primary btn-xs btn-icon btn-action",attrs:{title:"Asignar Bien","data-toggle":"tooltip",disabled:10!=a.row.asset_status_id,type:"button"},on:{click:function(i){return t.assignAsset(a.row.id)}}},[i("i",{staticClass:"fa fa-filter"})]),t._v(" "),i("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Desincorporar Bien","data-toggle":"tooltip",disabled:!(a.row.asset_status_id<6||a.row.asset_status_id>9),type:"button"},on:{click:function(i){return t.disassignAsset(a.row.id)}}},[i("i",{staticClass:"fa fa-chain"})]),t._v(" "),i("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.editForm(a.row.id)}}},[i("i",{staticClass:"fa fa-edit"})]),t._v(" "),i("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.deleteRecord(a.index,"")}}},[i("i",{staticClass:"fa fa-trash-o"})])],1)])}}])}),t._v(" "),i("div",{staticClass:"VuePagination-2 row col-md-12 "},[i("nav",{staticClass:"text-center"},[i("ul",{staticClass:"pagination VuePagination__pagination"},[1!=t.page?i("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[i("a",{staticClass:"page-link",on:{click:function(a){return t.changePage(1)}}},[t._v("PRIMERO")])]):t._e(),t._v(" "),t._m(0),t._v(" "),t.page>1?i("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-page"},[i("a",{staticClass:"page-link",on:{click:function(a){return t.changePage(t.page-1)}}},[t._v("<")])]):t._e(),t._v(" "),t._l(t.pageValues,(function(a){return a<=t.lastPage?i("li",{class:t.page==a?"VuePagination__pagination-item page-item active":"VuePagination__pagination-item page-item"},[i("a",{staticClass:"page-link active",attrs:{role:"button"},on:{click:function(i){return t.changePage(a)}}},[t._v(t._s(a))])]):t._e()})),t._v(" "),t.page<t.lastPage?i("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-page"},[i("a",{staticClass:"page-link",on:{click:function(a){return t.changePage(t.page+1)}}},[t._v(">")])]):t._e(),t._v(" "),t._m(1),t._v(" "),t.lastPage!=t.page?i("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[i("a",{staticClass:"page-link",on:{click:function(a){return t.changePage(t.lastPage)}}},[t._v("ÚLTIMO")])]):t._e()],2),t._v(" "),i("p",{staticClass:"VuePagination__count text-center col-md-12"})])])],1)}),[function(){var t=this.$createElement,a=this._self._c||t;return a("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk disabled"},[a("a",{staticClass:"page-link"},[this._v("<<")])])},function(){var t=this.$createElement,a=this._self._c||t;return a("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-chunk disabled"},[a("a",{staticClass:"page-link"},[this._v(">>")])])}],!1,null,null,null);a.default=n.exports}}]);