<template>
    <section class="PurchaseFormComponent">
        <div class="form-horizontal">
            <purchase-show-errors ref="purchaseShowError" />
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <v-client-table :columns="columns" :data="records" :options="table_options" class="row">
                            <div slot="requirement_status" slot-scope="props" class="text-center">
                                <div class="d-inline-flex">
                                    <span class="badge badge-danger" v-show="props.row.requirement_status == 'WAIT'"> <strong>EN ESPERA</strong></span>
                                    <span class="badge badge-info" v-show="props.row.requirement_status == 'PROCESSED'"><strong>PROCESADO</strong></span>
                                    <span class="badge badge-success" v-show="props.row.requirement_status == 'BOUGHT'"> <strong>COMPRADO </strong></span>
                                </div>
                            </div>
                            <!-- <div slot="id" slot-scope="props" class="text-center">
                                <div class="feature-list-content-left mr-2">
                                    <label class="custom-control custom-checkbox">
                                        <p-check class="p-icon p-smooth p-plain p-curve"
                                                 color="primary-o"
                                                 :value="'_'+props.row.id"
                                                 :checked="indexOf(requirement_list, props.row.id, true)"
                                                 @change="requirementCheck(props.row)">
                                            <i slot="extra" class="icon fa fa-check"></i>
                                        </p-check>
                                    </label>
                                </div>
                            </div> -->
                        </v-client-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group is-required">
                    <label class="control-label">Tipo de moneda</label>
                    <select2 :options="currencies" v-model="currency_id" tabindex="1"></select2>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group is-required">
                    <label class="control-label">Impuesto</label>
                    <select2 :options="taxes" v-model="tax_id" tabindex="2"></select2>
                </div>
            </div>
        </div>
        <div class="form-horizontal" v-if="currency_id && tax_id">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="row">
                            <th tabindex="0" class="col-1" style="border: 1px solid #dee2e6; position: relative;">Código de requerimiento</th>
                            <th tabindex="0" class="col-3" style="border: 1px solid #dee2e6; position: relative;">Nombre</th>
                            <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">Cantidad</th>
                            <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">Unidad de medida</th>
                            <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;" id="pusiva" >Precio unitario sin IVA</th>
                            <th tabindex="0" class="col-2" style="border: 1px solid #dee2e6; position: relative;">cantidad * Precio unitario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="varr in record_items" class="row">
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-1">
                                {{ varr.requirement_code }}
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-3">
                                {{ varr.name }}
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                {{ varr.quantity }}
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <p v-if="varr.warehouse_product && varr.warehouse_product.measurement_unit">
                                    {{ varr.warehouse_product.measurement_unit.name }}
                                </p>
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <input type="number" :disabled="!currency_id" :step="cualculateLimitDecimal()" class="form-control" 
                                v-model="(varr.pivot_purchase)?varr.pivot_purchase.unit_price:varr.unit_price" @input="CalculateTot">
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">{{ CalculateQtyPrice(varr.qty_price) }}</h6>
                            </td>
                        </tr>
                        <tr class="row">
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-8"></td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">SUB TOTAL {{ currency_symbol }}</h6>
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">{{ (sub_total).toFixed((currency)?currency.decimal_places:'') }}</h6>
                            </td>
                        </tr>
                        <tr class="row">
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-8"></td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">{{ (history_tax && history_tax.percentage)?history_tax.percentage:'' }} % IVA {{ currency_symbol }}</h6>
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">{{ (tax).toFixed((currency)?currency.decimal_places:'') }}</h6>
                            </td>
                        </tr>
                        <tr class="row">
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-8"></td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">TOTAL {{ currency_symbol }}</h6>
                            </td>
                            <td style="border: 1px solid #dee2e6;" tabindex="0" class="col-2">
                                <h6 align="right">{{ (total).toFixed((currency)?currency.decimal_places:'') }}</h6>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

                        <button type="button"  @click="createRecord()"
                                class="btn btn-success btn-icon btn-round btn-modal-save"
                                title="Guardar registro">
                            <i class="fa fa-save"></i>
                        </button>
                    </div>
                </div>
                <!--<div class="card-footer text-right">
                    <buttonsDisplay route_list="/purchese/requirements" display="false" />
                </div>-->
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props: {
        records: {
            type: Array,
            default: function() {
                return [];
            }
        },
        currencies: {
            type: Array,
            default: function() {
                return [];
            }
        },
        taxes: {
            type: Array,
            default: function() {
                return [];
            }
        },
        base_budget_edit: {
            type: Object,
            default: function() {
                return null;
            }
        },
    },
    data() {
        return {
            record_items: [],
            requirement_list: [],
            requirement_list_deleted: [],
            columns: [
                'code',
                'description',
                'fiscal_year.year',
                'contrating_department.name',
                'user_department.name',
                'purchase_supplier_object.name',
                'requirement_status',
                // 'id'
            ],
            currency_id: '',
            history_tax: {
                id: '',
                percentage: 0,
            },
            tax_id: '',
            currency: null,
            sub_total: 0,
            tax: 0,
            total: 0,
            requirement_checked: [],
        }
    },
    methods: {
        /**
         * Reinicia los valores de los campos del formulario
         *
         * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve | pedrobui@gmail.com>
         */
        reset() {
          this.currency_id = '';
          this.currency = null;
          this.tax_id = '';
          for (var i = this.record_items.length - 1; i >= 0; i--) {
                var r = this.record_items[i];
                r.unit_price = 0;
            }
        },
        // indexOf(list, id, returnBoolean){
        //     for (var i = list.length - 1; i >= 0; i--) {
        //         if (list[i].id == id) {
        //             return (returnBoolean) ? true : i;
        //         }
        //     }
        //     return (returnBoolean) ? false : -1;
        // },
        // requirementCheck(record){

        //     var pos = this.indexOf(this.requirement_list, record.id);
        //     // se agregan a la lista a guardar
        //     if (pos == -1) {
        //         for (var i = 0; i < record.purchase_requirement_items.length; i++) {
        //             record.purchase_requirement_items[i].unit_price = 0;
        //             record.purchase_requirement_items[i].requirement_code = record.code;
        //         }

        //         // saca de la lista de registros eliminar
        //         pos = this.indexOf(this.requirement_list_deleted, record.id);
        //         if (pos != -1) {
        //             this.requirement_list_deleted.splice(pos,1);
        //         }

        //         this.requirement_list.push(record);
        //         this.record_items = this.record_items.concat(record.purchase_requirement_items);
        //     }else{
        //         // se sacan de la lista a guardar
        //         var record_copy = this.requirement_list.splice(pos,1)[0];
        //         var pos = this.indexOf(this.requirement_list_deleted, record_copy.id); 

        //         // agrega a la lista de registros a eliminar
        //         if (pos == -1) {
        //             this.requirement_list_deleted.push(record_copy);
        //         }

        //         for (var i = 0; i < record.purchase_requirement_items.length; i++) {
        //             for (var x = 0; x < this.record_items.length; x++) {
        //                 if (this.record_items[x].id == record.purchase_requirement_items[i].id) {
        //                     delete this.record_items[x].qty_price;
        //                     this.record_items.splice(x,1);
        //                     break;
        //                 }
        //             }
        //         }
        //     }
        //     this.CalculateTot();
        // },
        createRecord() {
            const vm = this;
            vm.errors = [];

            if (!vm.taxes || vm.taxes.length < 2) {
                vm.errors.push('Debe configurar el IVA en el sistema.');
                return;
            }
            if (!vm.currency_id) {
                vm.errors.push('Debe seleccionar un tipo de moneda.');
                return;
            }
            for (var i = 0; i < vm.record_items.length; i++) {
                if (!vm.record_items[i].qty_price) {
                    vm.errors.push('El precio unitario de los registros son obligatorios.');
                    return;
                }
                vm.record_items[i].qty_price = (vm.record_items[i].qty_price).toFixed((vm.currency) ? vm.currency.decimal_places : '');
            }
            vm.loading = true;
            if (!vm.base_budget_edit) {
                axios.post('/purchase/base_budget', {
                    'list': vm.requirement_list,
                    'currency_id': vm.currency_id,
                    'subtotal': vm.sub_total,
                    'tax_id': vm.history_tax.id,
                }).then(response => {
                    vm.loading = false;
                    vm.showMessage('store');
                    setTimeout(function() {
                        location.href = '/purchase/requirements';
                    }, 2000);
                }).catch(error => {
                    vm.loading = false;

                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                });
            } else {
                axios.put('/purchase/base_budget/' + vm.base_budget_edit.id, {
                    'list': vm.requirement_list,
                    'list_to_delete': vm.requirement_list_deleted,
                    'currency_id': vm.currency_id,
                    'subtotal': vm.sub_total,
                    'tax_id': vm.history_tax.id,
                }).then(response => {
                    vm.loading = false;
                    vm.showMessage('update');
                    setTimeout(function() {
                        location.href = '/purchase/requirements';
                    }, 2000);
                }).catch(error => {
                    vm.loading = false;

                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                });
            }
            $(".PurchaseFormComponent").offset().top
        },

        CalculateQtyPrice(qty_price) {
            return (qty_price) ? (qty_price).toFixed((this.currency) ? this.currency.decimal_places : '') : 0;
        },

        /**
         * Calcula el total del debe y haber del asiento contable
         *
         * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
         */
        CalculateTot() {
            const vm = this;
            vm.sub_total = 0;
            vm.tax = 0;
            for (var i = vm.record_items.length - 1; i >= 0; i--) {
                var r = vm.record_items[i];
                r['qty_price'] = r.quantity * r.unit_price;
                vm.sub_total += r['qty_price'];
            }
            vm.tax = vm.sub_total * (parseFloat(vm.history_tax.percentage) / 100);
            vm.total = vm.sub_total + vm.tax;
        },

        /**
         * Establece la cantidad de decimales correspondientes a la moneda que se maneja
         *
         * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
         */
        cualculateLimitDecimal() {
            var res = "0.";
            if (this.currency) {
                for (var i = 0; i < this.currency.decimal_places - 1; i++) {
                    res += "0";
                }
            }
            res += "1";
            return res;
        },
    },
    created() {
        this.table_options.headings = {
            'code': 'Código',
            'description': 'Descripción',
            'fiscal_year.year': 'Año fiscal',
            'contrating_department.name': 'Departamento contratante',
            'user_department.name': 'Departamento Usuario',
            'purchase_supplier_object.name': 'Tipo',
            'requirement_status': 'Estado',
            // 'id': 'Selección'
        };
        this.table_options.columnsClasses = {
            'code': 'col-xs-1 text-center',
            'description': 'col-xs-3',
            'fiscal_year.year': 'col-xs-1 text-center',
            'contrating_department.name': 'col-xs-2',
            'user_department.name': 'col-xs-2',
            'purchase_supplier_object.name': 'col-xs-2',
            'requirement_status': 'col-xs-1',
            // 'id'      : 'col-xs-1'
        };
    },
    mounted() {
        const vm = this;
        if (!vm.taxes || vm.taxes.length < 2) {
            vm.errors.push('Debe configurar el IVA en el sistema.');
        }
        if (vm.base_budget_edit) {
            vm.currency_id = vm.base_budget_edit.currency_id;

            vm.tax_id = vm.base_budget_edit.tax_id;

            var prices = [];
            for (var i = 0; i < vm.base_budget_edit.relatable.length; i++) {
                prices[vm.base_budget_edit.relatable[i].purchase_requirement_item_id] = vm.base_budget_edit.relatable[i].unit_price;
            }

            // for (var i = 0; i < vm.base_budget_edit.purchase_requirements.length; i++) {
            var requirement = vm.base_budget_edit.purchase_requirement;

            // if (vm.requirement_list.indexOf(requirement) == -1) {
            vm.requirement_list.push(requirement);
            // }

            var items = requirement.purchase_requirement_items;

            for (var x = 0; x < items.length; x++) {
                items[x].requirement_code = requirement.code;
                items[x].unit_price = (prices && prices[items[x].id]) ? prices[items[x].id] : 0;
                items[x].qty_price = items[x].quantity * items[x].unit_price;

                vm.record_items = vm.record_items.concat(items[x]);
            }
            // }
            vm.CalculateTot();
        }
    },
    watch: {
        currency_id(newVal) {
            if (newVal) {
                axios.get('/currencies/info/' + newVal).then(response => {
                    this.currency = response.data.currency;
                    this.CalculateTot();
                });
            }
        },
        tax_id(newVal){
            if (newVal) {
                axios.get('/get-last-history-tax/' + newVal).then(response => {
                    this.history_tax = response.data.record;
                    this.CalculateTot();
                });
            }
        }
    },
    computed: {
        currency_symbol: function() {
            return (this.currency) ? this.currency.symbol : '';
        }
    }
};
</script>
