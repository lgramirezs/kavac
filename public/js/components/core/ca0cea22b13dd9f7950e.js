(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{"KHd+":function(t,e,a){"use strict";function i(t,e,a,i,s,r,o,n){var c,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=a,d._compiled=!0),i&&(d.functional=!0),r&&(d._scopeId="data-v-"+r),o?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},d._ssrRegister=c):s&&(c=n?function(){s.call(this,this.$root.$options.shadowRoot)}:s),c)if(d.functional){d._injectStyles=c;var l=d.render;d.render=function(t,e){return c.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:d}}a.d(e,"a",(function(){return i}))},qw14:function(t,e,a){"use strict";a.r(e);var i={data:function(){return{record:{id:"",institution_id:"",parent_id:"",acronym:"",name:"",issue_requests:!1,active:!1,administrative:!1},institutions:[],departments:[],errors:[],records:[],columns:["institution.acronym","parent.name","acronym","name","active","id"]}},methods:{reset:function(){this.record={id:"",institution_id:"",parent_id:"",acronym:"",name:"",issue_requests:!1,active:!1,administrative:!1}}},created:function(){this.table_options.headings={"institution.acronym":"Institución","parent.name":"Depende de",acronym:"Siglas",name:"Nombre",active:"Activo",id:"Acción"},this.table_options.sortable=["institution.acronym","parent.name","acronym","name"],this.table_options.filterable=["institution.acronym","parent.name","acronym","name"],this.table_options.columnsClasses={"institution.acronym":"col-md-2","parent.name":"col-md-2",acronym:"col-md-2",name:"col-md-3",active:"col-md-1",id:"col-md-2"}},mounted:function(){var t=this;$("#add_department").on("show.bs.modal",(function(){t.getInstitutions(),t.getDepartments()})),t.switchHandler("issue_requests"),t.switchHandler("active"),t.switchHandler("administrative")}},s=a("KHd+"),r=Object(s.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-xs-2 text-center"},[a("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registros de unidades, departamentos o dependencias","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_department","departments",e)}}},[a("i",{staticClass:"icofont icofont-architecture-alt ico-3x"}),t._v(" "),a("span",[t._v("Unidades / Dependencias")])]),t._v(" "),a("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_department"}},[a("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[a("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),a("div",{staticClass:"modal-body"},[t.errors.length>0?a("div",{staticClass:"alert alert-danger"},[a("ul",t._l(t.errors,(function(e){return a("li",[t._v(t._s(e))])})),0)]):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Institución:")]),t._v(" "),a("select2",{attrs:{options:t.institutions,title:"Institución a la cual pertenece","data-toggle":"tooltip"},on:{input:function(e){return t.getDepartments()}},model:{value:t.record.institution_id,callback:function(e){t.$set(t.record,"institution_id",e)},expression:"record.institution_id"}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})],1)]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group"},[a("label",[t._v("Depende de:")]),t._v(" "),a("select2",{attrs:{options:t.departments,title:"Unidad, Departamento o dependencia de la cual depende. No seleccionar si no es subordinada a otra dependencia","data-toggle":"tooltip"},model:{value:t.record.parent_id,callback:function(e){t.$set(t.record,"parent_id",e)},expression:"record.parent_id"}})],1)]),t._v(" "),a("div",{staticClass:"col-md-2"},[a("div",{staticClass:"form-group"},[a("label",[t._v("Siglas:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.acronym,expression:"record.acronym"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",title:"Siglas o acrónimo para el departamento (si posee)",placeholder:"SIGLAS"},domProps:{value:t.record.acronym},on:{input:function(e){e.target.composing||t.$set(t.record,"acronym",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Nombre:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",placeholder:"Nombre de la unidad, departamento o dependencia"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-2"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Solicita almacén:")]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"bootstrap-switch-mini"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.issue_requests,expression:"record.issue_requests"}],staticClass:"form-control bootstrap-switch",attrs:{type:"checkbox","data-toggle":"tooltip","data-on-label":"SI","data-off-label":"NO",title:"Indique si puede emitir solicitudes de almacén",value:"true",name:"issue_requests"},domProps:{checked:Array.isArray(t.record.issue_requests)?t._i(t.record.issue_requests,"true")>-1:t.record.issue_requests},on:{change:function(e){var a=t.record.issue_requests,i=e.target,s=!!i.checked;if(Array.isArray(a)){var r=t._i(a,"true");i.checked?r<0&&t.$set(t.record,"issue_requests",a.concat(["true"])):r>-1&&t.$set(t.record,"issue_requests",a.slice(0,r).concat(a.slice(r+1)))}else t.$set(t.record,"issue_requests",s)}}})])])])]),t._v(" "),a("div",{staticClass:"col-md-2"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Activo:")]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"bootstrap-switch-mini"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.active,expression:"record.active"}],staticClass:"form-control bootstrap-switch",attrs:{type:"checkbox",name:"active","data-toggle":"tooltip",title:"Indique si se encuentra activo","data-on-label":"SI","data-off-label":"NO",value:"true"},domProps:{checked:Array.isArray(t.record.active)?t._i(t.record.active,"true")>-1:t.record.active},on:{change:function(e){var a=t.record.active,i=e.target,s=!!i.checked;if(Array.isArray(a)){var r=t._i(a,"true");i.checked?r<0&&t.$set(t.record,"active",a.concat(["true"])):r>-1&&t.$set(t.record,"active",a.slice(0,r).concat(a.slice(r+1)))}else t.$set(t.record,"active",s)}}})])])])]),t._v(" "),a("div",{staticClass:"col-md-2"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Administrativo:")]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"bootstrap-switch-mini"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.administrative,expression:"record.administrative"}],staticClass:"form-control bootstrap-switch",attrs:{type:"checkbox","data-toggle":"tooltip",title:"Indique si la unidad, departamento o dependencia es del área administrativa",name:"administrative","data-on-label":"SI","data-off-label":"NO",value:"true"},domProps:{checked:Array.isArray(t.record.administrative)?t._i(t.record.administrative,"true")>-1:t.record.administrative},on:{change:function(e){var a=t.record.administrative,i=e.target,s=!!i.checked;if(Array.isArray(a)){var r=t._i(a,"true");i.checked?r<0&&t.$set(t.record,"administrative",a.concat(["true"])):r>-1&&t.$set(t.record,"administrative",a.slice(0,r).concat(a.slice(r+1)))}else t.$set(t.record,"administrative",s)}}})])])])])])]),t._v(" "),a("div",{staticClass:"modal-footer"},[a("div",{staticClass:"form-group"},[a("modal-form-buttons",{attrs:{saveRoute:"departments"}})],1)]),t._v(" "),a("div",{staticClass:"modal-body modal-table"},[a("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"parent.name",fn:function(e){return a("div",{},[e.row.parent?a("span",[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(e.row.parent.name)+"\n\t\t\t\t\t\t\t\t")]):a("span",[t._v("N/A")])])}},{key:"active",fn:function(e){return a("div",{},[e.row.active?a("span",[t._v("SI")]):a("span",[t._v("NO")])])}},{key:"id",fn:function(e){return a("div",{staticClass:"text-center"},[a("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.initUpdate(e.row.id,a)}}},[a("i",{staticClass:"fa fa-edit"})]),t._v(" "),a("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.deleteRecord(e.row.id,"departments")}}},[a("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-architecture-alt inline-block"}),this._v("\n\t\t\t\t\t\t\tUnidades / Dependencias\n\t\t\t\t\t\t")])])}],!1,null,null,null);e.default=r.exports}}]);