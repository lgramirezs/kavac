(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{wrZZ:function(t,e,a){"use strict";a.r(e);var s={data:function(){return{record:{id:"",asset_type_id:"",name:"",code:""},errors:[],records:[],asset_types:[],columns:["asset_type.name","name","code","id"]}},created:function(){this.table_options.headings={"asset_type.name":"Tipo de Bien",name:"Categoria General",code:"Código",id:"Acción"},this.table_options.sortable=["asset_type.name","name","code"],this.table_options.filterable=["asset_type.name","name","code"],this.table_options.columnsClasses={"asset_type.name":"col-xs-2",name:"col-xs-6",code:"col-xs-2",id:"col-xs-2"}},mounted:function(){this.getAssetTypes()},methods:{reset:function(){this.record={id:"",asset_type_id:"",name:"",code:""}}}},o=a("KHd+"),i=Object(o.a)(s,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-xs-2 text-center"},[a("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"#",title:"Registros de Categorias Generales de Bienes","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_category","categories",e)}}},[a("i",{staticClass:"icofont icofont-read-book ico-3x"}),t._v(" "),t._m(0)]),t._v(" "),a("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_category"}},[a("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[a("div",{staticClass:"modal-content"},[t._m(1),t._v(" "),a("div",{staticClass:"modal-body"},[t.errors.length>0?a("div",{staticClass:"alert alert-danger"},[a("div",{staticClass:"container"},[t._m(2),t._v(" "),a("strong",[t._v("Cuidado!")]),t._v(" Debe verificar los siguientes errores antes de continuar:\n                                "),a("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"alert","aria-label":"Close"},on:{click:function(e){e.preventDefault(),t.errors=[]}}},[t._m(3)]),t._v(" "),a("ul",t._l(t.errors,(function(e){return a("li",[t._v(t._s(e))])})),0)])]):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group"},[a("label",[t._v("Tipo de Bien:")]),t._v(" "),a("select2",{attrs:{options:t.asset_types},model:{value:t.record.asset_type_id,callback:function(e){t.$set(t.record,"asset_type_id",e)},expression:"record.asset_type_id"}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})],1)]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Código de la Categoría General:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.code,expression:"record.code"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Código de Categoría General","data-toggle":"tooltip",title:"Indique el código de la nueva Categoría General (requerido)"},domProps:{value:t.record.code},on:{input:function(e){e.target.composing||t.$set(t.record,"code",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Categoría General:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Nueva Categoría General","data-toggle":"tooltip",title:"Indique la nueva Categoría General (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}})])])])]),t._v(" "),a("div",{staticClass:"modal-footer"},[a("div",{staticClass:"form-group"},[a("modal-form-buttons",{attrs:{saveRoute:"asset/categories"}})],1)]),t._v(" "),a("div",{staticClass:"modal-body modal-table"},[a("hr"),t._v(" "),a("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return a("div",{staticClass:"text-center"},[a("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.initUpdate(e.row.id,a)}}},[a("i",{staticClass:"fa fa-edit"})]),t._v(" "),a("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.deleteRecord(e.row.id,"categories")}}},[a("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("span",[this._v("Categorias"),e("br"),this._v("Generales")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-read-book ico-2x"}),this._v("\n\t\t\t\t\t\t\tNueva Categoria General de Bienes\n\t\t\t\t\t\t")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"alert-icon"},[e("i",{staticClass:"now-ui-icons objects_support-17"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{attrs:{"aria-hidden":"true"}},[e("i",{staticClass:"now-ui-icons ui-1_simple-remove"})])}],!1,null,null,null);e.default=i.exports}}]);