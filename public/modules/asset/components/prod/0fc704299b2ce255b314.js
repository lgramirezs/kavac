(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{nF1K:function(e,t,s){"use strict";s.r(t);function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var a={data:function(){return{record:{id:"",date:"",motive:"",type_id:"",delivery_date:"",agent_name:"",agent_telf:"",agent_email:"",country_id:"",estate_id:"",municipality_id:"",parish_id:"",address:""},records:[],files:[],page:1,total:"",perPage:10,lastPage:"",pageValues:[1,2,3,4,5,6,7,8,9,10],perPageValues:[{id:10,text:"10"},{id:25,text:"25"},{id:50,text:"50"}],columns:["check","inventory_serial","condition","status","serial","marca","model"],errors:[],types:[{id:"",text:"Seleccione..."},{id:1,text:"Prestamo de Equipos (Uso Interno)"},{id:2,text:"Prestamo de Equipos (Uso Externo)"},{id:3,text:"Prestamo de Equipos para Agentes Externos"}],selected:[],selectAll:!1,countries:[],estates:[],municipalities:[],parishes:[],table_options:{rowClassCallback:function(e){var t=document.getElementById("checkbox_"+e.id);return t&&t.checked?"selected-row cursor-pointer":"cursor-pointer"},headings:{inventory_serial:"Código",condition:"Condición Física",status:"Estatus de Uso",serial:"Serial",marca:"Marca",model:"Modelo"},sortable:["inventory_serial","condition","status","serial","marca","model"],filterable:["inventory_serial","condition","status","serial","marca","model"]}}},watch:{perPage:function(e){1==this.page?this.loadAssets("/asset/registers/vue-list/"+e+"/"+this.page):this.changePage(1)},page:function(e){this.loadAssets("/asset/registers/vue-list/"+this.perPage+"/"+e)}},created:function(){this.loadAssets("/asset/registers/vue-list/"+this.perPage+"/"+this.page),this.getCountries()},mounted:function(){this.requestid&&this.loadForm(this.requestid)},props:{requestid:Number},methods:{toggleActive:function(e){var t=e.row,s=document.getElementById("checkbox_"+t.id);if(s&&0==s.checked)(i=this.selected.indexOf(t.id))>=0?this.selected.splice(i,1):s.click();else if(s&&1==s.checked){var i;(i=this.selected.indexOf(t.id))>=0?s.click():this.selected.push(t.id)}},reset:function(){this.record={id:"",created_at:"",motive:"",type_id:"",delivery_date:"",agent_name:"",agent_telf:"",agent_email:"",country_id:"",estate_id:"",municipality_id:"",parish_id:"",address:""},this.selected=[],this.files=[],this.selectAll=!1},select:function(){var e=this;e.selected=[],$.each(e.records,(function(t,s){var i=document.getElementById("checkbox_"+s.id);e.selectAll?i&&i.checked&&i.click():e.selected.push(s.id)}))},changePage:function(e){this.page=e;for(var t=0;;){if(t+10>=this.page){t+=1;break}t+=10}this.pageValues=[];for(var s=0;s<10;s++)this.pageValues.push(t+s)},createRecord:function(e){var t=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],s=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],i=this,a=document.querySelector("#files"),r=new FormData;if(i.errors=[],!i.selected.length>0)return bootbox.alert("Debe agregar almenos un elemento a la solicitud"),!1;if(this.record.id);else{for(var o in i.loading=!0,i.record)"motive"==o?r.append("motive",window.editor.getData()):r.append(o,i.record[o]);r.append("file",a.files[0]),r.append("assets",i.selected),axios.post("/"+e,r,{headers:{"Content-Type":"multipart/form-data"}}).then((function(a){void 0!==a.data.redirect?location.href=a.data.redirect:(i.errors=[],s&&i.reset(),t&&i.readRecords(e),i.loading=!1,i.showMessage("store"))})).catch((function(e){if(i.errors=[],void 0!==e.response)for(var t in e.response.data.errors)e.response.data.errors[t]&&i.errors.push(e.response.data.errors[t][0]);i.loading=!1}))}},loadForm:function(e){var t=this,s={};axios.get("/asset/requests/vue-info/"+e).then((function(e){i("undefined"!=e.data.records)&&(t.record=e.data.records,t.record.created_at=t.format_date(t.record.created_at),s=e.data.records.asset_request_assets,$.each(s,(function(e,s){t.selected.push(s.asset.id)})))}))},loadAssets:function(e){var t=this;e+=null!=t.requestid?"/requests/"+t.requestid:"/requests",axios.get(e).then((function(e){void 0!==e.data.records&&(t.records=e.data.records,t.total=e.data.total,t.lastPage=e.data.lastPage,t.$refs.tableMax.setLimit(t.perPage))}))}}},r=s("KHd+"),o=Object(r.a)(a,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("section",{attrs:{id:"AssetRequestForm"}},[s("div",{staticClass:"card-body"},[e.errors.length>0?s("div",{staticClass:"alert alert-danger"},[s("div",{staticClass:"container"},[e._m(0),e._v(" "),s("strong",[e._v("Cuidado!")]),e._v(" Debe verificar los siguientes errores antes de continuar:\n\t\t\t\t\t"),s("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"alert","aria-label":"Close"},on:{click:function(t){t.preventDefault(),e.errors=[]}}},[e._m(1)]),e._v(" "),s("ul",e._l(e.errors,(function(t){return s("li",[e._v(e._s(t))])})),0)])]):e._e(),e._v(" "),s("div",{staticClass:"row"},[e.record.id?s("div",{staticClass:"col-md-4",attrs:{id:"helpAssetRequestDate"}},[s("div",{staticClass:"form-group"},[s("label",[e._v("Fecha de Solicitud")]),e._v(" "),s("div",{staticClass:"input-group input-sm"},[e._m(2),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.record.created_at,expression:"record.created_at"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toogle":"tooltip",title:"Fecha de la solicitud",disabled:"true"},domProps:{value:e.record.created_at},on:{input:function(t){t.target.composing||e.$set(e.record,"created_at",t.target.value)}}})])])]):e._e(),e._v(" "),s("div",{staticClass:"col-md-6",attrs:{id:"helpAssetRequestDeliveryDate"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Fecha de Entrega")]),e._v(" "),s("div",{staticClass:"input-group input-sm"},[e._m(3),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.record.delivery_date,expression:"record.delivery_date"}],staticClass:"form-control input-sm",attrs:{type:"date","data-toogle":"tooltip",title:"Indique la fecha de entrega de los equipos"},domProps:{value:e.record.delivery_date},on:{input:function(t){t.target.composing||e.$set(e.record,"delivery_date",t.target.value)}}})])])]),e._v(" "),s("div",{staticClass:"col-md-6",attrs:{id:"helpAssetRequestType"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Tipo de Solicitud")]),e._v(" "),s("select2",{attrs:{options:e.types},model:{value:e.record.type_id,callback:function(t){e.$set(e.record,"type_id",t)},expression:"record.type_id"}})],1)]),e._v(" "),s("div",{staticClass:"col-md-6",attrs:{id:"helpAssetRequestMotive"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Motivo de la solicitud")]),e._v(" "),s("ckeditor",{staticClass:"form-control",attrs:{editor:e.ckeditor.editor,id:"motive","data-toggle":"tooltip",title:"Indique el motivo de la solicitud",config:e.ckeditor.editorConfig,name:"motive","tag-name":"textarea",rows:"3"},model:{value:e.record.motive,callback:function(t){e.$set(e.record,"motive",t)},expression:"record.motive"}})],1)]),e._v(" "),e._m(4)]),e._v(" "),e.record.type_id>1?s("div",[e._m(5),e._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-3",attrs:{id:"helpAssetCountry"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Pais:")]),e._v(" "),s("select2",{attrs:{options:e.countries,id:"country_select"},on:{input:function(t){return e.getEstates()}},model:{value:e.record.country_id,callback:function(t){e.$set(e.record,"country_id",t)},expression:"record.country_id"}})],1)]),e._v(" "),s("div",{staticClass:"col-md-3",attrs:{id:"helpAssetEstate"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Estado:")]),e._v(" "),s("select2",{attrs:{options:e.estates,id:"estate_select",disabled:""!=!this.record.country_id},on:{input:function(t){return e.getMunicipalities()}},model:{value:e.record.estate_id,callback:function(t){e.$set(e.record,"estate_id",t)},expression:"record.estate_id"}})],1)]),e._v(" "),s("div",{staticClass:"col-md-3",attrs:{id:"helpAssetMunicipality"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Municipio:")]),e._v(" "),s("select2",{attrs:{options:e.municipalities,id:"municipality_select",disabled:""!=!this.record.estate_id},on:{input:function(t){return e.getParishes()}},model:{value:e.record.municipality_id,callback:function(t){e.$set(e.record,"municipality_id",t)},expression:"record.municipality_id"}})],1)]),e._v(" "),s("div",{staticClass:"col-md-3",attrs:{id:"helpAssetParish"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Parroquia:")]),e._v(" "),s("select2",{attrs:{options:e.parishes,id:"parish_select",disabled:""!=!this.record.municipality_id},model:{value:e.record.parish_id,callback:function(t){e.$set(e.record,"parish_id",t)},expression:"record.parish_id"}})],1)]),e._v(" "),s("div",{staticClass:"col-md-6",attrs:{id:"helpAssetAddress"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Dirección")]),e._v(" "),s("ckeditor",{staticClass:"form-control",attrs:{editor:e.ckeditor.editor,"data-toggle":"tooltip",title:"Indique dirección física del bien",config:e.ckeditor.editorConfig,name:"address","tag-name":"textarea",rows:"3"},model:{value:e.record.address,callback:function(t){e.$set(e.record,"address",t)},expression:"record.address"}})],1)])])]):e._e(),e._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:3==e.record.type_id,expression:"record.type_id == 3"}]},[e._m(6),e._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-4",attrs:{id:"helpAssetRequestAgentName"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Nombre del Agente Externo")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.record.agent_name,expression:"record.agent_name"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toogle":"tooltip",title:"Indique el nombre del agente externo responsable del bien"},domProps:{value:e.record.agent_name},on:{input:function(t){t.target.composing||e.$set(e.record,"agent_name",t.target.value)}}})])]),e._v(" "),s("div",{staticClass:"col-md-4",attrs:{id:"helpAssetRequestAgentTelf"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Teléfono del Agente Externo")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.record.agent_telf,expression:"record.agent_telf"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toogle":"tooltip",title:"Indique el teléfono del agente externo responsable del bien"},domProps:{value:e.record.agent_telf},on:{input:function(t){t.target.composing||e.$set(e.record,"agent_telf",t.target.value)}}})])]),e._v(" "),s("div",{staticClass:"col-md-4",attrs:{id:"helpAssetRequestAgentEmail"}},[s("div",{staticClass:"form-group is-required"},[s("label",[e._v("Correo del Agente Externo")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.record.agent_email,expression:"record.agent_email"}],staticClass:"form-control input-sm",attrs:{type:"text","data-toogle":"tooltip",title:"Indique el correo eléctronico del agente externo responsable del bien"},domProps:{value:e.record.agent_email},on:{input:function(t){t.target.composing||e.$set(e.record,"agent_email",t.target.value)}}})])])])]),e._v(" "),s("hr"),e._v(" "),s("div",{staticClass:"form-group form-inline pull-right VueTables__limit-2"},[s("div",{staticClass:"VueTables__limit-field"},[s("label",{},[e._v("Registros")]),e._v(" "),s("select2",{attrs:{options:e.perPageValues},model:{value:e.perPage,callback:function(t){e.perPage=t},expression:"perPage"}})],1)]),e._v(" "),s("v-client-table",{ref:"tableMax",attrs:{id:"helpTable",columns:e.columns,data:e.records,options:e.table_options},on:{"row-click":e.toggleActive},scopedSlots:e._u([{key:"check",fn:function(t){return s("div",{staticClass:"text-center"},[s("label",{staticClass:"form-checkbox"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.selected,expression:"selected"}],staticClass:"cursor-pointer",attrs:{type:"checkbox",id:"checkbox_"+t.row.id},domProps:{value:t.row.id,checked:Array.isArray(e.selected)?e._i(e.selected,t.row.id)>-1:e.selected},on:{change:function(s){var i=e.selected,a=s.target,r=!!a.checked;if(Array.isArray(i)){var o=t.row.id,n=e._i(i,o);a.checked?n<0&&(e.selected=i.concat([o])):n>-1&&(e.selected=i.slice(0,n).concat(i.slice(n+1)))}else e.selected=r}}})])])}},{key:"condition",fn:function(t){return s("div",{staticClass:"text-center"},[s("span",[e._v(e._s(t.row.asset_condition?t.row.asset_condition.name:t.row.asset_condition_id))])])}},{key:"status",fn:function(t){return s("div",{staticClass:"text-center"},[s("span",[e._v(e._s(t.row.asset_status?t.row.asset_status.name:t.row.asset_status_id))])])}}])},[s("div",{staticClass:"text-center",attrs:{slot:"h__check"},slot:"h__check"},[s("label",{staticClass:"form-checkbox"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.selectAll,expression:"selectAll"}],staticClass:"cursor-pointer",attrs:{type:"checkbox"},domProps:{checked:Array.isArray(e.selectAll)?e._i(e.selectAll,null)>-1:e.selectAll},on:{click:function(t){return e.select()},change:function(t){var s=e.selectAll,i=t.target,a=!!i.checked;if(Array.isArray(s)){var r=e._i(s,null);i.checked?r<0&&(e.selectAll=s.concat([null])):r>-1&&(e.selectAll=s.slice(0,r).concat(s.slice(r+1)))}else e.selectAll=a}}})])])]),e._v(" "),s("div",{staticClass:"VuePagination-2 row col-md-12 "},[s("nav",{staticClass:"text-center"},[s("ul",{staticClass:"pagination VuePagination__pagination"},[1!=e.page?s("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[s("a",{staticClass:"page-link",on:{click:function(t){return e.changePage(1)}}},[e._v("PRIMERO")])]):e._e(),e._v(" "),e._m(7),e._v(" "),e.page>1?s("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-page"},[s("a",{staticClass:"page-link",on:{click:function(t){return e.changePage(e.page-1)}}},[e._v("<")])]):e._e(),e._v(" "),e._l(e.pageValues,(function(t){return t<=e.lastPage?s("li",{class:e.page==t?"VuePagination__pagination-item page-item active":"VuePagination__pagination-item page-item"},[s("a",{staticClass:"page-link active",attrs:{role:"button"},on:{click:function(s){return e.changePage(t)}}},[e._v(e._s(t))])]):e._e()})),e._v(" "),e.page<e.lastPage?s("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-page"},[s("a",{staticClass:"page-link",on:{click:function(t){return e.changePage(e.page+1)}}},[e._v(">")])]):e._e(),e._v(" "),e._m(8),e._v(" "),e.lastPage!=e.page?s("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[s("a",{staticClass:"page-link",on:{click:function(t){return e.changePage(e.lastPage)}}},[e._v("ÚLTIMO")])]):e._e()],2),e._v(" "),s("p",{staticClass:"VuePagination__count text-center col-md-12"})])])],1),e._v(" "),s("div",{staticClass:"card-footer text-right"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-3 offset-md-9",attrs:{id:"helpParamButtons"}},[s("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{type:"button",title:"Borrar datos del formulario"},on:{click:function(t){return e.reset()}}},[s("i",{staticClass:"fa fa-eraser"})]),e._v(" "),e._m(9),e._v(" "),s("button",{staticClass:"btn btn-success btn-icon btn-round btn-modal-save",attrs:{type:"button",title:"Guardar registro"},on:{click:function(t){return e.createRecord("asset/requests")}}},[s("i",{staticClass:"fa fa-save"})])])])])])}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"alert-icon"},[t("i",{staticClass:"now-ui-icons objects_support-17"})])},function(){var e=this.$createElement,t=this._self._c||e;return t("span",{attrs:{"aria-hidden":"true"}},[t("i",{staticClass:"now-ui-icons ui-1_simple-remove"})])},function(){var e=this.$createElement,t=this._self._c||e;return t("span",{staticClass:"input-group-addon"},[t("i",{staticClass:"now-ui-icons ui-1_calendar-60"})])},function(){var e=this.$createElement,t=this._self._c||e;return t("span",{staticClass:"input-group-addon"},[t("i",{staticClass:"now-ui-icons ui-1_calendar-60"})])},function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"col-md-3"},[t("div",{staticClass:"form-group"},[t("label",[this._v(" Adjuntar archivos ")]),this._v(" "),t("input",{attrs:{id:"files",name:"files",type:"file",accept:".odt, .pdf",multiple:""}})])])},function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"row",staticStyle:{margin:"10px 0"}},[t("div",{staticClass:"col-md-12"},[t("b",[this._v("Ubicación")])])])},function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"row",staticStyle:{margin:"10px 0"}},[t("div",{staticClass:"col-md-12"},[t("b",[this._v("Información de Contacto")])])])},function(){var e=this.$createElement,t=this._self._c||e;return t("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk disabled"},[t("a",{staticClass:"page-link"},[this._v("<<")])])},function(){var e=this.$createElement,t=this._self._c||e;return t("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-chunk disabled"},[t("a",{staticClass:"page-link"},[this._v(">>")])])},function(){var e=this.$createElement,t=this._self._c||e;return t("button",{staticClass:"btn btn-warning btn-icon btn-round btn-modal-close",attrs:{type:"button","data-dismiss":"modal",title:"Cancelar y regresar"}},[t("i",{staticClass:"fa fa-ban"})])}],!1,null,null,null);t.default=o.exports}}]);