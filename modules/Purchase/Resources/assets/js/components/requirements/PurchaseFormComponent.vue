<template>
    <div class="form-horizontal">
        <div class="card-body">
            <purchase-show-errors ref="purchaseShowError" />
            <div class="row">
                <div class="col-md-12">
                    <b>Información base del requerimiento</b>
                </div>
                <div class="col-3" v-if="record.code">
                    <div class="form-group">
                        <label class="control-label">Código del requerimiento</label><br>
                        <label class="control-label">
                            <h5>{{ record.code }}</h5>
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label">Ejercicio económico</label><br>
                        <label class="control-label">
                            <h5>{{ (fiscalYear)?fiscalYear.year:'' }}</h5>
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label">Fecha de generación</label><br>
                        <input type="date" class="form-control" v-model="record.date">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="institutions">Institución</label><br>
                        <select2 :options="institutions" id="institutions" v-model="record.institution_id" v-has-tooltip @input="getDepartmentsAndFiscalYear()"></select2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group is-required">
                        <label class="control-label" for="departments1">Unidad contratante</label><br>
                        <select2 :options="(requirement_edit)?department_list:departments" id="departments1" v-model="record.contracting_department_id"></select2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group is-required">
                        <label class="control-label" for="departments2">Unidad usuario</label><br>
                        <select2 :options="(requirement_edit)?department_list:departments" id="departments2" v-model="record.user_department_id"></select2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group is-required">
                        <label for="purchase_supplier_objects">Tipo</label>
                        <select2 :options="purchase_supplier_objects" id="purchase_supplier_objects" v-model='record.purchase_supplier_object_id'></select2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group is-required">
                        <label for="description">Descripción</label>
                        <input type="text" id="description" v-model="record.description" title="Descripción del requerimiento" data-toggle="tooltip" v-has-tooltip class="form-control">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row" v-if="record.institution_id">
                <div class="col-12">
                    <b>
                        Ingrese los Productos a la solicitud
                        <i class="fa fa-plus-circle card-title cursor-pointer" title="Agregar nuevo producto" data-toggle="tooltip" v-has-tooltip @click="$refs.purchaseWareHouseProduct.addRecord('add_product', 'warehouse/products', $event)">
                        </i>
                        <purchase-warehouse-products id="helpWarehouseProducts" ref="purchaseWareHouseProduct"></purchase-warehouse-products>
                    </b>
                </div>
                <div class="col-6">
                    <div class="form-group is-required">
                        <label>
                            Producto
                        </label>
                        <select2 :options="products" v-model="product"></select2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                    </div>
                </div>
            </div>
            <hr v-if="record.institution_id">
            <v-client-table :columns="columns" :data="record_products" :options="table_options" class="row" v-if="record.institution_id">
                <div slot="measurement_unit" slot-scope="props" class="text-center">
                    <p v-if="props.row.warehouse_product && props.row.warehouse_product.measurement_unit && props.row.warehouse_product.measurement_unit.name">
                        {{ props.row.warehouse_product.measurement_unit.name }}
                    </p>
                    <p v-else>
                        {{ props.row.measurement_unit }}
                    </p>
                </div>
                <div slot="technical_specifications" slot-scope="props" class="text-center">
                    <span>
                        <input type="text" :id="props.index" v-model="props.row.technical_specifications" class="form-control" @input="changeTecnicalSpecifications">
                    </span>
                </div>
                <div slot="quantity" slot-scope="props">
                    <span>
                        <input type="number" :id="props.index" v-model="props.row.quantity" class="form-control" min="1" @input="changeQty">
                    </span>
                </div>
                <div slot="id" slot-scope="props" class="text-center">
                    <div class="d-inline-flex">
                        <button @click="removeProduct(props.index, $event)" class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro" data-toggle="tooltip" v-has-tooltip type="button">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
            </v-client-table>
        </div>
        <div class="row">
            <div class="col-md-3 offset-md-9" id="helpParamButtons">
                <button type="button" @click="reset()"
                        class="btn btn-default btn-icon btn-round"
                        data-toggle="tooltip"
                        title="Borrar datos del formulario">
                        <i class="fa fa-eraser"></i>
                </button>

                <button type="button" @click="redirect_back('/purchase/requirements')"
                        class="btn btn-warning btn-icon btn-round btn-modal-close"
                        data-dismiss="modal"
                        title="Cancelar y regresar">
                        <i class="fa fa-ban"></i>
                </button>

                <button type="button"  @click="createRecord('/purchase/requirements')"
                        class="btn btn-success btn-icon btn-round btn-modal-save"
                        title="Guardar registro">
                    <i class="fa fa-save"></i>
                </button>
            </div>
        </div>
        <!--<div class="card-footer text-right">
            <buttonsDisplay route_list="/purchase/requirements" display="false" />
        </div>-->
    </div>
</template>
<script>
export default {
    props: {
        institutions: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
        purchase_supplier_objects: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
        measurement_units: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
        requirement_edit: {
            type: Object,
            default: function() {
                return null
            }
        },
        department_list: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
    },
    data() {
        return {
            record: {
                code:'',
                date:'',
                institution_id: '',
                contracting_department_id: '',
                user_department_id: '',
                warehouse_id: '',
                purchase_supplier_object_id: '',
                description: '',
                fiscal_year_id: '',
                year: '',
                products: [],
            },
            fiscalYear: null,
            product: {},
            products: [],
            compare_contracting_department_id: '',
            departments: [],
            record_products: [],
            toDelete: [],
            columns: ['name', 'measurement_unit', 'technical_specifications', 'quantity', 'id'],
        }
    },
    created() {
        this.table_options.headings = {
            'name': 'Producto',
            'measurement_unit': 'Unidad de Medida',
            'technical_specifications': 'Especificaciones tecnicas',
            'quantity': 'Cantidad',
            'id': 'ACCIÓN'
        };
        this.table_options.columnsClasses = {
            'name': 'col-xs-4',
            'measurement_unit': 'col-xs-1',
            'technical_specifications': 'col-xs-4',
            'quantity': 'col-xs-2',
            'id': 'col-xs-1'
        };
        if (this.requirement_edit) {
            this.departments = this.department_list;
        }
    },
    mounted() {
        const vm = this;
        if (vm.requirement_edit) {
            // asignara la institucion por medio del usuario
            vm.record.code = vm.requirement_edit.code;
            vm.record.date = vm.requirement_edit.date;
            vm.record.description = vm.requirement_edit.description;
            vm.record.institution_id = vm.requirement_edit.institution_id;
            vm.record.contracting_department_id = vm.requirement_edit.contracting_department_id;
            vm.record.user_department_id = vm.requirement_edit.user_department_id;
            vm.record.purchase_supplier_object_id = vm.requirement_edit.purchase_supplier_object_id;
            vm.record.fiscal_year_id = vm.requirement_edit.fiscal_year_id;
            vm.record_products = vm.requirement_edit.purchase_requirement_items;
            vm.getDepartments();
        }

        vm.getFiscalYear();
    },
    methods: {
        reset() {
            const vm = this;
            vm.record = {
                institution_id: '',
                contracting_department_id: '',
                user_department_id: '',
                warehouse_id: '',
                purchase_supplier_object_id: '',
                description: '',
                products: [],
            };
            vm.errors = [];
            vm.$refs.purchaseShowError.reset();
        },
        createRecord() {
            const vm = this;
            vm.record.products = vm.record_products;
            vm.loading = true;
            
            var url = vm.setUrl('/purchase/requirements/');

            if (vm.requirement_edit) {
                vm.record.toDelete = vm.toDelete;
                axios.put(url + vm.requirement_edit.id, vm.record).then(response => {
                    vm.loading = false;
                    vm.showMessage('update');
                    setTimeout(function() {
                        location.href = url;
                    }, 2000);
                }).catch(error => {
                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                    vm.$refs.purchaseShowError.refresh();
                    vm.loading = false;
                });
            } else {
                axios.post(url, vm.record).then(response => {
                    vm.loading = false;
                    vm.showMessage('store');
                    setTimeout(function() {
                        location.href = url;
                    }, 2000);
                }).catch(error => {
                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                    vm.$refs.purchaseShowError.refresh();
                    vm.loading = false;
                });
            }
        },

        // getWarehouses() {
        //     const vm = this;
        //     vm.warehouses = [];

        //     if (vm.record.institution_id != '') {
        //         axios.get('/warehouse/get-warehouses/' + vm.record.institution_id).then(response => {
        //             vm.warehouses = response.data;
        //         });
        //     }
        // },

        getDepartmentsAndFiscalYear(){
            this.getExecutionYear();
            this.getDepartments();
        },

        getExecutionYear(){
            const vm = this;
            axios.get(`${window.app_url}/get-execution-year/${vm.record.institution_id}`).then(response => {
                vm.getFiscalYear();
            });
        },
        getDepartments() {
            const vm = this;
            vm.departments = [];

            if (vm.record.institution_id != '') {
                axios.get(`${window.app_url}/get-departments/${vm.record.institution_id}`).then(response => {
                    vm.departments = response.data;
                    // vm.getWarehouses();
                    vm.getWarehouseProducts();
                });
            }
        },
        getFiscalYear() {
            const vm = this;
            axios.get('/purchase/get-fiscal-year').then(response => {
                if (response.data.fiscal_year) {
                    vm.fiscalYear = response.data.fiscal_year;
                    vm.record.fiscal_year_id = vm.fiscalYear.id;
                }
            });
        },
        removeProduct(index, event) {
            var v = this.record_products.splice(index - 1, 1)[0];
            if (v['updated_at']) {
                this.toDelete.push(v['id']);
            }
        },
        getWarehouseProducts() {
            this.products = [];
            axios.get('/warehouse/get-warehouse-products/').then(response => {
                this.products = response.data;
            });
        },
        changeQty({ type, target }) {
            this.record_products[target.id - 1].quantity = target.value;
        },
        changeTecnicalSpecifications({ type, target }) {
            this.record_products[target.id - 1].technical_specifications = target.value;
        },
        // changeMeasurementUnit(index, id){
        //     this.record_products[index-1].measurement_unit_id = id;
        // },
        // fetchDataRecord(){
        //     // if (this.record.warehouse_id != '' && this.record.warehouse_id != this.compare_contracting_department_id) {
        //     //     this.compare_contracting_department_id = this.record.warehouse_id;
        //     //     this.getWarehouseProducts();
        //     // }
        // },
    },
    watch: {
        // record: {
        //     deep: true,
        //     handler: 'fetchDataRecord'
        // },
        product(res) {
            if (res) {
                axios.get('/warehouse/get-warehouse-product/' + res).then(response => {
                    if (response.data.record) {
                        var product = response.data.record;
                        this.record_products.push({
                            id: res,
                            name: product.name,
                            quantity: 0,
                            technical_specifications: '',
                            measurement_unit: product.measurement_unit.name,
                        });
                    }
                });
            }
        },
    }
};
</script>
