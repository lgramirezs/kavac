(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{"KHd+":function(t,e,a){"use strict";function o(t,e,a,o,i,s,n,r){var c,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=a,l._compiled=!0),o&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),n?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},l._ssrRegister=c):i&&(c=r?function(){i.call(this,this.$root.$options.shadowRoot)}:i),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(t,e){return c.call(e),d(t,e)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:l}}a.d(e,"a",function(){return o})},ghxA:function(t,e,a){"use strict";a.r(e);var o={data:function(){return{record:{id:"",name:"",direction:"",headquarters:!1,contact_person:"",contact_email:"",finance_bank_id:"",country_id:"",estate_id:"",city_id:"",phones:[]},errors:[],records:[],banks:[],countries:[],estates:["0"],cities:["0"],columns:["finance_bank.name","city.name","name","direction","headquarters","phones","id"]}},methods:{reset:function(){this.record={id:"",name:"",direction:"",headquarters:!1,contact_person:"",contact_email:"",finance_bank_id:"",country_id:"",estate_id:"",city_id:"",phones:[]}}},created:function(){this.table_options.headings={"finance_bank.name":"Banco","city.name":"Ciudad",name:"Agencia Bancaria",direction:"Dirección",headquarters:"Sede Principal",phones:"Números Telefónicos",id:"Acción"},this.table_options.sortable=["finance_bank.name","city.name","name"],this.table_options.filterable=["finance_bank.name","city.name","name"],this.getCountries(),this.getBanks()}},i=a("KHd+"),s=Object(i.a)(o,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"col-xs-2 text-center"},[a("a",{staticClass:"btn-simplex btn-simplex-md btn-simplex-primary",attrs:{href:"#",title:"Registros de agencias bancarias","data-toggle":"tooltip"},on:{click:function(e){return t.addRecord("add_banking_agency","/finance/banking-agencies",e)}}},[a("i",{staticClass:"icofont icofont-business-man ico-3x"}),t._v(" "),t._m(0)]),t._v(" "),a("div",{staticClass:"modal fade text-left",attrs:{tabindex:"-1",role:"dialog",id:"add_banking_agency"}},[a("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[a("div",{staticClass:"modal-content"},[t._m(1),t._v(" "),a("div",{staticClass:"modal-body"},[t.errors.length>0?a("div",{staticClass:"alert alert-danger"},[a("ul",t._l(t.errors,function(e){return a("li",[t._v(t._s(e))])}),0)]):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group"},[a("label",[t._v("Sede principal")]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"col-12 bootstrap-switch-mini"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.headquarters,expression:"record.headquarters"}],staticClass:"form-control bootstrap-switch",attrs:{type:"checkbox","data-toggle":"tooltip","data-on-label":"SI","data-off-label":"NO",title:"Indique si es la sede principal del banco",value:"true"},domProps:{checked:Array.isArray(t.record.headquarters)?t._i(t.record.headquarters,"true")>-1:t.record.headquarters},on:{change:function(e){var a=t.record.headquarters,o=e.target,i=!!o.checked;if(Array.isArray(a)){var s=t._i(a,"true");o.checked?s<0&&t.$set(t.record,"headquarters",a.concat(["true"])):s>-1&&t.$set(t.record,"headquarters",a.slice(0,s).concat(a.slice(s+1)))}else t.$set(t.record,"headquarters",i)}}})])])])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("País")]),t._v(" "),a("select2",{attrs:{options:t.countries},on:{input:t.getEstates},model:{value:t.record.country_id,callback:function(e){t.$set(t.record,"country_id",e)},expression:"record.country_id"}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.id,expression:"record.id"}],attrs:{type:"hidden"},domProps:{value:t.record.id},on:{input:function(e){e.target.composing||t.$set(t.record,"id",e.target.value)}}})],1)]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Estado")]),t._v(" "),a("select2",{attrs:{options:t.estates},on:{input:t.getCities},model:{value:t.record.estate_id,callback:function(e){t.$set(t.record,"estate_id",e)},expression:"record.estate_id"}})],1)]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Ciudad")]),t._v(" "),a("select2",{attrs:{options:t.cities},model:{value:t.record.city_id,callback:function(e){t.$set(t.record,"city_id",e)},expression:"record.city_id"}})],1)]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Banco")]),t._v(" "),a("select2",{attrs:{options:t.banks},model:{value:t.record.finance_bank_id,callback:function(e){t.$set(t.record,"finance_bank_id",e)},expression:"record.finance_bank_id"}})],1),t._v(" "),a("div",{staticClass:"form-group"},[a("label",[t._v("Persona de contacto")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.contact_person,expression:"record.contact_person"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Nombre contacto","data-toggle":"tooltip",title:"Indique el nombre de la persona de contacto"},domProps:{value:t.record.contact_person},on:{input:function(e){e.target.composing||t.$set(t.record,"contact_person",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Nombre de Agencia")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.name,expression:"record.name"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Nombre agencia","data-toggle":"tooltip",title:"Indique el nombre de la agencia bancaria (requerido)"},domProps:{value:t.record.name},on:{input:function(e){e.target.composing||t.$set(t.record,"name",e.target.value)}}})]),t._v(" "),a("div",{staticClass:"form-group"},[a("label",[t._v("Correo de contacto")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.record.contact_email,expression:"record.contact_email"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Nombre contacto","data-toggle":"tooltip",title:"Indique el correo de la persona de contacto"},domProps:{value:t.record.contact_email},on:{input:function(e){e.target.composing||t.$set(t.record,"contact_email",e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"form-group is-required"},[a("label",[t._v("Dirección")]),t._v(" "),a("ckeditor",{staticClass:"form-control",attrs:{editor:t.ckeditor.editor,id:"direction","data-toggle":"tooltip",title:"Indique la dirección de la agencia bancaria",config:t.ckeditor.editorConfig,name:"direction","tag-name":"textarea",rows:"3"},model:{value:t.record.direction,callback:function(e){t.$set(t.record,"direction",e)},expression:"record.direction"}})],1)])]),t._v(" "),a("hr"),t._v(" "),a("h6",{staticClass:"card-title"},[t._v("\n\t\t\t\t\t\t\tNúmeros Telefónicos "),a("i",{staticClass:"fa fa-plus-circle cursor-pointer",on:{click:t.addPhone}})]),t._v(" "),t._l(t.record.phones,function(e,o){return a("div",{staticClass:"row"},[a("div",{staticClass:"col-3"},[a("div",{staticClass:"form-group is-required"},[a("select",{directives:[{name:"model",rawName:"v-model",value:e.type,expression:"phone.type"}],staticClass:"select2",attrs:{"data-toggle":"tooltip",title:"Seleccione el tipo de número telefónico"},on:{change:function(a){var o=Array.prototype.filter.call(a.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.$set(e,"type",a.target.multiple?o:o[0])}}},[a("option",{attrs:{value:""}},[t._v("Seleccione...")]),t._v(" "),a("option",{attrs:{value:"M"}},[t._v("Móvil")]),t._v(" "),a("option",{attrs:{value:"T"}},[t._v("Teléfono")]),t._v(" "),a("option",{attrs:{value:"F"}},[t._v("Fax")])])])]),t._v(" "),a("div",{staticClass:"col-2"},[a("div",{staticClass:"form-group is-required"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.area_code,expression:"phone.area_code"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Cod. Area","data-toggle":"tooltip",title:"Indique el código de área"},domProps:{value:e.area_code},on:{input:function(a){a.target.composing||t.$set(e,"area_code",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-4"},[a("div",{staticClass:"form-group is-required"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.number,expression:"phone.number"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Número","data-toggle":"tooltip",title:"Indique el número telefónico"},domProps:{value:e.number},on:{input:function(a){a.target.composing||t.$set(e,"number",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-2"},[a("div",{staticClass:"form-group is-required"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.extension,expression:"phone.extension"}],staticClass:"form-control input-sm",attrs:{type:"text",placeholder:"Extensión","data-toggle":"tooltip",title:"Indique la extención telefónica (opcional)"},domProps:{value:e.extension},on:{input:function(a){a.target.composing||t.$set(e,"extension",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"col-1"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-sm btn-danger btn-action",attrs:{type:"button",title:"Eliminar este dato","data-toggle":"tooltip"},on:{click:function(e){return t.removeRow(o,t.record.phones)}}},[a("i",{staticClass:"fa fa-minus-circle"})])])])])})],2),t._v(" "),a("div",{staticClass:"modal-footer"},[a("div",{staticClass:"form-group"},[a("modal-form-buttons",{attrs:{saveRoute:"finance/banking-agencies"}})],1)]),t._v(" "),a("div",{staticClass:"modal-body modal-table"},[a("v-client-table",{attrs:{columns:t.columns,data:t.records,options:t.table_options},scopedSlots:t._u([{key:"id",fn:function(e){return a("div",{staticClass:"text-center"},[a("button",{staticClass:"btn btn-warning btn-xs btn-icon btn-round",attrs:{title:"Modificar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.initUpdate(e.index,a)}}},[a("i",{staticClass:"fa fa-edit"})]),t._v(" "),a("button",{staticClass:"btn btn-danger btn-xs btn-icon btn-round",attrs:{title:"Eliminar registro","data-toggle":"tooltip",type:"button"},on:{click:function(a){return t.deleteRecord(e.index,"/finance/banking-agencies")}}},[a("i",{staticClass:"fa fa-trash-o"})])])}},{key:"headquarters",fn:function(e){return a("div",{staticClass:"text-center"},[e.row.headquarters?a("span",[t._v("SI")]):a("span",[t._v("NO")])])}},{key:"phones",fn:function(e){return a("div",{staticClass:"text-center"},t._l(e.row.phones,function(e){return a("span",[a("div",[t._v("\n\t                \t\t\t\t\t"+t._s(e.area_code)+" "+t._s(e.number)+"\n\t\t                \t\t\t\t"+t._s(e.extension?" - "+e.extension:"")+"\n\t                \t\t\t\t")])])}),0)}}])})],1)])])])])},[function(){var t=this.$createElement,e=this._self._c||t;return e("span",[this._v("Agencias"),e("br"),this._v("Bancarias")])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])]),this._v(" "),e("h6",[e("i",{staticClass:"icofont icofont-business-man inline-block"}),this._v("\n\t\t\t\t\t\t\tAgencias Bancarias\n\t\t\t\t\t\t")])])}],!1,null,null,null);e.default=s.exports}}]);