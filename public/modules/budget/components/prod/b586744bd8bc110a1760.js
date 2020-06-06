(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{DjMg:function(t,o,i){"use strict";i.r(o);var a={data:function(){return{record:{type:"",approved_at:"",institution_id:"",document:"",description:"",budget_account_id:[]},institutions:[],specific_actions:[],accounts:[{id:"",text:"Seleccione..."}],from_specific_action_id:"",from_account_id:"",from_amount:0,to_specific_action_id:"",to_account_id:"",to_amount:0,errors:[],modification_accounts:[]}},props:{type_modification:{type:String,required:!0},edit_object:{type:String,required:!1}},mounted:function(){var t=this;axios.get("".concat(window.app_url,"/budget/get-group-specific-actions/").concat(window.execution_year,"/true")).then(function(o){$.isEmptyObject(o.data)||(t.specific_actions=o.data)}).catch(function(o){t.logs("BudgetModificationComponent.vue",263,o,"mounted")}),t.reset(),t.getInstitutions(),t.getAccounts(),t.record.type=t.type_modification,t.edit_object&&t.loadEditData()},watch:{modification_accounts:{deep:!0,handler:function(){0===this.modification_accounts.length?localStorage.removeItem("modification_accounts"):(this.record.budget_account_id=this.modification_accounts,localStorage.modification_accounts=JSON.stringify(this.modification_accounts))}},from_amount:function(){"TR"===this.type_modification&&(this.to_amount=this.from_amount)}},methods:{reset:function(){this.from_specific_action_id="",this.from_account_id="",this.from_amount=0,this.to_specific_action_id="",this.to_account_id="",this.to_amount=0},loadEditData:function(){var t=this,o=JSON.parse(t.edit_object);t.record.approved_at=t.format_date(o.approved_at),t.record.institution_id=o.institution_id,t.record.document=o.document,t.record.description=o.description;var i=[],a={spac_description:"",code:"",description:"",amount:0,account_id:"",specific_action_id:""},c={spac_description:"",code:"",description:"",amount:0,account_id:"",specific_action_id:""},e=0;$.each(o.budget_modification_accounts,function(o,n){var s=n.budget_sub_specific_formulation.specificAction,r="".concat(s.specificable.code," - ").concat(s.code," | ").concat(s.name),d=n.budgetAccount,_="".concat(d.group,".").concat(d.item,".").concat(d.generic,".").concat(d.specific,".").concat(d.subspecific);"D"===n.operation?(a.spac_description=r,a.code=_,a.description=n.budgetAccount.denomination,a.amount=n.amount,a.account_id=d.id,a.specific_action_id=s.id):(c.spac_description=r,c.code=_,c.description=n.budgetAccount.denomination,c.amount=n.amount,c.account_id=d.id,c.specific_action_id=s.id),o%2!=1&&"TR"===t.type_modification||(i[e]={from_spac_description:a.spac_description,from_code:a.code,from_description:a.description,from_amount:a.amount,from_account_id:a.account_id,from_specific_action_id:a.specific_action_id,to_spac_description:c.spac_description,to_code:c.code,to_description:c.description,to_amount:c.amount,to_account_id:c.account_id,to_specific_action_id:c.specific_action_id},e++)}),t.modification_accounts=i},addAccount:function(){var t=this,o=this,i={from_spac_description:"",from_code:"",from_description:"",from_amount:0,from_account_id:"",from_specific_action_id:"",to_spac_description:"",to_code:"",to_description:"",to_amount:0,to_account_id:"",to_specific_action_id:""};return o.from_specific_action_id?o.from_account_id?o.from_amount<=0?(o.showMessage("custom","Alerta!","danger","screen-error","Debe indicar un monto"),!1):void axios.get("".concat(window.app_url,"/budget/detail-specific-actions/").concat(o.from_specific_action_id)).then(function(a){if(a.data.result){var c=a.data.record;axios.get("".concat(window.app_url,"/budget/detail-accounts/").concat(o.from_account_id)).then(function(a){if(a.data.result){var e=a.data.record;i.from_code="".concat(e.group,".").concat(e.item,".").concat(e.generic,".").concat(e.specific,".").concat(e.subspecific),i.from_description=e.denomination,i.from_spac_description="".concat(c.specificable.code," - ").concat(c.code," | ").concat(c.name),i.from_amount=o.from_amount,i.from_account_id=o.from_account_id,i.from_specific_action_id=o.from_specific_action_id,"TR"===t.type_modification?axios.get("".concat(window.app_url,"/budget/detail-specific-actions/").concat(o.to_specific_action_id)).then(function(t){if(t.data.result){var a=t.data.record;axios.get("".concat(window.app_url,"/budget/detail-accounts/").concat(o.to_account_id)).then(function(t){if(t.data.result){var c=t.data.record;i.to_code="".concat(c.group,".").concat(c.item,".").concat(c.generic,".").concat(c.specific,".").concat(c.subspecific),i.to_description=c.denomination,i.to_spac_description="".concat(a.specificable.code," - ").concat(a.code," | ").concat(a.name),i.to_amount=o.to_amount,i.to_account_id=o.to_account_id,i.to_specific_action_id=o.to_specific_action_id,o.modification_accounts.push(i),$(".close").click(),o.reset()}}).catch(function(t){console.log(t)})}}).catch(function(t){console.log(t)}):(o.modification_accounts.push(i),$(".close").click(),o.reset())}}).catch(function(t){console.log(t)})}}).catch(function(t){console.log(t)}):(o.showMessage("custom","Alerta!","danger","screen-error","Debe seleccionar una cuenta presupuestaria"),!1):(o.showMessage("custom","Alerta!","danger","screen-error","Debe seleccionar una acción específica"),!1)},deleteAccount:function(t){var o=this;bootbox.confirm({title:"Eliminar cuenta?",message:"Esta seguro de eliminar esta cuenta del registro de la modificación\n\t\t\t\t\t\t  presupuestaria?",buttons:{cancel:{label:'<i class="fa fa-times"></i> Cancelar'},confirm:{label:'<i class="fa fa-check"></i> Confirmar'}},callback:function(i){i&&o.budget_modification_accounts.splice(t,1)}})},getAccounts:function(){var t=this;t.accounts=[{id:"",text:"Seleccione...",title:""}],axios.get("".concat(window.app_url,"/budget/accounts/egress-list/")).then(function(o){$.isEmptyObject(o.data.records)||$.each(o.data.records,function(){"00"!==this.specific&&t.accounts.push({id:this.id,text:"".concat(this.code," - ").concat(this.denomination),title:"Disponible: "})})}).catch(function(o){t.logs("BudgetModificationComponent.vue",415,o,"getAccounts")})}}},c=i("KHd+"),e=Object(c.a)(a,function(){var t=this,o=t.$createElement,i=t._self._c||o;return i("div",[i("div",{staticClass:"card-body"},[t.errors.length>0?i("div",{staticClass:"alert alert-danger"},[i("ul",t._l(t.errors,function(o){return i("li",[t._v(t._s(o))])}),0)]):t._e(),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-2"},[i("div",{staticClass:"form-group is-required"},[i("label",{staticClass:"control-label",attrs:{for:"approved_at"}},[t._v("Fecha de creación")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.approved_at,expression:"record.approved_at"}],staticClass:"form-control input-sm",attrs:{type:"date",name:"approved_at",id:"approved_at",placeholder:"dd/mm/YY","data-toggle":"tooltip",title:"Fecha en la que se aprobó la modificación presupuestaria"},domProps:{value:t.record.approved_at},on:{input:function(o){o.target.composing||t.$set(t.record,"approved_at",o.target.value)}}})])]),t._v(" "),i("div",{staticClass:"col-md-6"},[i("div",{staticClass:"form-group is-required"},[i("label",{staticClass:"control-label",attrs:{for:"institution_id"}},[t._v("Institución")]),t._v(" "),i("select2",{attrs:{options:t.institutions},model:{value:t.record.institution_id,callback:function(o){t.$set(t.record,"institution_id",o)},expression:"record.institution_id"}})],1)]),t._v(" "),i("div",{staticClass:"col-md-4"},[i("div",{staticClass:"form-group is-required"},[i("label",{staticClass:"control-label",attrs:{for:"document"}},[t._v("Documento")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.document,expression:"record.document"}],staticClass:"form-control input-sm",attrs:{type:"text",name:"document",id:"document",placeholder:"Nro. Documento","data-toggle":"tooltip",title:"Número del documento, decreto o misiva que avala la modificación presupuestaria"},domProps:{value:t.record.document},on:{input:function(o){o.target.composing||t.$set(t.record,"document",o.target.value)}}})])])]),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-12"},[i("div",{staticClass:"form-group is-required"},[i("label",{staticClass:"control-label",attrs:{for:"description"}},[t._v("Descripción")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.record.description,expression:"record.description"}],staticClass:"form-control input-sm",attrs:{type:"text",name:"description",id:"description",placeholder:"Descripción / Detalles","data-toggle":"tooltip",title:"Descripción o detalle de la modificación presupuestaria"},domProps:{value:t.record.description},on:{input:function(o){o.target.composing||t.$set(t.record,"description",o.target.value)}}})])])]),t._v(" "),i("div",{staticClass:"pad-top-40"},[i("h6",{staticClass:"text-center card-title"},[t._v("Cuentas presupuestarias")]),t._v(" "),i("div",{staticClass:"row"},["TR"!==t.type_modification?i("div",{staticClass:"col-12 pad-top-20"},[i("table",{staticClass:"table"},[i("thead",[i("tr",[i("th",[t._v("Acción Específica")]),t._v(" "),i("th",[t._v("Cuenta")]),t._v(" "),i("th",[t._v("Descripción")]),t._v(" "),i("th",[t._v("Monto")]),t._v(" "),i("th",[t.record.approved_at&&t.record.institution_id&&t.record.document&&t.record.description?i("a",{staticClass:"btn btn-sm btn-info btn-action btn-tooltip",attrs:{href:"#","data-original-title":"Agregar nuevo registro","data-toggle":"modal","data-target":"#add_account"}},[i("i",{staticClass:"fa fa-plus-circle"})]):t._e()])])]),t._v(" "),i("tbody",t._l(t.modification_accounts,function(o,a){return i("tr",[i("td",[t._v(t._s(o.from_spac_description))]),t._v(" "),i("td",[t._v(t._s(o.from_code))]),t._v(" "),i("td",[t._v(t._s(o.from_description))]),t._v(" "),i("td",{staticClass:"text-right"},[t._v(t._s(o.from_amount))]),t._v(" "),i("td",{staticClass:"text-center"},[i("input",{attrs:{type:"hidden",name:"from_account_id[]",readonly:""},domProps:{value:o.from_specific_action_id+"|"+o.from_account_id}}),t._v(" "),i("input",{attrs:{type:"hidden",name:"from_budget_account_amount[]",readonly:""},domProps:{value:o.from_amount}}),t._v(" "),i("a",{staticClass:"btn btn-sm btn-danger btn-action",attrs:{href:"#",title:"Eliminar este registro","data-toggle":"tooltip"},on:{click:function(o){return t.deleteAccount(a)}}},[i("i",{staticClass:"fa fa-minus-circle"})])])])}),0)])]):i("div",{staticClass:"col-12 pad-top-20"},[i("table",{staticClass:"table"},[i("thead",[i("tr",[i("th",{staticClass:"border-right",attrs:{colspan:"4"}},[t._v("\n\t\t\t\t\t\t\t\t\tDatos de Origen\n\t\t\t\t\t\t\t\t")]),t._v(" "),i("th",{attrs:{colspan:"4"}},[t._v("\n\t\t\t\t\t\t\t\t\tDatos de Destino\n\t\t\t\t\t\t\t\t")]),t._v(" "),i("th",[t.record.approved_at&&t.record.institution_id&&t.record.document&&t.record.description?i("a",{staticClass:"btn btn-sm btn-info btn-action btn-tooltip",attrs:{href:"#","data-original-title":"Agregar nuevo registro","data-toggle":"modal","data-target":"#add_account"}},[i("i",{staticClass:"fa fa-plus-circle"})]):t._e()])]),t._v(" "),t._m(0)]),t._v(" "),i("tbody",t._l(t.modification_accounts,function(o,a){return i("tr",[i("td",[t._v(t._s(o.from_spac_description))]),t._v(" "),i("td",[t._v(t._s(o.from_code))]),t._v(" "),i("td",[t._v(t._s(o.from_description))]),t._v(" "),i("td",{staticClass:"text-right border-right"},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(o.from_amount)+"\n\t\t\t\t\t\t\t\t")]),t._v(" "),i("td",[t._v(t._s(o.to_spac_description))]),t._v(" "),i("td",[t._v(t._s(o.to_code))]),t._v(" "),i("td",[t._v(t._s(o.to_description))]),t._v(" "),i("td",{staticClass:"text-right"},[t._v(t._s(o.to_amount))]),t._v(" "),i("td",{staticClass:"text-center"},[i("input",{attrs:{type:"hidden",name:"from_account_id[]",readonly:""},domProps:{value:o.from_specific_action_id+"|"+o.from_account_id}}),t._v(" "),i("input",{attrs:{type:"hidden",name:"from_budget_account_amount[]",readonly:""},domProps:{value:o.from_amount}}),t._v(" "),i("a",{staticClass:"btn btn-sm btn-danger btn-action",attrs:{href:"#",title:"Eliminar este registro","data-toggle":"tooltip"},on:{click:function(o){return t.deleteAccount(a)}}},[i("i",{staticClass:"fa fa-minus-circle"})])])])}),0)])])]),t._v(" "),i("div",{staticClass:"modal fade",attrs:{tabindex:"-1",role:"dialog",id:"add_account"}},[i("div",{staticClass:"modal-dialog vue-crud",attrs:{role:"document"}},[i("div",{staticClass:"modal-content"},[i("div",{staticClass:"modal-header"},[t._m(1),t._v(" "),i("h6",[i("i",{staticClass:"ion-arrow-graph-up-right"}),t._v("\n\t\t\t\t\t\t\t\tAgregar Cuenta"+t._s("TR"===t.type_modification?"s":"")+"\n\t\t\t\t\t\t\t")])]),t._v(" "),i("div",{staticClass:"modal-body"},[t.errors.length>0?i("div",{staticClass:"alert alert-danger"},[i("ul",t._l(t.errors,function(o){return i("li",[t._v(t._s(o))])}),0)]):t._e(),t._v(" "),"TR"===t.type_modification?i("div",{staticClass:"row"},[t._m(2)]):t._e(),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-6"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Acción Específica:")]),t._v(" "),i("select2",{attrs:{options:t.specific_actions},model:{value:t.from_specific_action_id,callback:function(o){t.from_specific_action_id=o},expression:"from_specific_action_id"}})],1)]),t._v(" "),i("div",{staticClass:"col-6"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Cuenta:")]),t._v(" "),i("select2",{attrs:{options:t.accounts},model:{value:t.from_account_id,callback:function(o){t.from_account_id=o},expression:"from_account_id"}})],1)])]),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-3"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Monto:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.from_amount,expression:"from_amount"}],staticClass:"form-control input-sm numeric",attrs:{type:"number",onfocus:"$(this).select()","data-toggle":"tooltip",title:"Indique el monto a asignar para la cuenta seleccionada"},domProps:{value:t.from_amount},on:{input:function(o){o.target.composing||(t.from_amount=o.target.value)}}})])])]),t._v(" "),"TR"===t.type_modification?i("div",[t._m(3),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-6"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Acción Específica:")]),t._v(" "),i("select2",{attrs:{options:t.specific_actions},model:{value:t.to_specific_action_id,callback:function(o){t.to_specific_action_id=o},expression:"to_specific_action_id"}})],1)]),t._v(" "),i("div",{staticClass:"col-6"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Cuenta:")]),t._v(" "),i("select2",{attrs:{options:t.accounts},model:{value:t.to_account_id,callback:function(o){t.to_account_id=o},expression:"to_account_id"}})],1)])]),t._v(" "),i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-3"},[i("div",{staticClass:"form-group is-required"},[i("label",[t._v("Monto:")]),t._v(" "),i("input",{directives:[{name:"model",rawName:"v-model",value:t.to_amount,expression:"to_amount"}],staticClass:"form-control",attrs:{type:"number","data-toggle":"tooltip",readonly:"",title:"Indique el monto a asignar para la cuenta seleccionada"},domProps:{value:t.to_amount},on:{input:function(o){o.target.composing||(t.to_amount=o.target.value)}}})])])])]):t._e()]),t._v(" "),i("div",{staticClass:"modal-footer"},[i("button",{staticClass:"btn btn-default btn-sm btn-round btn-modal-close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n\t\t                \t\tCerrar\n\t\t                \t")]),t._v(" "),i("button",{staticClass:"btn btn-primary btn-sm btn-round btn-modal-save",attrs:{type:"button"},on:{click:t.addAccount}},[t._v("\n\t\t                \t\tAgregar\n\t\t\t                ")])])])])])])]),t._v(" "),i("div",{staticClass:"card-footer text-right"},[i("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{type:"reset","data-toggle":"tooltip",title:"Borrar datos del formulario"},on:{click:t.reset}},[i("i",{staticClass:"fa fa-eraser"})]),t._v(" "),i("button",{staticClass:"btn btn-warning btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Cancelar y regresar"},on:{click:function(o){return t.redirect_back(t.route_list)}}},[i("i",{staticClass:"fa fa-ban"})]),t._v(" "),i("button",{staticClass:"btn btn-success btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Guardar registro"},on:{click:function(o){return t.createRecord("budget/modifications")}}},[i("i",{staticClass:"fa fa-save"})])])])},[function(){var t=this,o=t.$createElement,i=t._self._c||o;return i("tr",[i("th",[t._v("Acción Específica")]),t._v(" "),i("th",[t._v("Cuenta")]),t._v(" "),i("th",[t._v("Descripción")]),t._v(" "),i("th",{staticClass:"border-right"},[t._v("Monto")]),t._v(" "),i("th",[t._v("Acción Específica")]),t._v(" "),i("th",[t._v("Cuenta")]),t._v(" "),i("th",[t._v("Descripción")]),t._v(" "),i("th",[t._v("Monto")]),t._v(" "),i("th")])},function(){var t=this.$createElement,o=this._self._c||t;return o("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-label":"Close"}},[o("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])},function(){var t=this.$createElement,o=this._self._c||t;return o("div",{staticClass:"col-12"},[o("h6",{staticClass:"text-center"},[this._v("\n\t\t\t\t\t\t\t\t\t\tCuenta a Debitar\n\t\t\t\t\t\t\t\t\t")])])},function(){var t=this.$createElement,o=this._self._c||t;return o("div",{staticClass:"row"},[o("div",{staticClass:"col-12"},[o("hr"),this._v(" "),o("h6",{staticClass:"text-center"},[this._v("\n\t\t\t\t\t\t\t\t\t\t\tCuenta a Acreditar\n\t\t\t\t\t\t\t\t\t\t")])])])}],!1,null,null,null);o.default=e.exports},"KHd+":function(t,o,i){"use strict";function a(t,o,i,a,c,e,n,s){var r,d="function"==typeof t?t.options:t;if(o&&(d.render=o,d.staticRenderFns=i,d._compiled=!0),a&&(d.functional=!0),e&&(d._scopeId="data-v-"+e),n?(r=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),c&&c.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},d._ssrRegister=r):c&&(r=s?function(){c.call(this,this.$root.$options.shadowRoot)}:c),r)if(d.functional){d._injectStyles=r;var _=d.render;d.render=function(t,o){return r.call(o),_(t,o)}}else{var l=d.beforeCreate;d.beforeCreate=l?[].concat(l,r):[r]}return{exports:t,options:d}}i.d(o,"a",function(){return a})}}]);