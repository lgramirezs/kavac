(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{G4zk:function(t,a,e){"use strict";e.r(a);var s={data:function(){return{record:{type_graph_products:"exist"},errors:[],records:[],num_pag:10,pag:1,type:"bar",title:"Productos Registrados",numbers:[1,2,3,4,5,6,7,8,9,10],data:[],labels:[],descriptions:[]}},methods:{reset:function(){this.num_pag=10,this.pag=1,this.type="bar",this.errors=[],this.record={type_graph_products:"exist"}},checkPag:function(t){return this.records.length>t&&(this.pag<=t||this.checkPag(t+this.num_pag))},firstPag:function(){this.pag=1,this.numbers=[1,2,3,4,5,6,7,8,9,10]},lastPag:function(){var t=this;t.pag=t.records.length;for(var a=0;;){if(a+t.num_pag>=t.pag){a+=1;break}a+=t.num_pag}t.numbers=[];for(var e=0;e<10;e++)t.numbers.push(a+e)},prevPag:function(){var t=this;t.pag-=1;for(var a=0;;){if(a+t.num_pag>=t.pag){a+=1;break}a+=t.num_pag}t.numbers=[];for(var e=0;e<10;e++)t.numbers.push(a+e)},nextPag:function(){var t=this;t.pag+=1;for(var a=0;;){if(a+t.num_pag>=t.pag){a+=1;break}a+=t.num_pag}t.numbers=[];for(var e=0;e<10;e++)t.numbers.push(a+e)},addPag:function(){for(var t=this,a=1,e=t.pag;;){if(e<=t.num_pag){t.pag=a*t.num_pag+1;break}e-=t.num_pag,a++}t.numbers=[];for(a=0;a<10;a++)t.numbers.push(t.pag+a)},delPag:function(){for(var t=this,a=0,e=t.pag;;){if(e<=2*t.num_pag){t.pag=t.num_pag*a+1;break}e-=t.num_pag,a++}t.numbers=[];for(a=0;a<10;a++)t.numbers.push(t.pag+a)},update:function(){var t=this;t.records=[];var a=[],e=0;"exist"==t.record.type_graph_products?(t.title="Productos Registrados",axios.get("/warehouse/get-warehouse-inventory-products/exist").then(function(s){void 0!==s.data.records&&(s.data.records.length>5?($.each(s.data.records,function(s,i){e<5?(a.push(i),e++):(t.records.push(a),e=0,a=[])}),a.length>0&&t.records.push(a)):(a=s.data.records,t.records.push(a)),t.updateGraphData())})):"min_request"==t.record.type_graph_products?(t.title="Productos Menos Solicitados",axios.get("/warehouse/get-warehouse-inventory-products/request/asc").then(function(s){void 0!==s.data.records&&(s.data.records.length>5?($.each(s.data.records,function(s,i){e<5?(a.push(i),e++):(t.records.push(a),e=0,a=[])}),a.length>0&&t.records.push(a)):(a=s.data.records,t.records.push(a)),t.updateGraphData())})):"max_request"==t.record.type_graph_products&&(t.title="Productos Mas Solicitados",axios.get("/warehouse/get-warehouse-inventory-products/request/desc").then(function(s){void 0!==s.data.records&&(s.data.records.length>5?($.each(s.data.records,function(s,i){e<5?(a.push(i),e++):(t.records.push(a),e=0,a=[])}),a.length>0&&t.records.push(a)):(a=s.data.records,t.records.push(a)),t.updateGraphData())}))},updateGraphData:function(){var t=this,a=[],e=[],s=[],i=[];t.records.length>0&&(a=t.records[t.pag-1],"exist"==t.record.type_graph_products?$.each(a,function(t,a){e.push(a.exist),s.push(a.code),i.push(a.warehouse_product.measurement_unit?"Unidad: "+a.warehouse_product.measurement_unit:"")}):$.each(a,function(t,a){e.push(null==a.reserved?0:a.reserved),s.push(a.code),i.push(a.warehouse_product.measurement_unit?"Unidad: "+a.warehouse_product.measurement_unit:"")}),t.data=e,t.labels=s,t.descriptions=i)},updateGraphType:function(t){t!=this.type&&(this.type=t,this.update())}},mounted:function(){var t=this;t.reset(),t.switchHandler("type_graph_products"),t.update(),$(".sel_graph_products").on("switchChange.bootstrapSwitch",function(a){t.update()})},watch:{pag:function(t){this.updateGraphData()}}},i=e("KHd+"),r=Object(i.a)(s,function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"card"},[t._m(0),t._v(" "),e("div",{staticClass:"card-body"},[t.errors.length>0?e("div",{staticClass:"alert alert-danger"},[e("ul",t._l(t.errors,function(a){return e("li",[t._v(t._s(a))])}),0)]):t._e(),t._v(" "),t._m(1),t._v(" "),e("div",{staticClass:"row"},[e("div",{staticClass:"col-md-8"}),t._v(" "),e("div",{staticClass:"col-md-4 d-inline-flex"},[e("div",{staticClass:"dropdown"},[e("a",{staticClass:"dropdown-toggle btn btn-sm btn-default btn-custom",attrs:{href:"#",id:"list_options_diagram","data-toggle":"dropdown","aria-expanded":"false",title:""}},["doughnut"==this.type?e("i",{staticClass:"fa fa-refresh",staticStyle:{color:"white"}}):t._e(),t._v(" "),"pie"==this.type?e("i",{staticClass:"fa fa-pie-chart",staticStyle:{color:"white"}}):t._e(),t._v(" "),"line"==this.type?e("i",{staticClass:"fa fa-line-chart",staticStyle:{color:"white"}}):t._e(),t._v(" "),"bar"==this.type?e("i",{staticClass:"fa fa-bar-chart",staticStyle:{color:"white"},attrs:{"aria-hidden":"true"}}):t._e(),t._v(" "),""==this.type?e("i",{staticClass:"fa fa-stop",staticStyle:{color:"white"},attrs:{"aria-hidden":"true"}}):t._e()]),t._v(" "),e("div",{staticClass:"dropdown-menu dropdown-menu-right",attrs:{"aria-labelledby":"list_options_diagram"}},[e("div",{staticClass:"d-inline-flex"},[e("a",{staticClass:"dropdown-item btn btn-sm btn-default",attrs:{title:"Vizualizar en gráfico de barras","data-toggle":"tooltip"},on:{click:function(a){return t.updateGraphType("bar")}}},[e("i",{staticClass:"fa fa-bar-chart",staticStyle:{color:"white"}})]),t._v(" "),e("a",{staticClass:"dropdown-item btn btn-sm btn-default",attrs:{title:"Vizualizar en gráfico de dona","data-toggle":"tooltip"},on:{click:function(a){return t.updateGraphType("doughnut")}}},[e("i",{staticClass:"fa fa-refresh",staticStyle:{color:"white"}})]),t._v(" "),e("a",{staticClass:"dropdown-item btn btn-sm btn-default",attrs:{title:"Vizualizar en gráfico de torta","data-toggle":"tooltip"},on:{click:function(a){return t.updateGraphType("pie")}}},[e("i",{staticClass:"fa fa-pie-chart",staticStyle:{color:"white"}})]),t._v(" "),e("a",{staticClass:"dropdown-item btn btn-sm btn-default",attrs:{title:"Vizualizar en gráfico de linea","data-toggle":"tooltip"},on:{click:function(a){return t.updateGraphType("line")}}},[e("i",{staticClass:"fa fa-line-chart",staticStyle:{color:"white"}})])])])])])]),t._v(" "),e("div",{staticClass:"row"},[e("div",{staticClass:"col-md-2"}),t._v(" "),e("div",{staticClass:"col-md-8"},[e("warehouse-graph-charts",{attrs:{data:t.data,labels:t.labels,type:t.type,descriptions:t.descriptions,title:t.title}})],1)]),t._v(" "),t.records.length>0?e("div",{staticClass:"VuePagination row col-md-12"},[e("nav",{staticClass:"text-center"},[e("ul",{staticClass:"pagination VuePagination__pagination"},[1!=t.pag?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.firstPag()}}},[t._v("PRIMERO")])]):t._e(),t._v(" "),t.pag>t.num_pag?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.delPag()}}},[t._v("<<")])]):t._e(),t._v(" "),t.pag>1?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-page"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.prevPag()}}},[t._v("<")])]):t._e(),t._v(" "),t._l(t.numbers,function(a){return t.records.length>=a?e("li",{class:t.pag==a?"VuePagination__pagination-item page-item active":"VuePagination__pagination-item page-item"},[e("a",{staticClass:"page-link active",attrs:{role:"button"},on:{click:function(e){e.preventDefault(),t.pag=a}}},[t._v(t._s(a))])]):t._e()}),t._v(" "),t.records.length>t.pag?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-page"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.nextPag()}}},[t._v(">")])]):t._e(),t._v(" "),t.checkPag(t.num_pag)?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-next-chunk"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.addPag()}}},[t._v(">>")])]):t._e(),t._v(" "),t.records.length!=t.pag?e("li",{staticClass:"VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk"},[e("a",{staticClass:"page-link",on:{click:function(a){return t.lastPag()}}},[t._v("ÚLTIMO")])]):t._e()],2)])]):t._e()])])},[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"card-header"},[a("h6",{staticClass:"card-title text-uppercase"},[this._v("Gráficos del Inventario de Productos en Almacén")]),this._v(" "),a("div",{staticClass:"card-btns"},[a("a",{staticClass:"card-minimize btn btn-card-action btn-round",attrs:{href:"#",title:"Minimizar","data-toggle":"tooltip"}},[a("i",{staticClass:"now-ui-icons arrows-1_minimal-up"})])])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"row"},[e("div",{staticClass:"col-md-2"}),t._v(" "),e("div",{staticClass:"col-md-3"},[e("div",{staticClass:" form-group"},[e("label",[t._v("Existencia")]),t._v(" "),e("div",{staticClass:"col-12"},[e("div",{staticClass:"col-12 bootstrap-switch-mini"},[e("input",{staticClass:"form-control bootstrap-switch bootstrap-switch-mini sel_graph_products",attrs:{type:"radio",name:"type_graph_products",value:"exist",id:"sel_product_exist","data-on-label":"SI","data-off-label":"NO",checked:""}})])])])]),t._v(" "),e("div",{staticClass:"col-md-3"},[e("div",{staticClass:" form-group"},[e("label",[t._v("Más Solicitados")]),t._v(" "),e("div",{staticClass:"col-12"},[e("div",{staticClass:"col-12 bootstrap-switch-mini"},[e("input",{staticClass:"form-control bootstrap-switch bootstrap-switch-mini sel_graph_products",attrs:{type:"radio",name:"type_graph_products",value:"max_request",id:"sel_product_max_request","data-on-label":"SI","data-off-label":"NO"}})])])])]),t._v(" "),e("div",{staticClass:"col-md-3"},[e("div",{staticClass:" form-group"},[e("label",[t._v("Menos Solicitados")]),t._v(" "),e("div",{staticClass:"col-12"},[e("div",{staticClass:"col-12 bootstrap-switch-mini"},[e("input",{staticClass:"form-control bootstrap-switch bootstrap-switch-mini sel_graph_products",attrs:{type:"radio",name:"type_graph_products",value:"min_request",id:"sel_product_min_request","data-on-label":"SI","data-off-label":"NO"}})])])])])])}],!1,null,null,null);a.default=r.exports},"KHd+":function(t,a,e){"use strict";function s(t,a,e,s,i,r,n,o){var c,l="function"==typeof t?t.options:t;if(a&&(l.render=a,l.staticRenderFns=e,l._compiled=!0),s&&(l.functional=!0),r&&(l._scopeId="data-v-"+r),n?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(n)},l._ssrRegister=c):i&&(c=o?function(){i.call(this,this.$root.$options.shadowRoot)}:i),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(t,a){return c.call(a),d(t,a)}}else{var p=l.beforeCreate;l.beforeCreate=p?[].concat(p,c):[c]}return{exports:t,options:l}}e.d(a,"a",function(){return s})}}]);