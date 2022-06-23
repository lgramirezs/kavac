<template>
    <section id="WarehouseReportStocksForm">
        <div class="card-body">
            <div class="alert alert-danger" v-if="errors.length > 0">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <v-client-table :columns="columns" :data="records" :options="table_options">
                <div slot="product" slot-scope="props">
                    <span>
                        {{ (props.row.warehouse_inventory_product)
                            ? (props.row.warehouse_inventory_product.warehouse_product)
                                ? props.row.warehouse_inventory_product.warehouse_product.name
                                : ''
                            : ''
                        }}
                    </span>
                </div>
                <div slot="exist" slot-scope="props">
                    <span>
                        {{
                        
                            (props.row.warehouse_inventory_product)
                            ? props.row.warehouse_inventory_product.exist
                            : ''
                            
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
                    id: ''
                },
                records: [],
                errors: [],
                columns: ['product', 'minimum', 'exist', 'detail']
            }
        },
        methods: {
            reset() {
                this.record = {
                    id: ''
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
            this.loadInventoryProduct('stocks');
        }
    };
</script>
