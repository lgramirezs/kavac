(window.webpackJsonp=window.webpackJsonp||[]).push([[17],{TR7U:function(t,e,o){"use strict";o.r(e);var i={data:function(){return{record:{id:"",code:"",created_at:"",type_operation:"",description:""},records:[],columns:["created_at","description","id"]}},created:function(){this.table_options.headings={created_at:"Fecha de operación",description:"Descripción",id:"Acción"},this.table_options.sortable=["created_at","description"],this.table_options.filterable=["created_at","description"]},mounted:function(){this.initRecords(this.route_list,"")},methods:{reset:function(){this.record={id:"",code:"",created_at:"",type_operation:"",description:""}},showReport:function(t,e){this.record=this.records[t-1];var o="/asset/dashboard/get-operation/"+this.record.type_operation+"/"+this.record.code;e.preventDefault(),axios.get(o).then((function(t){0==t.data.result?location.href=t.data.redirect:t.data.redirect}))}}},r=o("KHd+"),n=Object(r.a)(i,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return o("div",{staticClass:"text-center"},[o("div",{staticClass:"d-inline-flex"},[o("asset-operations-history-info",{attrs:{route_list:"/asset/dashboard/operations/info",operation:e.row}}),t._v(" "),o("button",{staticClass:"btn btn-primary btn-xs btn-icon btn-action",attrs:{title:"Abrir reporte de operación","data-toggle":"tooltip",type:"button"},on:{click:function(o){return t.showReport(e.index,o)}}},[o("i",{staticClass:"fa fa-file-pdf-o"})])],1)])}}])})}),[],!1,null,null,null);e.default=n.exports}}]);