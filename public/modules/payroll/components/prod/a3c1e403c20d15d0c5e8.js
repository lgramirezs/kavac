(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{"KHd+":function(t,e,s){"use strict";function r(t,e,s,r,a,o,i,l){var n,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=s,d._compiled=!0),r&&(d.functional=!0),o&&(d._scopeId="data-v-"+o),i?(n=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),a&&a.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},d._ssrRegister=n):a&&(n=l?function(){a.call(this,this.$root.$options.shadowRoot)}:a),n)if(d.functional){d._injectStyles=n;var c=d.render;d.render=function(t,e){return n.call(e),c(t,e)}}else{var _=d.beforeCreate;d.beforeCreate=_?[].concat(_,n):[n]}return{exports:t,options:d}}s.d(e,"a",function(){return r})},zZUs:function(t,e,s){"use strict";s.r(e);var r=new FormData,a={props:{payroll_professional_id:Number},data:function(){return{record:{id:"",payroll_staff_id:"",payroll_instruction_degree_id:"",instruction_degree_name:"",is_student:"",payroll_study_type_id:"",study_program_name:"",class_schedule:"",professions:[],language_details:[]},errors:[],payroll_staffs:[],payroll_instruction_degrees:[],professions:[],json_professions:[],payroll_study_types:[],payroll_languages:[],payroll_language_levels:[]}},methods:{getProfessional:function(){var t=this;axios.get("/payroll/professionals/"+t.payroll_professional_id).then(function(e){for(var s in t.record.id=e.data.record.id,t.record.payroll_staff_id=e.data.record.payroll_staff_id,t.record.payroll_instruction_degree_id=e.data.record.payroll_instruction_degree_id,t.record.instruction_degree_name=e.data.record.instruction_degree_name,t.record.is_student=e.data.record.is_student,t.record.payroll_study_type_id=e.data.record.payroll_study_type_id,t.record.study_program_name=e.data.record.study_program_name,t.record.class_schedule=e.data.record.class_schedule,t.record.professions=e.data.record.professions,t.record.payroll_staff=e.data.record.payroll_staff,t.record.payroll_study_type=e.data.record.payroll_study_type,t.record.payroll_instruction_degree=e.data.record.payroll_instruction_degree,t.record.payroll_languages=e.data.record.payroll_languages,t.record.payroll_language_levels=e.data.record.payroll_language_levels,t.record.payroll_languages)t.record.language_details.push({payroll_language_id:t.record.payroll_languages[s].id,payroll_language_level_id:t.record.payroll_language_levels[s].id})})},reset:function(){this.record={id:"",payroll_staff_id:"",payroll_instruction_degree_id:"",instruction_degree_name:"",is_student:!1,payroll_study_type_id:"",study_program_name:"",class_schedule:"",professions:[],language_details:[]}},addLanguageDetails:function(){this.record.language_details.push({payroll_language_id:"",payroll_language_level_id:""})},processFiles:function(){var t=document.querySelector("#acknowledgments").files.length;console.log(t);for(var e=0;e<t;e++)r.append("acknowledgments[]",document.getElementById("acknowledgments").files[e])}},created:function(){this.record.language_details=[],this.record.professions=[],this.getPayrollStaffs(),this.getPayrollInstructionDegrees(),this.getProfessions(),this.getPayrollStudyTypes(),this.getPayrollLanguages(),this.getPayrollLanguageLevels(),this.record.is_student=!1},mounted:function(){this.payroll_professional_id&&(this.getProfessional(),this.getJsonProfessions()),this.switchHandler("is_student")},watch:{record:{deep:!0,handler:function(){this.record.is_student?($("#is_student").bootstrapSwitch("state",!0,!0),$("#block_student").removeClass("d-none")):$("#block_student").addClass("d-none")}}}},o=s("KHd+"),i=Object(o.a)(a,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-12"},[s("div",{staticClass:"card"},[s("div",{staticClass:"card-header"},[s("h6",{staticClass:"card-title"},[t._v("Registrar los Datos Profesionales")]),t._v(" "),s("div",{staticClass:"card-btns"},[s("a",{staticClass:"btn btn-sm btn-primary btn-custom",attrs:{href:"#",title:"Ir atrás","data-toggle":"tooltip"},on:{click:function(e){return t.redirect_back(t.route_list)}}},[s("i",{staticClass:"fa fa-reply"})]),t._v(" "),t._m(0)])]),t._v(" "),s("div",{staticClass:"card-body"},[t.errors.length>0?s("div",{staticClass:"alert alert-danger"},[s("ul",t._l(t.errors,function(e){return s("li",[t._v(t._s(e))])}),0)]):t._e(),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group is-required"},[s("label",[t._v("Trabajador:")]),t._v(" "),s("select2",{attrs:{options:t.payroll_staffs},model:{value:t.record.payroll_staff_id,callback:function(e){t.$set(t.record,"payroll_staff_id",e)},expression:"record.payroll_staff_id"}}),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})],1)]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group is-required"},[s("label",[t._v("Grado de Instrucción:")]),t._v(" "),s("select2",{attrs:{options:t.payroll_instruction_degrees},model:{value:t.record.payroll_instruction_degree_id,callback:function(e){t.$set(t.record,"payroll_instruction_degree_id",e)},expression:"record.payroll_instruction_degree_id"}})],1)]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:4==t.record.payroll_instruction_degree_id||5==t.record.payroll_instruction_degree_id,expression:"record.payroll_instruction_degree_id == 4 || record.payroll_instruction_degree_id == 5"}],staticClass:"col-md-4"},[s("div",{staticClass:"form-group is-required"},[s("label",[t._v("Profesiones:")]),t._v(" "),t.payroll_professional_id?s("v-multiselect",{attrs:{options:t.json_professions,track_by:"name",hide_selected:!1,selected:t.record.professions},model:{value:t.record.professions,callback:function(e){t.$set(t.record,"professions",e)},expression:"record.professions"}}):s("v-multiselect",{attrs:{options:t.professions,track_by:"text",hide_selected:!1,selected:t.record.professions},model:{value:t.record.professions,callback:function(e){t.$set(t.record,"professions",e)},expression:"record.professions"}})],1)])]),t._v(" "),s("div",{staticClass:"row"},[s("div",{directives:[{name:"show",rawName:"v-show",value:6==t.record.payroll_instruction_degree_id||7==t.record.payroll_instruction_degree_id||8==t.record.payroll_instruction_degree_id,expression:"record.payroll_instruction_degree_id == 6 || record.payroll_instruction_degree_id == 7 ||\n\t\t\t\t\t\t\trecord.payroll_instruction_degree_id == 8"}],staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Nombre de Especialización, Maestría o Doctorado:")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.instruction_degree_name,expression:"record.instruction_degree_name"}],staticClass:"form-control input-sm",attrs:{type:"text"},domProps:{value:t.record.instruction_degree_name},on:{input:function(e){e.target.composing||t.$set(t.record,"instruction_degree_name",e.target.value)}}})])]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("¿Es Estudiante?")]),t._v(" "),s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"col-12 bootstrap-switch-mini"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.is_student,expression:"record.is_student"}],staticClass:"form-control bootstrap-switch",attrs:{id:"is_student",name:"is_student",type:"checkbox","data-toggle":"tooltip","data-on-label":"SI","data-off-label":"NO",title:"Indique si el trabajador es estudiante o no",value:"true"},domProps:{checked:Array.isArray(t.record.is_student)?t._i(t.record.is_student,"true")>-1:t.record.is_student},on:{change:function(e){var s=t.record.is_student,r=e.target,a=!!r.checked;if(Array.isArray(s)){var o=t._i(s,"true");r.checked?o<0&&t.$set(t.record,"is_student",s.concat(["true"])):o>-1&&t.$set(t.record,"is_student",s.slice(0,o).concat(s.slice(o+1)))}else t.$set(t.record,"is_student",a)}}})])])])])]),t._v(" "),s("div",{staticClass:"row d-none",attrs:{id:"block_student"}},[s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Tipo de Estudio:")]),t._v(" "),s("select2",{attrs:{options:t.payroll_study_types},model:{value:t.record.payroll_study_type_id,callback:function(e){t.$set(t.record,"payroll_study_type_id",e)},expression:"record.payroll_study_type_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",[t._v("Nombre del Programa de Estudio:")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.record.study_program_name,expression:"record.study_program_name"}],staticClass:"form-control input-sm",attrs:{type:"text"},domProps:{value:t.record.study_program_name},on:{input:function(e){e.target.composing||t.$set(t.record,"study_program_name",e.target.value)}}})])]),t._v(" "),t._m(1)]),t._v(" "),s("hr"),t._v(" "),s("h6",{staticClass:"card-title"},[t._v("\n\t\t\t\t\t\tDetalles de Idiomas "),s("i",{staticClass:"fa fa-plus-circle cursor-pointer",on:{click:t.addLanguageDetails}})]),t._v(" "),t._l(t.record.language_details,function(e,r){return s("div",{staticClass:"row"},[s("div",{staticClass:"col-3"},[s("div",{staticClass:"form-group is-required"},[s("label",[t._v("Idioma:")]),t._v(" "),s("select2",{attrs:{options:t.payroll_languages},model:{value:e.payroll_language_id,callback:function(s){t.$set(e,"payroll_language_id",s)},expression:"language_detail.payroll_language_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-3"},[s("div",{staticClass:"form-group is-required"},[s("label",[t._v("Nivel de Idioma:")]),t._v(" "),s("select2",{attrs:{options:t.payroll_language_levels},model:{value:e.payroll_language_level_id,callback:function(s){t.$set(e,"payroll_language_level_id",s)},expression:"language_detail.payroll_language_level_id"}})],1)]),t._v(" "),s("div",{staticClass:"col-1"},[s("div",{staticClass:"form-group"},[s("br"),t._v(" "),s("button",{staticClass:"btn btn-sm btn-danger btn-action",attrs:{type:"button",title:"Eliminar este dato","data-toggle":"tooltip","data-placement":"right"},on:{click:function(e){return t.removeRow(r,t.record.language_details)}}},[s("i",{staticClass:"fa fa-minus-circle"})])])])])}),t._v(" "),s("hr"),t._v(" "),s("div",{staticClass:"row"},[t._m(2),t._v(" "),s("div",{staticClass:"col-md-4"},[s("div",{staticClass:"form-group"},[s("label",{attrs:{for:"acknowledgments"}},[t._v("\n\t\t\t\t\t\t\t\t\tReconocimientos:\n\t                            ")]),t._v(" "),s("input",{attrs:{id:"acknowledgments",name:"acknowledgments",type:"file",accept:".png, .jpg, .pdf, .odt",multiple:""},on:{change:function(e){return t.processFiles()}}})])])])],2),t._v(" "),s("div",{staticClass:"card-footer pull-right"},[s("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{"data-toggle":"tooltip",type:"button",title:"Borrar datos del formulario"},on:{click:t.reset}},[s("i",{staticClass:"fa fa-eraser"})]),t._v(" "),s("button",{staticClass:"btn btn-warning btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Cancelar y regresar"},on:{click:function(e){return t.redirect_back(t.route_list)}}},[s("i",{staticClass:"fa fa-ban"})]),t._v(" "),s("button",{staticClass:"btn btn-success btn-icon btn-round",attrs:{type:"button"},on:{click:function(e){return t.createRecord("payroll/professionals")}}},[s("i",{staticClass:"fa fa-save"})])])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("a",{staticClass:"card-minimize btn btn-card-action btn-round",attrs:{href:"#",title:"Minimizar","data-toggle":"tooltip"}},[e("i",{staticClass:"now-ui-icons arrows-1_minimal-up"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-md-4"},[e("div",{staticClass:"form-group"},[e("label",{attrs:{for:"class_schedule"}},[this._v("\n\t\t\t\t\t\t\t\t\tHorario de Clase:\n\t                            ")]),this._v(" "),e("input",{attrs:{id:"class_schedule",name:"class_schedule",type:"file",accept:".odt, .pdf",multiple:""}})])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"col-md-4"},[e("div",{staticClass:"form-group"},[e("label",{attrs:{for:"course"}},[this._v("\n\t\t\t\t\t\t\t\t\tCursos:\n\t                            ")]),this._v(" "),e("input",{attrs:{id:"course",name:"course",type:"file",accept:".png, .jpg, .pdf, .odt",multiple:""}})])])}],!1,null,null,null);e.default=i.exports}}]);