(window.webpackJsonp=window.webpackJsonp||[]).push([[18],{"KHd+":function(t,e,i){"use strict";function o(t,e,i,o,n,s,a,r){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=i,l._compiled=!0),o&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),a?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),n&&n.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},l._ssrRegister=c):n&&(c=r?function(){n.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:n),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(t,e){return c.call(e),d(t,e)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:l}}i.d(e,"a",(function(){return o}))},wvVC:function(t,e,i){"use strict";i.r(e);var o={data:function(){return{record:{id:"",acronym:"",name:""},errors:[],records:[],columns:["acronym","name","id"]}},methods:{reset:function(){this.record={id:"",acronym:"",name:""}}},created:function(){this.table_options.headings={acronym:"Acrónimo",name:"Nombre",id:"Acción"},this.table_options.sortable=["name","acronym"],this.table_options.filterable=["name","acronym"],this.table_options.columnsClasses={acronym:"col-md-2",name:"col-md-8",id:"col-md-2"}}},n=i("KHd+"),s=Object(n.a)(o,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"col-xs-2 text-center"},[i("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registros de tipos de instituciones","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_institution_type","institution-types",e)}}},[i("i",{staticClass:"icofont icofont-building-alt ico-3x"}),t._v(" "),t._m(0)]),t._v(" "),i("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_institution_type"}},[i("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[i("div",{staticClass:"modal-content"},[t._m(1),t._v(" "),i("div",{staticClass:"modal-body"},[t.errors.length>0?i("div",{staticClass:"alert alert-danger"},[i("ul",t._l(t.errors,(function(e){return i("li",[t._v(t._s(e))])})),0)]):t._e(),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-6"},[i("div",{staticClass:"form-group"},[i("label",[t._v("Acrónimo:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.acronym,expression:"record.acronym"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Acrónimo","data-toggle":"tooltip",title:"Indique el acrónimo del tipo de institución"},domProps:{value:t.record.acronym},on:{input:function(e){e.target.composing||t.$set(t.record,"acronym",e.target.value)}}}),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})])]),t._v(" "),i("div",{staticClass:"col-md-6"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Nombre:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Tipo","data-toggle":"tooltip",title:"Indique el nombre del tipo de institución (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}})])])])]),t._v(" "),i("div",{staticClass:"modal-footer"},[i("div",{staticClass:"form-group"},[i("modal-form-buttons",{attrs:{saveRoute:"institution-types"}})],1)]),t._v(" "),i("div",{staticClass:"modal-body modal-table"},[i("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return i("div",{staticClass:"text-center"},[i("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.initUpdate(e.row.id,i)}}},[i("i",{staticClass:"fa fa-edit"})]),t._v(" "),i("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(i){return t.deleteRecord(e.row.id,"institution-types")}}},[i("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("span",[this._v("Tipo"),e("br"),this._v("Instituciones")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-building-alt inline-block"}),this._v("\n\t\t\t\t\t\tTipo de Institución\n\t\t\t\t\t")])])}],!1,null,null,null);e.default=s.exports}}]);