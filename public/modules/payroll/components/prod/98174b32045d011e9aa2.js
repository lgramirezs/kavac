(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{"KHd+":function(a,t,l){"use strict";function r(a,t,l,r,e,s,o,i){var c,n="function"==typeof a?a.options:a;if(t&&(n.render=t,n.staticRenderFns=l,n._compiled=!0),r&&(n.functional=!0),s&&(n._scopeId="data-v-"+s),o?(c=function(a){(a=a||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(a=__VUE_SSR_CONTEXT__),e&&e.call(this,a),a&&a._registeredComponents&&a._registeredComponents.add(o)},n._ssrRegister=c):e&&(c=i?function(){e.call(this,this.$root.$options.shadowRoot)}:e),c)if(n.functional){n._injectStyles=c;var _=n.render;n.render=function(a,t){return c.call(t),_(a,t)}}else{var d=n.beforeCreate;n.beforeCreate=d?[].concat(d,c):[c]}return{exports:a,options:n}}l.d(t,"a",function(){return r})},jdaD:function(a,t,l){"use strict";l.r(t);var r={data:function(){return{record:{id:"",name:"",active:!1,description:"",institution_id:"",currency_id:"",payroll_salary_tabulator_type:"",payroll_staff_types:[],payroll_horizontal_salary_scale_id:"",payroll_vertical_salary_scale_id:"",payroll_salary_tabulator_scales:[]},errors:[],records:[],columns:["name","description","id"],payroll_salary_tabulator_types:[{id:"",text:"Seleccione..."},{id:"horizontal",text:"Horizontal"},{id:"vertical",text:"Vertical"},{id:"mixed",text:"Mixto"}],institutions:[],currencies:[],payroll_staff_types:[],payroll_horizontal_salary_scales:[],payroll_vertical_salary_scales:[],payroll_salary_scale_h:[],payroll_salary_scale_v:[],panel:"Form"}},created:function(){this.table_options.headings={name:"Nombre",description:"Descripción",id:"Acción"},this.table_options.sortable=["name","description"],this.table_options.filterable=["name","description"],this.table_options.columnsClasses={name:"col-xs-4",description:"col-xs-6",id:"col-xs-2"}},mounted:function(){var a=this;$("#add_payroll_salary_tabulator").on("show.bs.modal",function(){a.reset(),a.getPayrollStaffTypes(),a.getInstitutions(),a.getCurrencies()}),a.switchHandler("active")},watch:{record:{deep:!0,handler:function(){var a=this;a.record.id&&(a.payroll_horizontal_salary_scales.length>0&&a.record.payroll_horizontal_salary_scale&&""==a.record.payroll_horizontal_salary_scale_id&&(a.record.payroll_horizontal_salary_scale_id=a.record.payroll_horizontal_salary_scale.id),a.payroll_vertical_salary_scales.length>0&&a.record.payroll_vertical_salary_scale&&""==a.record.payroll_vertical_salary_scale_id&&(a.record.payroll_vertical_salary_scale_id=a.record.payroll_vertical_salary_scale.id))}}},methods:{changePanel:function(a){this.panel=a,document.getElementById("tabulador"+a).click()},isDisable:function(a){var t=this;"horizontal"==a?$.each(t.payroll_vertical_salary_scales,function(a,l){l.id==t.record.payroll_horizontal_salary_scale_id&&""!=t.record.payroll_horizontal_salary_scale_id?$("#"+l.id+"_v").prop("disabled",!0):$("#"+l.id+"_v").prop("disabled",!1)}):$.each(t.payroll_horizontal_salary_scales,function(a,l){l.id==t.record.payroll_vertical_salary_scale_id&&""!=t.record.payroll_vertical_salary_scale_id?$("#"+l.id+"_h").prop("disabled",!0):$("#"+l.id+"_h").prop("disabled",!1)})},reset:function(){var a=this;a.errors=[],a.record={id:"",name:"",active:!1,description:"",institution_id:"",currency_id:"",payroll_salary_tabulator_type:"",payroll_staff_types:[],payroll_horizontal_salary_scale_id:"",payroll_vertical_salary_scale_id:"",payroll_salary_tabulator_scales:[]},a.payroll_salary_scale_h=[],a.payroll_salary_scale_v=[],a.panel="Form",a.changePanel(a.panel)},exportRecord:function(a,t){var l=this.records[a-1];window.open("/payroll/salary-tabulators/export/"+l.id),t.preventDefault()},getPayrollSalaryScales:function(){var a=this,t={institution_id:a.record.institution_id,except_id:""};a.record.payroll_horizontal_salary_scale_id="",a.record.payroll_vertical_salary_scale_id="",""!=a.record.payroll_salary_tabulator_type&&axios.post("/payroll/get-salary-scales",t).then(function(t){void 0!==t.data&&(a.payroll_horizontal_salary_scales="vertical"==a.record.payroll_salary_tabulator_type?[]:t.data,a.payroll_vertical_salary_scales="horizontal"==a.record.payroll_salary_tabulator_type?[]:t.data)}).catch(function(t){void 0!==t.response&&(403==t.response.status?a.showMessage("custom","Acceso Denegado","danger","screen-error",t.response.data.message):a.logs("modules/Payroll/Resources/assets/js/_all.js",343,t,"getPayrollSalaryScales"))})},getPayrollSalary:function(a){var t=this,l={institution_id:t.record.institution_id,except_id:""};if("horizontal"==a){if(""==t.record.payroll_horizontal_salary_scale_id){if(""==t.record.payroll_vertical_salary_scale_id&&t.payroll_vertical_salary_scales.length==t.payroll_horizontal_salary_scales.length)return}else l=(t.record.payroll_vertical_salary_scale_id,{institution_id:t.record.institution_id,except_id:t.record.payroll_horizontal_salary_scale_id});axios.post("/payroll/get-salary-scales",l).then(function(a){void 0!==a.data&&(t.payroll_vertical_salary_scales=a.data)}).catch(function(a){void 0!==a.response&&(403==a.response.status?t.showMessage("custom","Acceso Denegado","danger","screen-error",a.response.data.message):t.logs("modules/Payroll/Resources/assets/js/_all.js",343,a,"getPayrollSalaryScales"))})}else{if(""==t.record.payroll_vertical_salary_scale_id){if(""==t.record.payroll_horizontal_salary_scale_id&&t.payroll_vertical_salary_scales.length==t.payroll_horizontal_salary_scales.length)return}else l=(t.record.payroll_horizontal_salary_scale_id,{institution_id:t.record.institution_id,except_id:t.record.payroll_vertical_salary_scale_id});axios.post("/payroll/get-salary-scales",l).then(function(a){void 0!==a.data&&(t.payroll_horizontal_salary_scales=a.data)}).catch(function(a){void 0!==a.response&&(403==a.response.status?t.showMessage("custom","Acceso Denegado","danger","screen-error",a.response.data.message):t.logs("modules/Payroll/Resources/assets/js/_all.js",343,a,"getPayrollSalaryScales"))})}},loadSalaryScales:function(){var a=this,t="";a.record.payroll_horizontal_salary_scale_id>0&&(t=a.record.payroll_horizontal_salary_scale_id,axios.get("/payroll/salary-scales/info/"+t).then(function(t){a.payroll_salary_scale_h=t.data.record})),a.record.payroll_vertical_salary_scale_id>0&&(t=a.record.payroll_vertical_salary_scale_id,axios.get("/payroll/salary-scales/info/"+t).then(function(t){a.payroll_salary_scale_v=t.data.record})),a.changePanel("Show")},createRecord:function(a){var t=this,l=[];if((t.record.payroll_horizontal_salary_scale_id>0&&t.payroll_salary_scale_h.payroll_scales.length>0||t.record.payroll_vertical_salary_scale_id>0&&t.payroll_salary_scale_v.payroll_scales.length>0)&&(""==t.record.payroll_vertical_salary_scale_id?$.each(t.payroll_salary_scale_h.payroll_scales,function(a,t){var r=document.getElementById("salary_scale_h_"+t.id);if(r){var e={payroll_horizontal_scale_id:t.id,payroll_horizontal_scale_code:t.code,value:r.value};l.push(e)}}):""==t.record.payroll_horizontal_salary_scale_id?$.each(t.payroll_salary_scale_v.payroll_scales,function(a,t){var r=document.getElementById("salary_scale_v_"+t.id);if(r){var e={payroll_vertical_scale_id:t.id,payroll_vertical_scale_code:t.code,value:r.value};l.push(e)}}):$.each(t.payroll_salary_scale_v.payroll_scales,function(a,r){$.each(t.payroll_salary_scale_h.payroll_scales,function(a,t){var e=document.getElementById("salary_scale_"+r.id+"_"+t.id);if(e){var s={payroll_vertical_scale_id:r.id,payroll_vertical_scale_code:r.code,payroll_horizontal_scale_id:t.id,payroll_horizontal_scale_code:t.code,value:e.value};l.push(s)}})}),t.record.payroll_salary_tabulator_scales=l),t.record.id)t.updateRecord(a);else{var r={};for(var e in t.record)r[e]=t.record[e];axios.post("/"+a,r).then(function(l){void 0!==l.data.redirect?location.href=l.data.redirect:(t.errors=[],t.reset(),t.readRecords(a),t.showMessage("store"))}).catch(function(a){if(t.errors=[],void 0!==a.response)for(var l in a.response.data.errors)a.response.data.errors[l]&&t.errors.push(a.response.data.errors[l][0])})}}}},e=l("KHd+"),s=Object(e.a)(r,function(){var a=this,t=a.$createElement,l=a._self._c||t;return l("section",{attrs:{id:"PayrollSalaryTabulatorComponent"}},[l("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"#",title:"Registros Tabuladores de Nómina","data-toggle":"tooltip"},on:{click:function(t){return a.addRecord("add_payroll_salary_tabulator","salary-tabulators",t)}}},[l("i",{staticClass:"icofont icofont-table ico-3x"}),a._v(" "),l("span",[a._v("Tabuladores de Nónima")])]),a._v(" "),l("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_payroll_salary_tabulator"}},[l("div",{staticClass:"modal-dialog modal-lg vue-crud",attrs:{role:"document"}},[l("div",{staticClass:"modal-content"},[a._m(0),a._v(" "),l("div",{staticClass:"modal-body"},[a.errors.length>0?l("div",{staticClass:"alert alert-danger"},[l("div",{staticClass:"container"},[a._m(1),a._v(" "),l("strong",[a._v("Cuidado!")]),a._v(" Debe verificar los siguientes errores antes de continuar:\n                                "),l("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"alert","aria-label":"Close"},on:{click:function(t){t.preventDefault(),a.errors=[]}}},[a._m(2)]),a._v(" "),l("ul",a._l(a.errors,function(t){return l("li",[a._v(a._s(t))])}),0)])]):a._e(),a._v(" "),l("div",{staticClass:"wizard-tabs with-border"},["Form"==a.panel?l("ul",{staticClass:"nav wizard-steps"},[l("li",{staticClass:"nav-item active"},[l("a",{staticClass:"nav-link text-center",attrs:{href:"#w-tabulatorForm","data-toggle":"tab",id:"tabuladorForm"},on:{click:function(t){return a.changePanel("Form")}}},[l("span",{staticClass:"badge"},[a._v("1")]),a._v("\n                                        Definir Tabulador\n                                    ")])]),a._v(" "),l("li",{staticClass:"nav-item"},[l("a",{staticClass:"nav-link text-center",attrs:{href:"#w-tabulatorShow","data-toggle":"tab",id:"tabuladorShow"},on:{click:function(t){return a.changePanel("Show")}}},[l("span",{staticClass:"badge"},[a._v("2")]),a._v("\n                                        Cargar Tabulador\n                                    ")])])]):l("ul",{staticClass:"nav wizard-steps"},[l("li",{staticClass:"nav-item"},[l("a",{staticClass:"nav-link text-center",attrs:{href:"#w-tabulatorForm","data-toggle":"tab",id:"tabuladorForm"},on:{click:function(t){return a.changePanel("Form")}}},[l("span",{staticClass:"badge"},[a._v("1")]),a._v("\n                                        Definir Tabulador\n                                    ")])]),a._v(" "),l("li",{staticClass:"nav-item active"},[l("a",{staticClass:"nav-link text-center",attrs:{href:"#w-tabulatorShow","data-toggle":"tab",id:"tabuladorShow"},on:{click:function(t){return a.changePanel("Show")}}},[l("span",{staticClass:"badge"},[a._v("2")]),a._v("\n                                        Cargar Tabulador\n                                    ")])])])]),a._v(" "),l("form",{staticClass:"form-horizontal"},[l("div",{staticClass:"tab-content"},[l("div",{staticClass:"tab-pane p-3 active",attrs:{id:"w-tabulatorForm"}},[l("div",{staticClass:"row"},[l("div",{staticClass:"col-md-6"},[l("div",{staticClass:" form-group is-required"},[l("label",[a._v("Nombre")]),a._v(" "),l("input",{directives:[{name:"model",rawName:"v-model",value:a.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Nombre del tabulador","data-toggle":"tooltip"},domProps:{value:a.record.name},on:{input:function(t){t.target.composing||a.$set(a.record,"name",t.target.value)}}}),a._v(" "),l("input",{directives:[{name:"model",rawName:"v-model",value:a.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:a.record.id},on:{input:function(t){t.target.composing||a.$set(a.record,"id",t.target.value)}}})]),a._v(" "),l("div",{staticClass:"row"},[l("div",{staticClass:"col-md-4"},[l("div",{staticClass:"form-group"},[l("label",[a._v("¿Activa?")]),a._v(" "),l("div",{staticClass:"col-12"},[l("p-check",{staticClass:"pretty p-switch p-fill p-bigger",attrs:{color:"success","off-color":"text-gray",toggle:""},model:{value:a.record.active,callback:function(t){a.$set(a.record,"active",t)},expression:"record.active"}},[l("label",{attrs:{slot:"off-label"},slot:"off-label"})])],1)])]),a._v(" "),l("div",{staticClass:"col-md-8"},[l("div",{staticClass:"form-group"},[l("label",[a._v("Moneda:")]),a._v(" "),l("select2",{attrs:{options:a.currencies},model:{value:a.record.currency_id,callback:function(t){a.$set(a.record,"currency_id",t)},expression:"record.currency_id"}})],1)])]),a._v(" "),l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Tipo de Personal")]),a._v(" "),l("v-multiselect",{attrs:{options:a.payroll_staff_types,track_by:"text",hide_selected:!1},model:{value:a.record.payroll_staff_types,callback:function(t){a.$set(a.record,"payroll_staff_types",t)},expression:"record.payroll_staff_types"}})],1)]),a._v(" "),l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group"},[l("label",[a._v("Descripción:")]),a._v(" "),l("ckeditor",{staticClass:"form-control",attrs:{editor:a.ckeditor.editor,id:"description_tabulator","data-toggle":"tooltip",title:"Indique alguna descripción asociada al tabulador",config:a.ckeditor.editorConfig,name:"description_tabulator","tag-name":"textarea",rows:"2"},model:{value:a.record.description,callback:function(t){a.$set(a.record,"description",t)},expression:"record.description"}})],1)]),a._v(" "),l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Institución:")]),a._v(" "),l("select2",{attrs:{options:a.institutions},model:{value:a.record.institution_id,callback:function(t){a.$set(a.record,"institution_id",t)},expression:"record.institution_id"}})],1)]),a._v(" "),l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Tipo de tabulador:")]),a._v(" "),l("select2",{attrs:{options:a.payroll_salary_tabulator_types},on:{input:function(t){return a.getPayrollSalaryScales()}},model:{value:a.record.payroll_salary_tabulator_type,callback:function(t){a.$set(a.record,"payroll_salary_tabulator_type",t)},expression:"record.payroll_salary_tabulator_type"}})],1)]),a._v(" "),a.record.payroll_salary_tabulator_type&&"mixed"!=a.record.payroll_salary_tabulator_type?l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Escalafón:")]),a._v(" "),"horizontal"==a.record.payroll_salary_tabulator_type?l("select2",{attrs:{id:"payroll_horizontal_salary_scale",options:a.payroll_horizontal_salary_scales},model:{value:a.record.payroll_horizontal_salary_scale_id,callback:function(t){a.$set(a.record,"payroll_horizontal_salary_scale_id",t)},expression:"record.payroll_horizontal_salary_scale_id"}}):l("select2",{attrs:{id:"payroll_vertical_salary_scale",options:a.payroll_vertical_salary_scales},model:{value:a.record.payroll_vertical_salary_scale_id,callback:function(t){a.$set(a.record,"payroll_vertical_salary_scale_id",t)},expression:"record.payroll_vertical_salary_scale_id"}})],1)]):a._e()]),a._v(" "),"mixed"==a.record.payroll_salary_tabulator_type?l("div",{staticClass:"row"},[l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Escalafón horizontal:")]),a._v(" "),l("select",{directives:[{name:"model",rawName:"v-model",value:a.record.payroll_horizontal_salary_scale_id,expression:"record.payroll_horizontal_salary_scale_id"}],staticClass:"form-control select2",attrs:{id:"payroll_horizontal_salary_scale"},on:{change:[function(t){var l=Array.prototype.filter.call(t.target.options,function(a){return a.selected}).map(function(a){return"_value"in a?a._value:a.value});a.$set(a.record,"payroll_horizontal_salary_scale_id",t.target.multiple?l:l[0])},function(t){return a.isDisable("horizontal")}]}},a._l(a.payroll_horizontal_salary_scales,function(t){return l("option",{attrs:{id:t.id+"_h"},domProps:{value:t.id}},[a._v("\n                                                        "+a._s(t.text)+"\n                                                    ")])}),0)])]),a._v(" "),l("div",{staticClass:"col-md-6"},[l("div",{staticClass:"form-group is-required"},[l("label",[a._v("Escalafón vertical:")]),a._v(" "),l("select",{directives:[{name:"model",rawName:"v-model",value:a.record.payroll_vertical_salary_scale_id,expression:"record.payroll_vertical_salary_scale_id"}],staticClass:"form-control select2",attrs:{id:"payroll_vertical_salary_scale"},on:{change:[function(t){var l=Array.prototype.filter.call(t.target.options,function(a){return a.selected}).map(function(a){return"_value"in a?a._value:a.value});a.$set(a.record,"payroll_vertical_salary_scale_id",t.target.multiple?l:l[0])},function(t){return a.isDisable("vertical")}]}},a._l(a.payroll_vertical_salary_scales,function(t){return l("option",{attrs:{id:t.id+"_v"},domProps:{value:t.id}},[a._v("\n                                                        "+a._s(t.text)+"\n                                                    ")])}),0)])])]):a._e()]),a._v(" "),l("div",{staticClass:"tab-pane p-3",attrs:{id:"w-tabulatorShow"}},[a.record.payroll_horizontal_salary_scale_id>0&&a.payroll_salary_scale_h.payroll_scales&&a.payroll_salary_scale_h.payroll_scales.length>0||a.record.payroll_vertical_salary_scale_id>0&&a.payroll_salary_scale_v.payroll_scales&&a.payroll_salary_scale_v.payroll_scales.length>0?l("div",{staticClass:"modal-table"},[a.record.payroll_horizontal_salary_scale_id>0&&"vertical"!=a.record.payroll_salary_tabulator_type?l("table",{staticClass:"table table-hover table-striped table-responsive  table-assignment"},[l("thead",[a.payroll_salary_scale_h.payroll_scales?l("th",{attrs:{colspan:1+a.payroll_salary_scale_h.payroll_scales.length}},[l("strong",[a._v(a._s(a.record.name))])]):a._e()]),a._v(" "),l("tbody",[l("tr",{staticClass:"text-center"},[l("th",[a._v("Nombre:")]),a._v(" "),a._l(a.payroll_salary_scale_h.payroll_scales,function(t,r){return l("th",[a._v("\n                                                        "+a._s(t.name)+"\n                                                    ")])})],2),a._v(" "),"horizontal"==a.record.payroll_salary_tabulator_type?l("tr",{staticClass:"text-center"},[l("th",[a._v("Incidencia:")]),a._v(" "),a._l(a.payroll_salary_scale_h.payroll_scales,function(a,t){return l("td",{staticClass:"td-with-border"},[l("div",[l("input",{staticClass:"form-control input-sm",attrs:{type:"number",id:"salary_scale_h_"+a.id,"data-toggle":"tooltip",min:"0",step:".01",onfocus:"this.select()"}})])])})],2):a._e(),a._v(" "),a._l(a.payroll_salary_scale_v.payroll_scales,function(t,r){return a.record.payroll_vertical_salary_scale_id>0&&"mixed"==a.record.payroll_salary_tabulator_type?l("tr",{staticClass:"text-center"},[l("th",[a._v("\n                                                        "+a._s(t.name)+"\n                                                    ")]),a._v(" "),a._l(a.payroll_salary_scale_h.payroll_scales,function(a,r){return l("td",{staticClass:"td-with-border"},[l("div",[l("input",{staticClass:"form-control input-sm",attrs:{type:"number",id:"salary_scale_"+t.id+"_"+a.id,"data-toggle":"tooltip",min:"0",step:".01",onfocus:"this.select()"}})])])})],2):a._e()})],2)]):"vertical"==a.record.payroll_salary_tabulator_type&&a.record.payroll_vertical_salary_scale_id>0?l("table",{staticClass:"table table-hover table-striped table-responsive  table-assignment"},[l("thead",[l("th",{attrs:{colspan:"2"}},[l("strong",[a._v(a._s(a.record.name))])])]),a._v(" "),l("tbody",[a._m(3),a._v(" "),a._l(a.payroll_salary_scale_v.payroll_scales,function(t,r){return l("tr",{staticClass:"text-center"},[l("th",[a._v("\n                                                        "+a._s(t.name)+"\n                                                    ")]),a._v(" "),l("td",[l("div",[l("input",{staticClass:"form-control input-sm",attrs:{type:"number",id:"salary_scale_v_"+t.id,"data-toggle":"tooltip",min:"0",step:".01",onfocus:"this.select()"}})])])])})],2)]):a._e()]):a._e()])]),a._v(" "),l("div",{staticClass:"wizard-footer"},["Form"==a.panel?l("div",{staticClass:"pull-right"},[l("button",{staticClass:"btn btn-primary btn-wd btn-sm",attrs:{type:"button","data-toggle":"tooltip",title:""},on:{click:function(t){return a.loadSalaryScales()}}},[a._v("\n                                        Siguiente\n                                    ")])]):l("div",{staticClass:"pull-left"},[l("button",{staticClass:"btn btn-default btn-wd btn-sm",attrs:{type:"button","data-toggle":"tooltip",title:""},on:{click:function(t){return a.changePanel("Form")}}},[a._v("\n                                        Regresar\n                                    ")])])])])]),a._v(" "),l("div",{staticClass:"modal-footer"},[l("div",{staticClass:"form-group"},[l("modal-form-buttons",{attrs:{saveRoute:"payroll/salary-tabulators"}})],1)]),a._v(" "),l("div",{staticClass:"modal-body modal-table"},[l("hr"),a._v(" "),l("v-client-table",{attrs:{columns:a.columns,data:a.records,options:a.table_options},scopedSlots:a._u([{key:"description",fn:function(t){return l("div",{},[l("span",{domProps:{innerHTML:a._s(t.row.description)}})])}},{key:"id",fn:function(t){return l("div",{staticClass:"text-center"},[l("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-action",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(l){return a.initUpdate(t.index,l)}}},[l("i",{staticClass:"fa fa-edit"})]),a._v(" "),l("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-action",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(l){return a.deleteRecord(t.index,"salary-tabulators")}}},[l("i",{staticClass:"fa fa-trash-o"})])])}}])})],1)])])])])},[function(){var a=this.$createElement,t=this._self._c||a;return t("div",{staticClass:"modal-header"},[t("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[t("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),t("h6",[t("i",{staticClass:"icofont icofont-table ico-2x"}),this._v("\n\t\t\t\t\t\t\t Tabulador de Nómina\n\t\t\t\t\t\t")])])},function(){var a=this.$createElement,t=this._self._c||a;return t("div",{staticClass:"alert-icon"},[t("i",{staticClass:"now-ui-icons objects_support-17"})])},function(){var a=this.$createElement,t=this._self._c||a;return t("span",{attrs:{"aria-hidden":"true"}},[t("i",{staticClass:"now-ui-icons ui-1_simple-remove"})])},function(){var a=this.$createElement,t=this._self._c||a;return t("tr",{staticClass:"text-center"},[t("th",[this._v("Nombre")]),this._v(" "),t("th",[this._v("Incidencia")])])}],!1,null,null,null);t.default=s.exports}}]);