(window.webpackJsonp=window.webpackJsonp||[]).push([[36],{grSk:function(t,s,e){"use strict";e.r(s);var o=e("o0o1"),a=e.n(o);function r(t,s,e,o,a,r,i){try{var n=t[r](i),l=n.value}catch(t){return void e(t)}n.done?s(l):Promise.resolve(l).then(o,a)}var i,n,l={data:function(){return{record:{roles_attach_permissions:[]},moduleGroups:[],allPermissionByRol:[],search:"",showGroups:[],roles:[],permissions:[]}},props:["rolesPermissionsUrl","saveUrl"],watch:{record:{deep:!0,handler:function(t,s){}}},methods:{reset:function(){this.record.roles_attach_permissions=[]},setModuleGroups:function(){var t=this,s="";t.moduleGroups=[],$.each(t.permissions,(function(e,o){s!==o.model_prefix&&(s=o.model_prefix,t.moduleGroups.push(s))}))},getGroupName:function(t){return"0"===t.substring(0,1)?t.substring(1):t},filterGroupPermissions:function(t){return this.permissions.filter((function(s){return s.model_prefix===t}))},togglePermissionsByRol:function(t){var s=this;if(s.loading=!0,s.allPermissionByRol.filter((function(s,e,o){return s===t})).length>0)$.each(s.permissions,(function(e,o){s.record.roles_attach_permissions.indexOf("".concat(t,"_").concat(o.id))<0&&s.record.roles_attach_permissions.push("".concat(t,"_").concat(o.id))}));else{var e=s.record.roles_attach_permissions.filter((function(s,e){return s.indexOf("".concat(t,"_"))<0}));s.record.roles_attach_permissions=e}s.loading=!1},searchResult:function(t,s){var e=this,o=""===e.search||t.short_description.indexOf(e.search)>=0||t.name.indexOf(e.search)>=0;return o&&e.showGroups.indexOf(s)<0&&e.showGroups.push(s),o},getRolesAndPermissions:(i=a.a.mark((function t(){var s;return a.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return(s=this).loading=!0,t.next=4,axios.get(s.rolesPermissionsUrl).then((function(t){t.data.result&&(s.roles=t.data.roles,s.permissions=t.data.permissions,s.roles.forEach((function(t){t.permissions.forEach((function(e){s.record.roles_attach_permissions.push("".concat(t.id,"_").concat(e.id))}))})),s.setModuleGroups())})).catch((function(t){s.logs("RolesAndPermissionsComponent",264,t,"getRolesAndPermissions")}));case 4:s.loading=!1;case 5:case"end":return t.stop()}}),t,this)})),n=function(){var t=this,s=arguments;return new Promise((function(e,o){var a=i.apply(t,s);function n(t){r(a,e,o,n,l,"next",t)}function l(t){r(a,e,o,n,l,"throw",t)}n(void 0)}))},function(){return n.apply(this,arguments)})},created:function(){this.getRolesAndPermissions()},mounted:function(){this.setModuleGroups(),this.record.roles_attach_permissions=[],this.allPermissionByRol=[],$(window).scroll((function(){$(this).scrollTop()>50?$(".btn-display").fadeIn():$(".btn-display").fadeOut()}))}},c=e("KHd+"),u=Object(c.a)(l,(function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",[e("div",{staticClass:"card-body"},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-12"},[t._m(0),t._v(" "),t._m(1),t._v(" "),e("div",{staticClass:"row"},[e("div",{staticClass:"col-3 form-group"},[e("div",{staticClass:"input-group input-sm"},[t._m(2),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.search,expression:"search"}],staticClass:"form-control",attrs:{placeholder:"Filtrar",title:"Indique el texto para filtrar los permisos","data-toggle":"tooltip",type:"text"},domProps:{value:t.search},on:{input:function(s){s.target.composing||(t.search=s.target.value)}}})])])]),t._v(" "),e("table",{staticClass:"table table-hover table-striped dt-responsive table-roles-permissions"},[e("thead",[e("tr",[e("th",{staticClass:"text-center border-right col-2",attrs:{rowspan:"2"}},[t._v("PERMISOS")]),t._v(" "),e("th",{staticClass:"text-center col-10",attrs:{colspan:t.roles.length}},[t._v("ROLES")])]),t._v(" "),e("tr",t._l(t.roles,(function(s){return e("th",{staticClass:"text-center",attrs:{title:s.description,"data-toggle":"tooltip"}},[e("p-check",{staticClass:"p-icon p-plain",attrs:{color:"text-success","off-color":"text-gray",value:s.id,"data-toggle":"tooltip",title:"Seleccionar todos los permisos para este rol",toggle:""},on:{change:function(e){return t.togglePermissionsByRol(s.id)}},model:{value:t.allPermissionByRol,callback:function(s){t.allPermissionByRol=s},expression:"allPermissionByRol"}},[e("i",{staticClass:"fa fa-unlock",attrs:{slot:"extra"},slot:"extra"}),t._v(" "),e("i",{staticClass:"fa fa-lock",attrs:{slot:"off-extra"},slot:"off-extra"}),t._v(" "),e("label",{attrs:{slot:"off-label"},slot:"off-label"})]),t._v("\n                                "+t._s(s.name)+"\n                            ")],1)})),0)]),t._v(" "),t._l(t.moduleGroups,(function(s){return e("tbody",[e("tr",[e("td",[t._v(" ")]),t._v(" "),e("td",{staticClass:"text-center",attrs:{colspan:t.roles.length}},[e("span",{staticClass:"card-title text-uppercase text-module"},[t._v("\n                                    Módulo ["+t._s(t.getGroupName(s))+"]\n                                ")])])]),t._v(" "),t._l(t.filterGroupPermissions(s),(function(o,a){return t.searchResult(o,s)?e("tr",[e("td",{staticClass:"text-uppercase"},[t._v("\n                                "+t._s(o.short_description||o.name)+"\n                            ")]),t._v(" "),t._l(t.roles,(function(s,a){return e("td",{staticClass:"text-center"},[e("p-check",{staticClass:"p-icon p-plain",class:"role_"+s.id,attrs:{color:"text-success","off-color":"text-gray","data-toggle":"tooltip",title:"Rol: "+s.name,value:s.id+"_"+o.id,name:s.id+"_"+o.id,toggle:""},model:{value:t.record.roles_attach_permissions,callback:function(s){t.$set(t.record,"roles_attach_permissions",s)},expression:"record.roles_attach_permissions"}},[e("i",{staticClass:"fa fa-unlock",attrs:{slot:"extra"},slot:"extra"}),t._v(" "),e("i",{staticClass:"fa fa-lock",attrs:{slot:"off-extra"},slot:"off-extra"}),t._v(" "),e("label",{attrs:{slot:"off-label"},slot:"off-label"})])],1)}))],2):t._e()}))],2)}))],2)])])]),t._v(" "),e("div",{staticClass:"card-footer text-right"},[e("div",{staticClass:"btn-display"},[e("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Borrar datos del formulario"},on:{click:t.reset}},[e("i",{staticClass:"fa fa-eraser"})]),t._v(" "),e("button",{staticClass:"btn btn-warning btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Cancelar y regresar"},on:{click:function(s){return t.redirect_back(t.route_list)}}},[e("i",{staticClass:"fa fa-ban"})]),t._v(" "),e("button",{staticClass:"btn btn-success btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Guardar registro"},on:{click:function(s){return t.createRecord(t.saveUrl,!1,!1)}}},[e("i",{staticClass:"fa fa-save"})])]),t._v(" "),e("button",{staticClass:"btn btn-default btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Borrar datos del formulario"},on:{click:t.reset}},[e("i",{staticClass:"fa fa-eraser"})]),t._v(" "),e("button",{staticClass:"btn btn-warning btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Cancelar y regresar"},on:{click:function(s){return t.redirect_back(t.route_list)}}},[e("i",{staticClass:"fa fa-ban"})]),t._v(" "),e("button",{staticClass:"btn btn-success btn-icon btn-round",attrs:{type:"button","data-toggle":"tooltip",title:"Guardar registro"},on:{click:function(s){return t.createRecord(t.saveUrl,!1,!1)}}},[e("i",{staticClass:"fa fa-save"})])])])}),[function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-4 panel-legend"},[s("i",{staticClass:"fa fa-lock text-gray",attrs:{title:"Sin permiso de acceso","data-toggle":"tooltip"}}),this._v(" "),s("span",[this._v("Sin permiso otorgado")])])])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"row mg-bottom-20"},[s("div",{staticClass:"col-md-4 panel-legend"},[s("i",{staticClass:"fa fa-unlock text-success",attrs:{title:"Permiso de acceso configurado","data-toggle":"tooltip"}}),this._v(" "),s("span",[this._v("Permiso otorgado")])])])},function(){var t=this.$createElement,s=this._self._c||t;return s("span",{staticClass:"input-group-addon"},[s("i",{staticClass:"fa fa-search"})])}],!1,null,null,null);s.default=u.exports}}]);