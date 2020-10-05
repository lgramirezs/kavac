(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{"KHd+":function(t,e,a){"use strict";function r(t,e,a,r,i,o,s,l){var n,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=a,c._compiled=!0),r&&(c.functional=!0),o&&(c._scopeId="data-v-"+o),s?(n=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},c._ssrRegister=n):i&&(n=l?function(){i.call(this,this.$root.$options.shadowRoot)}:i),n)if(c.functional){c._injectStyles=n;var d=c.render;c.render=function(t,e){return n.call(e),d(t,e)}}else{var p=c.beforeCreate;c.beforeCreate=p?[].concat(p,n):[n]}return{exports:t,options:c}}a.d(e,"a",function(){return r})},"jD+S":function(t,e,a){"use strict";a.r(e);var r={data:function(){return{record:{id:"",name:"",code:"",acronym:"",description:"",parameter_type:"",percentage:"",value:"",formula:""},variable:"",variable_option:"",errors:[],records:[],columns:["name","code","acronym","description","id"],options:[],parameter_types:[]}},created:function(){this.table_options.headings={name:"Nombre",code:"Código",acronym:"Acrónimo",description:"Descripción",id:"Acción"},this.table_options.sortable=["name","code","acronym","description"],this.table_options.filterable=["name","code","acronym","description"],this.table_options.columnsClasses={name:"col-xs-2",code:"col-xs-2",acronym:"col-xs-2",description:"col-xs-4",id:"col-xs-2"}},mounted:function(){var t=this;$("#add_payroll_parameter").on("show.bs.modal",function(){t.reset(),t.getPayrollParameterTypes(),t.getOptions("payroll/get-associated-records")}),$(".btn-formula").on("click",function(){t.record.formula+=$(this).data("value")})},watch:{variable:function(t){"parameter"==this.variable?this.getOptions("payroll/get-parameters"):"worker_record"==this.variable&&this.getOptions("payroll/get-associated-records")}},computed:{updateNameVariable:function(){var t=this,e="";return""!=t.variable_option&&t.options.forEach(function(a,r){a.id==t.variable_option?e=a.text:void 0!==a.children&&a.children.forEach(function(a,r){a.id==t.variable_option&&(e=a.text)})}),e}},methods:{reset:function(){this.errors=[],this.record={id:"",name:"",code:"",acronym:"",description:"",parameter_type:"",percentage:"",value:"",formula:""},this.variable=""},changeParameterType:function(){var t=this;"processed_variable"==t.record.parameter_type?t.variable="":"global_value"==t.record.parameter_type?(t.variable="",t.record.formula=""):"resettable_variable"==t.record.parameter_type&&(t.variable="",t.record.formula="",t.record.percentage="")},getPayrollParameterTypes:function(){var t=this;t.parameter_types=[],axios.get("/payroll/get-parameter-types").then(function(e){t.parameter_types=e.data})},getOptions:function(t){var e=this;e.options=[],axios.get("/"+t).then(function(t){e.options=t.data})},getAcronymVariable:function(){var t=this,e="";""!=t.variable_option&&t.options.forEach(function(a,r){a.id==t.variable_option?void 0!==a.acronym?e=a.acronym:void 0!==a.id&&(e=a.id):void 0!==a.children&&a.children.forEach(function(a,r){a.id==t.variable_option&&(void 0!==a.acronym?e=a.acronym:void 0!==a.id&&(e=a.id))})}),""!=e&&(t.record.formula+=e)}}},i=a("KHd+"),o=Object(i.a)(r,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",{attrs:{id:"payrollParametersFormComponent"}},[a("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"",title:"Registros de parámetros","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_payroll_parameter","parameters",e)}}},[a("i",{staticClass:"icofont icofont-globe ico-3x"}),t._v(" "),t._m(0)]),t._v(" "),a("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_payroll_parameter"}},[a("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[a("div",{staticClass:"modal-content"},[t._m(1),t._v(" "),a("div",{staticClass:"modal-body"},[t.errors.length>0?a("div",{staticClass:"alert alert-danger"},[a("div",{staticClass:"container"},[t._m(2),t._v(" "),a("strong",[t._v("Cuidado!")]),t._v(" Debe verificar los siguientes errores antes de continuar:\n                            "),a("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"alert","aria-label":"Close"},on:{click:function(e){e.preventDefault(),t.errors=[]}}},[t._m(3)]),t._v(" "),a("ul",t._l(t.errors,function(e){return a("li",[t._v(t._s(e))])}),0)])]):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"parameter_type"}},[t._v("Tipo de parámetro:")]),t._v(" "),a("select2",{attrs:{options:t.parameter_types},on:{input:function(e){return t.changeParameterType()}},model:{value:t.record.parameter_type,callback:function(e){t.$set(t.record,"parameter_type",e)},expression:"record.parameter_type"}})],1)]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"name"}},[t._v("Nombre:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"name",placeholder:"Nombre","data-toggle":"tooltip",title:"Indique el nombre del parámetro (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden",name:"id",id:"id"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"code"}},[t._v("Código:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.code,expression:"record.code"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"code",placeholder:"Código","data-toggle":"tooltip",title:"Indique el código del parámetro (requerido)"},domProps:{value:t.record.code},on:{input:function(e){e.target.composing||t.$set(t.record,"code",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"acronym"}},[t._v("Acrónimo:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.acronym,expression:"record.acronym"}],staticClass:"form-control input-sm",attrs:{type:"text",id:"acronym",placeholder:"Acrónimo","data-toggle":"tooltip",title:"Indique el acrónimo del parámetro (requerido)"},domProps:{value:t.record.acronym},on:{input:function(e){e.target.composing||t.$set(t.record,"acronym",e.target.value)}}})])])])]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",{attrs:{for:"description"}},[t._v("Descripción:")]),t._v(" "),a("ckeditor",{staticClass:"form-control",attrs:{editor:t.ckeditor.editor,id:"description","data-toggle":"tooltip",title:"Indique la descripción del parámetro",config:t.ckeditor.editorConfig,name:"description","tag-name":"textarea"},model:{value:t.record.description,callback:function(e){t.$set(t.record,"description",e)},expression:"record.description"}})],1)])])]),t._v(" "),"global_value"==t.record.parameter_type?a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"value"}},[t._v("Valor:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.value,expression:"record.value"}],staticClass:"form-control input-sm",attrs:{type:"number",id:"value",placeholder:"Valor",min:"0",step:".01",onfocus:"this.select()","data-toggle":"tooltip",title:"Indique el valor del parámetro (requerido)"},domProps:{value:t.record.value},on:{input:function(e){e.target.composing||t.$set(t.record,"value",e.target.value)}}})])]):t._e(),t._v(" "),"global_value"==t.record.parameter_type?a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group"},[a("label",{attrs:{for:"percentage"}},[t._v("¿Porcentaje?")]),t._v(" "),a("div",{staticClass:"col-12"},[a("div",{staticClass:"pretty p-switch p-fill p-bigger p-toggle"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.percentage,expression:"record.percentage"}],attrs:{type:"checkbox","data-toggle":"tooltip",title:"Indique si el valor indicado está expresado en porcentaje (requerido)"},domProps:{checked:Array.isArray(t.record.percentage)?t._i(t.record.percentage,null)>-1:t.record.percentage},on:{change:function(e){var a=t.record.percentage,r=e.target,i=!!r.checked;if(Array.isArray(a)){var o=t._i(a,null);r.checked?o<0&&t.$set(t.record,"percentage",a.concat([null])):o>-1&&t.$set(t.record,"percentage",a.slice(0,o).concat(a.slice(o+1)))}else t.$set(t.record,"percentage",i)}}}),t._v(" "),t._m(4),t._v(" "),t._m(5)])])])]):t._e(),t._v(" "),"processed_variable"==t.record.parameter_type?a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"form-group is-required"},[a("label",{attrs:{for:"formula"}},[t._v("Fórmula:")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.formula,expression:"record.formula"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toggle":"tooltip",title:"Fórmula a aplicar para la variable. Utilice la siguiente calculadora para establecer los parámetros de la fórmula",readonly:""},domProps:{value:t.record.formula},on:{input:function(e){e.target.composing||t.$set(t.record,"formula",e.target.value)}}})]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-6 col-md-6"},[a("div",{staticClass:"form-group"},[a("label",{attrs:{for:"worker_record"}},[t._v("¿Expediente del Trabajador?")]),t._v(" "),a("div",{staticClass:"col-12"},[a("div",{staticClass:"pretty p-switch p-fill p-bigger p-toggle"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.variable,expression:"variable"}],attrs:{type:"radio","data-toggle":"tooltip",title:"Indique si desea utilizar una variable del expediente del Trabajador",value:"worker_record"},domProps:{checked:t._q(t.variable,"worker_record")},on:{change:function(e){t.variable="worker_record"}}}),t._v(" "),t._m(6),t._v(" "),t._m(7)])])])]),t._v(" "),a("div",{staticClass:"col-6 col-md-6"},[a("div",{staticClass:"form-group"},[a("label",{attrs:{for:"parameter"}},[t._v("¿Parámetro?")]),t._v(" "),a("div",{staticClass:"col-12"},[a("div",{staticClass:"pretty p-switch p-fill p-bigger p-toggle"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.variable,expression:"variable"}],attrs:{type:"radio","data-toggle":"tooltip",title:"Indique si desea utilizar un parámetro previamente registrado",value:"parameter"},domProps:{checked:t._q(t.variable,"parameter")},on:{change:function(e){t.variable="parameter"}}}),t._v(" "),t._m(8),t._v(" "),t._m(9)])])])]),t._v(" "),t.variable?a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("select2",{attrs:{options:t.options},model:{value:t.variable_option,callback:function(e){t.variable_option=e},expression:"variable_option"}})],1)]):t._e()])]):t._e(),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:"processed_variable"==t.record.parameter_type,expression:"record.parameter_type == 'processed_variable'"}],staticClass:"col-md-6"},[a("div",{staticClass:"form-group"},[t._m(10),t._v(" "),t._m(11),t._v(" "),t._m(12),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-sm-12 text-center"},[a("div",{staticClass:"btn btn-info btn-sm btn-formula-clear",attrs:{"data-toggle":"tooltip",title:"Reinicia el campo de la fórmula"},on:{click:function(e){t.record.formula=""}}},[t._v("C")]),t._v(" "),a("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"0"}},[t._v("0")]),t._v(" "),a("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar el separador de decimales","data-value":"."}},[t._v(".")]),t._v(" "),a("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar el signo de división","data-value":"/"}},[t._v("/")])])]),t._v(" "),t.variable_option?a("div",{staticClass:"row"},[a("div",{staticClass:"col-sm-12 col-btn-block text-center"},[a("div",{staticClass:"btn btn-info btn-sm",attrs:{"data-toggle":"tooltip",title:"Variable a usar cuando se realice el cálculo"},on:{click:function(e){return t.getAcronymVariable()}}},[t._v("\n                                            "+t._s(t.updateNameVariable)+"\n                                        ")])])]):t._e()])])])]),t._v(" "),a("div",{staticClass:"modal-footer"},[a("div",{staticClass:"form-group"},[a("modal-form-buttons",{attrs:{saveRoute:"payroll/parameters"}})],1)]),t._v(" "),a("div",{staticClass:"modal-body modal-table"},[a("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return a("div",{staticClass:"text-center"},[a("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.initUpdate(e.row.id,a)}}},[a("i",{staticClass:"fa fa-edit"})]),t._v(" "),a("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.deleteRecord(e.index,"parameters")}}},[a("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("span",[this._v("Parámetros"),e("br"),this._v("Globales")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-globe ico-3x"}),this._v("\n                        Parámetro Global de Nómina\n                    ")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"alert-icon"},[e("i",{staticClass:"now-ui-icons objects_support-17"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{attrs:{"aria-hidden":"true"}},[e("i",{staticClass:"now-ui-icons ui-1_simple-remove"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-off"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-on p-success"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-off"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-on p-success"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-off"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"state p-on p-success"},[e("label")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm-12 text-center"},[e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"1"}},[this._v("1")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"2"}},[this._v("2")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"3"}},[this._v("3")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar el signo de suma","data-value":"+"}},[this._v("+")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm-12 text-center"},[e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"4"}},[this._v("4")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"5"}},[this._v("5")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"6"}},[this._v("6")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar el signo de resta","data-value":"-"}},[this._v("-")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm-12 text-center"},[e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"7"}},[this._v("7")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"8"}},[this._v("8")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar este dígito","data-value":"9"}},[this._v("9")]),this._v(" "),e("div",{staticClass:"btn btn-info btn-sm btn-formula",attrs:{"data-toggle":"tooltip",title:"presione para agregar el signo de multiplicación","data-value":"*"}},[this._v("*")])])])}],!1,null,null,null);e.default=o.exports}}]);