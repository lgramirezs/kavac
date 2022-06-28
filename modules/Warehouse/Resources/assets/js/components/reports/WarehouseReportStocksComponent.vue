<template>
    <section id="WarehouseReportStocksForm">
        <div class="card-body">
            <div class="alert alert-danger" v-if="errors.length > 0">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <strong>Filtros</strong>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Organización:</label>
                        <select2 :options="institutions"
                                 v-model="record.institution_id"></select2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Almacén:</label>
                        <select2 :options="warehouses"
                                 v-model="record.warehouse_id"></select2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Insumo:</label>
                        <select2 :options="warehouse_products"
                                 v-model="record.warehouse_product_id"></select2>
                    </div>
                </div> 
            </div>

            <div class="row text-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Busqueda por periodo</label>
                        <div class="col-12">
                            <div class="col-12 bootstrap-switch-mini">
                                <input type="radio" name="type_search" value="date" id="sel_search_date"
                                       class="form-control bootstrap-switch bootstrap-switch-mini sel_type_search"
                                       data-on-label="SI" data-off-label="NO">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group">
                        <label>Busqueda por mes</label>
                        <div class="col-12">
                            <div class="col-12 bootstrap-switch-mini">
                                <input type="radio" name="type_search" value="mes" id="sel_search_mes"
                                       class="form-control bootstrap-switch bootstrap-switch-mini sel_type_search"
                                       data-on-label="SI" data-off-label="NO">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="this.record.type_search == 'mes'">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mes:</label>
                            <select2 :options="mes"
                                     v-model="record.mes_id"></select2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Año:</label>
                            <input type="number" data-toggle="tooltip" min="0"
                                       title="Indique el año de busqueda"
                                       class="form-control input-sm" v-model="record.year">
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="this.record.type_search == 'date'">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Desde:</label>
                            <div class="input-group input-sm">
                                <span class="input-group-addon">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                </span>
                                <input type="date" data-toggle="tooltip"
                                       title="Indique la fecha minima de busqueda"
                                       class="form-control input-sm" v-model="record.start_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Hasta:</label>
                            <div class="input-group input-sm">
                                <span class="input-group-addon">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                </span>
                                <input type="date" data-toggle="tooltip"
                                       title="Indique la fecha maxima de busqueda"
                                       class="form-control input-sm" v-model="record.end_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" @click="loadInventoryProduct('stocks')"
                            class='btn btn-sm btn-info float-right' title="Buscar registro" data-toggle="tooltip">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <v-client-table :columns="columns" :data="records" :options="table_options">
                <div slot="product" slot-scope="props">
                    <span>
                        {{
                            (props.row.warehouse_inventory_product &&
                            props.row.warehouse_inventory_product.warehouse_product)
                            ? props.row.warehouse_inventory_product.warehouse_product.name
                            : ''
                        }}
                    </span>
                </div>
                <div slot="exist" slot-scope="props">
                    <span>
                        {{
                            (props.row.warehouse_inventory_product &&
                             props.row.warehouse_inventory_product.exist)
                            ? props.row.warehouse_inventory_product.exist
                            : '0'
                        }}
                    </span>
                </div>
                 <div slot="detail" slot-scope="props">
                        <span v-if="props.row.minimum == props.row.warehouse_inventory_product.exist">
                            El artículo llego al mínimo de existencia
                        </span>

                        <span v-else-if="props.row.warehouse_inventory_product.exist == 0">
                            No hay existencia en inventario
                        </span>
                        
                        <span v-else-if="props.row.minimum > props.row.warehouse_inventory_product.exist">
                            El artículo sobrepasa el mínimo de existencia
                        </span>

                        <span v-else-if="props.row.warehouse_inventory_product.exist > props.row.minimum">
                            Hay existencia del artículo en inventario
                        </span>
                </div>

            </v-client-table>
        </div>
        <div class="card-footer text-right">
            <div class="row">
                <div class="col-md-3 offset-md-9" id="helpParamButtons">
                    <button type="button" class='btn btn-sm btn-primary btn-custom'
                            @click="createReport('stocks')">
                        <i class="fa fa-file-pdf-o"></i>
                        <span>Generar Reporte</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    id: '',
                    warehouse_product_id: '',
                    warehouse_id: '',

                    type_search: '',
                    institution_id: '',

                    mes_id: '',
                    year: '',
                    start_date: '',
                    end_date: ''
                },
                warehouses: [],
                warehouse_products: [],
                records: [],
                errors: [],
                columns: ['product', 'minimum', 'exist', 'detail'],
                mes: [
                    {"id":"","text":"Todos"},
                    {"id":1,"text":"Enero"},
                    {"id":2,"text":"Febrero"},
                    {"id":3,"text":"Marzo"},
                    {"id":4,"text":"Abril"},
                    {"id":5,"text":"Mayo"},
                    {"id":6,"text":"Junio"},
                    {"id":7,"text":"Julio"},
                    {"id":8,"text":"Agosto"},
                    {"id":9,"text":"Septiempre"},
                    {"id":10,"text":"Octubre"},
                    {"id":11,"text":"Noviembre"},
                    {"id":12,"text":"Diciembre"}
                ],
                institutions: []
            }
        },
        methods: {
            reset() {
                this.record = {
                    id: '',
                    warehouse_product_id: '',
                    warehouse_id: '',

                    type_search: '',
                    institution_id: '',

                    mes_id: '',
                    year: '',
                    start_date: '',
                    end_date: ''
                }
            },
            getWarehouseProducts() {
                const vm = this;
                axios.get('/warehouse/get-warehouse-products').then(response => {
                    vm.warehouse_products = response.data;
                });
            },
            createReport(current) {
                const vm = this;
                vm.loading = true;
                var fields = {};
                for (var index in this.record) {
                    fields[index] = this.record[index];
                }
                fields["current"] = current;
                axios.post("/warehouse/reports/inventory-products/create", fields).then(response => {
                    if (response.data.result == false)
                        location.href = response.data.redirect;
                    else if (typeof(response.data.redirect) !== "undefined") {
                        window.open(response.data.redirect, '_blank');
                    }
                    else {
                        vm.reset();
                    }
                    vm.loading = false;
                }).catch(error => {
                    if (typeof(error.response) != "undefined") {
                        console.log("error");
                    }
                    vm.loading = false;
                });
            },
            loadInventoryProduct(current) {
                const vm = this;
                vm.loading = true;
                var fields = {};
                for (var index in this.record) {
                    fields[index] = this.record[index];
                }
                fields["current"] = current;
                axios.post("/warehouse/reports/inventory-products/vue-list", fields).then(response => {
                    if (typeof(response.data.records) != "undefined") {
                        vm.records = response.data.records;
                    }
                    vm.loading = false;
                }).catch(error => {
                    if (typeof(error.response) != "undefined") {
                        console.log("error");
                    }
                    vm.loading = false;
                });
            },
        },
        created() {
            this.table_options.headings = {
                'product': 'Producto',
                'minimum': 'Mínimo',
                'exist':   'Existencia actual',
                'detail':  'Detalle'
            };
            this.table_options.sortable = ['product', 'minimum', 'exist', 'detail'];
            this.table_options.filterable = ['product', 'minimum', 'exist', 'detail'];
        },
        mounted() {
            this.switchHandler('type_search');
            this.getInstitutions();
            this.getWarehouseProducts();
            this.getWarehouses();
        }
    };
</script>
