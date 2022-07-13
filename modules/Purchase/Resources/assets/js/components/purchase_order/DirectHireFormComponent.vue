<template>
    <section>
        <purchase-show-errors ref="purchaseShowError" />
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Fecha de generación</label><br>
                    <label class="control-label">
                        <h5>{{ format_date(date) }}</h5>
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
            <!-- <div class="col-3">
                <div class="form-group is-required">
                    <label for="description">Código de la solicitud del requerimiento</label>
                    <input type="text" id="description" v-model="record.description" class="form-control">
                </div>
            </div> -->
            <div class="col-3">
                <div class="form-group is-required">
                    <label class="control-label" for="currencies">Tipo de moneda</label><br>
                    <select2 :options="currencies" id="currencies" v-model="currency_id"></select2>
                </div>
            </div>
            <div class="col-3" v-if="institutions.length > 1">
                <div class="form-group is-required">
                    <label class="control-label" for="institutions">Institución</label><br>
                    <select2 :options="institutions" id="institutions" v-model="record.institution_id" @input="getDepartments()"></select2>
                </div>
            </div>
            <div class="col-3" v-if="departments.length > 0">
                <div class="form-group is-required">
                    <label class="control-label" for="departments1">Unidad contratante</label><br>
                    <select2 :options="departments" id="departmentsss" v-model="record.contracting_department_id"></select2>
                </div>
            </div>
            <div class="col-3" v-if="departments.length > 0">
                <div class="form-group is-required">
                    <label class="control-label" for="departments2">Unidad usuaria</label><br>
                    <select2 :options="departments" id="departments2" v-model="record.user_department_id"></select2>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group is-required">
                    <label class="control-label" for="suppliers">Proveedor</label><br>
                    <select2 :options="suppliers" id="suppliers" v-model="purchase_supplier_id"></select2>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="supplier_direction">Dirección fiscal del proveedor</label>
                    <p v-html="supplier.direction"></p>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group is-required">
                    <label for="purchase_supplier_objects">Denominación del requerimiento</label>
                    <select2 :options="purchase_supplier_objects" id="purchase_supplier_objects" v-model='record.purchase_supplier_object_id'></select2>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group is-required">
                    <label for="funding_source">Fuente de financiamiento</label>
                    <input type="text" id="funding_source" v-model="record.funding_source" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group is-required">
                    <label for="description">Descripción de contratación</label>
                    <input type="text" id="description" v-model="record.description" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="supplier_rnc">Número de certificado (RNC)</label>
                    <p>
                        {{
                            supplier.rnc_certificate_number 
                            ? supplier.rnc_status+' - '+supplier.rnc_certificate_number
                            : 'No definido'
                        }}
                    </p>
                </div>
            </div>
            <div class="col-12">
                <br>
            </div>
            <div class="col-12">
                <h6 class="card-title">Lista de documentos requeridos</h6>
            </div>
            <div class="col-6" v-for="(file, idx) in files" :key="idx">
                <ul class="feature-list list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="feature-list-indicator bg-info">
                            <label style="margin-left: 2rem; width: 5rem;">
                                {{ traslate_name_files[idx] }}
                            </label>
                        </div>
                        <div class="feature-list-content p-0" style="margin-left: 8rem;">
                            <div class="feature-list-content-wrapper">
                                <div class="feature-list-content-left mr-2">
                                    <label class="custom-control">
                                        <button type="button" 
                                            data-toggle="tooltip" 
                                            v-has-tooltip class="btn btn-sm btn-info btn-import" 
                                            title="Presione para subir el archivo." 
                                            @click="setFile(idx)">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                        <input type="file" :id="idx" @change="uploadFile(idx, $event)" style="display:none;">
                                    </label>
                                </div>
                                <div class="feature-list-content-left">
                                    <div class="feature-list-subheading">
                                        <div v-if="files[idx]">
                                            {{ files[idx].name }}
                                        </div>
                                        <div v-show="!files[idx]">
                                            Cargar documento.
                                        </div>
                                    </div>
                                    <div class="feature-list-subheading" :id="'status_'+idx" style="display:none;">
                                        <span class="badge badge-info">
                                            <strong>Documento Cargado.</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 row">
                <div class="col-12">
                    <h6 class="card-title">Formas de pago</h6>
                </div>
                <div class="col-3">
                    <label for="pay_order">Orden de pago </label>
                    <input type="radio" id="pay_order" value="pay_order" v-model="record.payment_methods">
                </div>
                <div class="col-2">
                    <label for="direct">Directa </label>
                    <input type="radio" id="direct" value="direct" v-model="record.payment_methods">
                </div>
                <div class="col-2">
                    <label for="credit">Crédito </label>
                    <input type="radio" id="credit" value="credit" v-model="record.payment_methods">
                </div>
                <div class="col-2">
                    <label for="advance">Avances </label>
                    <input type="radio" id="advance" value="advance" v-model="record.payment_methods">
                </div>
                <div class="col-2">
                    <label for="others">Otras </label>
                    <input type="radio" id="others" value="others" v-model="record.payment_methods">
                </div>
            </div>

            <div class="col-12">
                <br>
                <hr>
            </div>

            <div class="col-12 row">
                <div class="col-12">
                    <h6 class="card-title">Factura</h6>
                </div>
                <div class="col-4">
                    <div class="form-group is-required">
                        <label for="receiver_invoice_to">Facturar a</label>
                        <input type="text" id="receiver_invoice_to" v-model="record.receiver.invoice_to" class="form-control">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group is-required">
                        <label for="receiver_send_to">Enviar a</label>
                        <input type="text" id="receiver_send_to" v-model="record.receiver.send_to" class="form-control">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group is-required">
                        <label for="receiver_rif">RIF</label>
                        <input type="text" id="receiver_rif" v-model="record.receiver.rif" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Tabla de requerimientos -->
            <div class="col-12">
                <br>
                <hr>
                <label><strong>NOTA</strong> Solo se podran seleccionar presupuestos base con un impuesto asignado</label>
            </div>

            <div class="col-12">
                <v-client-table :columns="columns" :data="requirements" :options="table_options">
                    <div slot="requirement_status" slot-scope="props" class="text-center">
                        <div class="d-inline-flex">
                            <span class="badge badge-info" v-show="props.row.requirement_status == 'PROCESSED'">
                                <strong>PROCESADO</strong>
                            </span>
                        </div>
                    </div>
                    <div slot="purchase_base_budget.tax.name" slot-scope="props" class="text-center">
                        {{ 
                            props.row.purchase_base_budget.tax 
                            ? (props.row.purchase_base_budget.tax.name+' '+
                                props.row.purchase_base_budget.tax.histories[props.row.purchase_base_budget.tax.histories.length-1].percentage)+'%'
                            : 'No definido' 
                        }}
                    </div>
                    <div slot="id" slot-scope="props" class="text-center">
                        <div class="feature-list-content-left mr-2" v-if="record.currency && props.row.purchase_base_budget.tax">
                            <label class="custom-control custom-checkbox">
                                <p-check class="p-icon p-smooth p-plain p-curve" 
                                    color="primary-o" 
                                    :value="'_'+props.row.id" 
                                    :id="'requirement_check_'+props.row.id" 
                                    :checked="indexOf(requirement_list, props.row.id, true)" 
                                    @change="requirementCheck(props.row)">
                                    <i slot="extra" class="icon fa fa-check"></i>
                                </p-check>
                            </label>
                        </div>
                    </div>
                </v-client-table>
            </div>
            <!-- ./Tabla de requerimientos -->

            <!-- Tabla de productos -->
            <div class="col-12">
                <v-client-table :columns="columns2" :data="record_items" :options="table2_options">
                    <div slot="unit_price" slot-scope="props">
                        <input type="number" 
                            class="form-control"
                            disabled
                            :value="searchBaseBudgetUnitPrice(props.row.pivot_purchase, props.row.purchase_requirement.purchase_base_budget_id)" 
                            :step="cualculateLimitDecimal()" 
                            @input="CalculateTot(record_items[props.index-1], props.index-1)">
                    </div>
                    <div slot="qty_price" slot-scope="props">
                        <h6 align="right">{{ CalculateQtyPrice(record_items[props.index-1].qty_price) }}</h6>
                    </div>
                </v-client-table>
            </div>
            <!-- ./Tabla de productos -->

            <!-- Totales -->
            <div class="col-12" v-if="record_items.length > 0">
                <div class="VueTables VueTables--client" style="margin-top: -1rem;">
                    <div class="table-responsive">
                        <table class="VueTables__table table table-striped table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="8.2%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="25%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.65%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%">
                                        <h6 align="right">SUB-TOTAL {{ currency_symbol }}</h6>
                                    </td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="20%">
                                        <h6 align="right">{{ sub_total.toFixed((record.currency)?currency_decimal_places:'') }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="8.2%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="25%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.6%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%">
                                        <h6 align="right">{{ tax?tax.percentage:'' }} % IVA {{ currency_symbol }}</h6>
                                    </td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="20%">
                                        <h6 align="right">{{ tax_value.toFixed((record.currency)?currency_decimal_places:'') }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="8.2%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="25%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.6%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%"></td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="16.75%">
                                        <h6 align="right">TOTAL {{ currency_symbol }}</h6>
                                    </td>
                                    <td style="border: 1px solid #dee2e6;" tabindex="0" width="20%">
                                        <h6 align="right">{{ (total).toFixed((record.currency)?currency_decimal_places:'') }}</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ./Totales -->

            <!-- Firmas autorizadas -->
            <div class="col-12 row">
                <div class="col-12">
                    <br>
                    <hr>
                    <h6 class="card-title">Firmas autorizadas</h6>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="prepared_by_id">Preparado por</label><br>
                        <select2 :options="employments" id="prepared_by_id" v-model="record.prepared_by_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label" for="reviewed_by_id">Revisado por</label><br>
                        <select2 :options="employments" id="reviewed_by_id" v-model="record.reviewed_by_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="verified_by_id">Verificado por</label><br>
                        <select2 :options="employments" id="verified_by_id" v-model="record.verified_by_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label" for="first_signature_id">Firmado por</label><br>
                        <select2 :options="employments" id="first_signature_id" v-model="record.first_signature_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="control-label" for="second_signature_id">Firmado por</label><br>
                        <select2 :options="employments" id="second_signature_id" v-model="record.second_signature_id"></select2>
                    </div>
                </div>
            </div>
            <!-- ./Firmas autorizadas -->
        </div>
        <div class="card-footer text-right">
            <button type="button" @click="reset()" class="btn btn-default btn-icon btn-round" data-toggle="tooltip"
				title="Borrar datos del formulario">
                <i class="fa fa-eraser"></i>
            </button>
            <button type="button" @click="redirect_back(url_list)" title="Cancelar y regresar"
                    class="btn btn-warning btn-icon btn-round" data-toggle="tooltip">
                <i class="fa fa-ban"></i>
            </button>
            <button type="button" @click="createRecord()" class="btn btn-success btn-icon btn-round"
                    data-toggle="tooltip" title="Guardar registro">
                <i class="fa fa-save"></i>
            </button>
        </div>
    </section>
</template>
<script>
export default {
    props: {
        record_edit: {
            type: Object,
            default: function() {
                return null;
            }
        },
        date: {
            type: String,
            default: function() {
                const dateJs = new Date();
                return dateJs.getFullYear() + '-' + (dateJs.getMonth() + 1) + '-' + dateJs.getDate();
            }
        },
        tax: {
            type: Object,
            default: function() {
                return null;
            }
        },
        tax_units: {
            type: Object,
            default: function() {
                return null;
            }
        },
        requirements: {
            type: Array,
            default: function() {
                return [];
            }
        },
        suppliers: {
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
        purchase_supplier_objects: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
        /** Lista de empleados laborales */
        employments: {
            type: Array,
            default: function() {
                return [{ id: '', text: 'Seleccione...' }];
            }
        },
    },
    data() {
        return {
            url_list: `${window.app_url}/purchase/direct_hire`,
            errors:[],
            records: [],
            record: {
                institution_id: '',
                contracting_department_id: '',
                user_department_id: '',
                warehouse_id: '',
                purchase_supplier_object_id: '',
                funding_source:'',
                description: '',
                fiscal_year_id: '',
                products: [],
                purchase_supplier_id: '',
                purchase_supplier_object: '',
                currency: null,

                contract_number: '',
                delivery_time: '',
                payment_methods: 'pay_order',
                receiver: {
                    invoice_to: '',
                    send_to: '',
                    rif: '',
                },

                // variables para firmas
                prepared_by_id: '',
                reviewed_by_id: '',
                verified_by_id: '',
                first_signature_id: '',
                second_signature_id: '',
            },
            // Editar registro
            recordEdit: false,
            // variables para proveedor
            purchase_supplier_id: '',
            supplier: {
                address: '',
                rnc: ''
            },
            // .variables para proveedor
            fiscalYear: null,
            institutions: [{ id: '', text: 'Seleccione...' }],
            departments: [],
            record_items: [],
            requirement_list: [],
            requirement_list_deleted: [],
            sub_total: 0,
            tax_value: 0,
            total: 0,
            currency_id: '',
            convertion_list: [],
            load_data_edit: false,
            files: {
                'start_minutes': null,
                'company_invitation': null,
                'certificate_receipt_of_offer': null,
                'motivated_act': null,
                'budget_availability': null,
            },
            traslate_name_files: {
                'start_minutes': 'Acta de inicio',
                'company_invitation': 'Invitación de la empresa',
                'certificate_receipt_of_offer': 'Acta de recepción de la oferta',
                'motivated_act': 'Acto motivado',
                'budget_availability': 'Disponibilidad presupuestaria',
            },

            columns: [
                'code',
                'description',
                'fiscal_year.year',
                'contrating_department.name',
                'user_department.name',
                'purchase_base_budget.currency.name',
                'purchase_base_budget.tax.name',
                'id'
            ],
            columns2: ['requirement_code',
                'name',
                'quantity',
                'measurement_unit.acronym',
                'unit_price',
                'qty_price',
            ],
            table2_options: {
                pagination: { edge: true },
                //filterByColumn: true,
                highlightMatches: true,
                texts: {
                    filter: "Buscar:",
                    filterBy: 'Buscar por {column}',
                    //count:'Página {page}',
                    count: ' ',
                    first: 'PRIMERO',
                    last: 'ÚLTIMO',
                    limit: 'Registros',
                    //page: 'Página:',
                    noResults: 'No existen registros',
                },
                sortIcon: {
                    is: 'fa-sort cursor-pointer',
                    base: 'fa',
                    up: 'fa-sort-up cursor-pointer',
                    down: 'fa-sort-down cursor-pointer'
                },
            },
        }
    },
    created() {
        const vm = this;
        
        vm.table_options.headings = {
            'code': 'Código',
            'description': 'Descripción',
            'fiscal_year.year': 'Año fiscal',
            'contrating_department.name': 'Departamento contatante',
            'user_department.name': 'Departamento Usuario',
            'purchase_base_budget.currency.name': 'Moneda',
            'purchase_base_budget.tax.name': 'Impuesto',
            'id': 'Acción'
        };

        vm.table_options.columnsClasses = {
            'code': 'col-xs-1 text-center',
            'description': 'col-xs-2',
            'fiscal_year.year': 'col-xs-1 text-center',
            'contrating_department.name': 'col-xs-2',
            'user_department.name': 'col-xs-2',
            'purchase_base_budget.currency.name': 'col-xs-1',
            'purchase_base_budget.tax.name' :'col-xs-1',
            'id': 'col-xs-1'
        };

        vm.table2_options.headings = {
            'requirement_code': 'Código de requerimiento',
            'name': 'Nombre',
            'quantity': 'Cantidad',
            'measurement_unit.acronym': 'Unidad de medida',
            'unit_price': 'Precio unitario sin IVA',
            'qty_price': 'Cantidad * precio unitario',
        };

        vm.table2_options.columnsClasses = {
            'requirement_code': 'col-xs-1 text-center',
            'name': 'col-xs-3',
            'quantity': 'col-xs-1',
            'measurement_unit.acronym': 'col-xs-2',
            'unit_price': 'col-xs-2',
            'qty_price': 'col-xs-2',
        };

        vm.table2_options.filterable = [];

        // vm.reset();
    },
    mounted() {
        const vm = this;
        axios.get('/purchase/get-institutions').then(response => {
            vm.institutions = response.data.institutions;

            if (vm.record_edit) {
                vm.record.institution_id = vm.record_edit[0]['institution_id'];
                vm.getDepartments();
            }
        });
        // vm.reset();
        vm.records = vm.requirements;
        if(vm.record_edit) {
            for( var i=0; i<vm.record_edit.length; i++ ) {
                vm.record.purchase_supplier_object_id = vm.record_edit[i]['purchase_supplier_object_id'];
                vm.record.funding_source = vm.record_edit[i]['funding_source'];
                vm.record.description = vm.record_edit[i]['description'];
                vm.record.fiscal_year_id = vm.record_edit[i]['fiscal_year_id'];
                vm.record.purchase_supplier_id = vm.record_edit[i]['purchase_supplier_id'];
                 vm.purchase_supplier_id = vm.record_edit[i]['purchase_supplier_id'];
                vm.currency_id = vm.record_edit[i]['currency_id'];
                vm.record.payment_methods = vm.record_edit[i]['payment_methods'];
                // variables para firmas
                vm.record.prepared_by_id = vm.record_edit[i]['prepared_by_id'];
                vm.record.reviewed_by_id = vm.record_edit[i]['reviewed_by_id'];
                vm.record.verified_by_id = vm.record_edit[i]['verified_by_id'];
                vm.record.first_signature_id = vm.record_edit[i]['first_signature_id'];
                vm.record.second_signature_id = vm.record_edit[i]['second_signature_id'];
                vm.indexOf(vm.requirements,vm.requirements.id,true);
                for (var j = 0; j < vm.requirements.length; j++) {
                    if(vm.requirements[j]['purchase_base_budget']['orderable_id'] != null) {
                        vm.requirementCheck(vm.requirements[j]);
                        }
                }
            }
        }
        /*if (vm.record_edit) {
            vm.load_data_edit = true;
            vm.currency_id = vm.record_edit.currency_id;
            vm.purchase_supplier_id = vm.record_edit.purchase_supplier_id;

            var prices = [];
            for (var i = 0; i < vm.record_edit.relatable.length; i++) {
                prices[vm.record_edit.relatable[i].purchase_requirement_item_id] = vm.record_edit.relatable[i].unit_price;
            }

            for (var i = 0; i < vm.record_edit.purchase_requirement.length; i++) {
                vm.addToList(vm.record_edit.purchase_requirement[i], prices);
            }
        } */

        axios.get('/purchase/get-fiscal-year').then(response => {
            vm.fiscalYear = response.data.fiscal_year;
        });
    },
    methods: {

        reset() {
            const vm = this;
            vm.record_items = [];
            vm.requirement_list = [];
            vm.requirement_list_deleted = [];
            vm.record = {
                institution_id: '',
                contracting_department_id: '',
                user_department_id: '',
                warehouse_id: '',
                purchase_supplier_object_id: '',
                funding_source:'',
                description: '',
                fiscal_year_id: '',
                products: [],
                purchase_supplier_id: '',
                purchase_supplier_object: '',
                currency: null,

                contract_number: '',
                delivery_time: '',
                payment_methods: 'pay_order',
                receiver: {
                    invoice_to: '',
                    send_to: '',
                    rif: '',
                },

                // variables para firmas
                prepared_by_id: '',
                reviewed_by_id: '',
                verified_by_id: '',
                first_signature_id: '',
                second_signature_id: '',
            };
            vm.sub_total = 0;
            vm.tax_value = 0;
            vm.total = 0;
            vm.$refs.purchaseShowError.reset();
        },

        uploadFile(inputID, e) {
            let vm = this;
            const files = e.target.files;
            Array.from(files).forEach(file => vm.addFile(file, inputID));
        },
        addFile(file, inputID) {
            if (!file.type.match('application/pdf')) {
                this.showMessage(
                    'custom', 'Error', 'danger', 'screen-error', 'Solo se permiten archivos pdf.'
                );
                return;
            } else {
                this.files[inputID] = file;
                $('#status_' + inputID).show("slow");
            }
        },

        addDecimals(value) {
            return parseFloat(value).toFixed(this.currency_decimal_places);
        },

        indexOf(list, id, returnBoolean) {
            for (var i = list.length - 1; i >= 0; i--) {
                if (list[i].id == id) {
                    return (returnBoolean) ? true : i;
                }
            }
            return (returnBoolean) ? false : -1;
        },

        requirementCheck(record) {
            axios.get('/purchase/get-convertion/' + this.currency_id + '/' + record.purchase_base_budget.currency_id)
                .then(response => {
                    if (record.purchase_base_budget.currency_id != this.currency_id && !response.data.record) {

                        if ($('#requirement_check_' + record.id + ' input:checkbox').prop('checked')) {
                            this.showMessage(
                                'custom', 'Error', 'danger', 'screen-error',
                                "No se puede realizar la conversión de " + this.record.currency.name +
                                " a " + record.purchase_base_budget.currency.name +
                                " ya que no existe una tasa asignada. Revisar las conversiones configuradas en el sistema."
                            );
                            $('#requirement_check_' + record.id + ' input:checkbox').prop('checked', false);
                        }
                    } else {
                        this.convertion_list.push(response.data.record);
                        this.addToList(record);
                    }
                });
        },

        addToList: function(record, prices) {
            var pos = this.indexOf(this.requirement_list, record.id);
            // se agregan a la lista a guardar
            if (pos == -1) {
                for (var i = 0; i < record.purchase_requirement_items.length; i++) {
                    record.purchase_requirement_items[i].requirement_code = record.code;
                    record.purchase_requirement_items[i].unit_price = (prices) ? prices[record.purchase_requirement_items[i].id] : 0;
                }

                // saca de la lista de registros eliminar
                pos = this.indexOf(this.requirement_list_deleted, record.id);
                if (pos != -1) {
                    this.requirement_list_deleted.splice(pos, 1);
                }

                this.requirement_list.push(record);
                this.record_items = this.record_items.concat(record.purchase_requirement_items);
            } else {
                // se sacan de la lista a guardar
                var record_copy = this.requirement_list.splice(pos, 1)[0];
                var pos = this.indexOf(this.requirement_list_deleted, record_copy.id);

                // agrega a la lista de registros a eliminar
                if (pos == -1) {
                    this.requirement_list_deleted.push(record_copy);
                }

                for (var i = 0; i < record.purchase_requirement_items.length; i++) {
                    for (var x = 0; x < this.record_items.length; x++) {
                        if (this.record_items[x].id == record.purchase_requirement_items[i].id) {
                            delete this.record_items[x].qty_price;
                            this.record_items.splice(x, 1);
                            break;
                        }
                    }
                }
            }
            this.CalculateTot();
        },

        /**
         * [CalculateTot Calcula el total del debe y haber del asiento contable]
         * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
         * @param  {[type]} r   [información del registro]
         * @param  {[type]} pos [posición del registro]
         */
        CalculateTot(item, pos) {
            const vm = this;
            // console.log(item)
            // vm.record_items[pos] = item;

            vm.sub_total = 0;
            vm.tax_value = 0;
            for (var i = vm.record_items.length - 1; i >= 0; i--) {
                var r = vm.record_items[i];
                r['qty_price'] = r.quantity * vm.searchBaseBudgetUnitPrice(r.pivot_purchase, r.purchase_requirement.purchase_base_budget_id);
                vm.sub_total += r['qty_price'];
            }
            vm.tax_value = vm.sub_total * (parseFloat(vm.tax.percentage) / 100);
            vm.total = vm.sub_total + vm.tax_value;
        },

        CalculateQtyPrice(qty_price) {
            return (qty_price) ? (qty_price).toFixed((this.record.currency) ? this.currency_decimal_places : '') : 0;
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

        createRecord() {
            /** Se obtiene y da formato para enviar el archivo a la ruta */
            let vm = this;
            var formData = new FormData();
            /**
             * Agrega los archivos a formData
             */
            for (const key in vm.files) {
                if (Object.hasOwnProperty.call(vm.files, key)) {
                    var inputFile = document.querySelector('#'+key);
                    if(inputFile.files[0]){
                        formData.append(key, inputFile.files[0]);
                    } else {
                        formData.append(key, '');
                    }
                }
            }

            formData.append("purchase_supplier_id", this.purchase_supplier_id);
            formData.append("currency_id", this.currency_id);
            // formData.append("subtotal", this.sub_total);

            if(this.requirement_list.length > 0){
                for(let i = 0; i < this.requirement_list.length; i++){
                    formData.append("requirement_list[]", JSON.stringify(this.requirement_list[i]));
                }
            } else {
                formData.append("requirement_list", '');
            }

            if(this.record_items.length > 0){
                for(let i = 0; i < this.record_items.length; i++){
                    formData.append("record_items[]", JSON.stringify(this.record_items[i]));
                }
            } else {
                formData.append("record_items", '');
            }

            formData.append("date", this.date);
            formData.append("institution_id", this.record.institution_id);
            formData.append("contracting_department_id", this.record.contracting_department_id);
            formData.append("user_department_id", this.record.user_department_id);
            formData.append("fiscal_year_id", this.fiscalYear.id);
            formData.append("purchase_supplier_id", this.record.purchase_supplier_id);
            formData.append("purchase_supplier_object_id", this.record.purchase_supplier_object_id);
            formData.append("funding_source", this.record.funding_source);
            formData.append("description", this.record.description);
            formData.append("payment_methods", this.record.payment_methods);


            // variables para firmas
            formData.append("prepared_by_id", this.record.prepared_by_id);
            formData.append("reviewed_by_id", this.record.reviewed_by_id);
            formData.append("verified_by_id", this.record.verified_by_id);
            formData.append("first_signature_id", this.record.first_signature_id);
            formData.append("second_signature_id", this.record.second_signature_id);

            vm.loading = true;

            if (!vm.record_edit) {
                axios.post('/purchase/direct_hire', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    vm.showMessage('store');
                    vm.$refs.purchaseShowError.refresh();
                    vm.loading = false;
                    location.href = this.route_list;
                }).catch(error => {
                    vm.errors = [];
                    if (typeof(error.response) !== "undefined") {
                        if (error.response.status == 422 || error.response.status == 500) {
                            for (var index in error.response.data.errors) {
                                if (error.response.data.errors[index]) {
                                    vm.errors.push(error.response.data.errors[index][0]);
                                }
                            }
                        }
                    }
                    vm.$refs.purchaseShowError.refresh();
                    vm.loading = false;
                });
            } else {
                formData.append("list_to_delete", JSON.stringify(this.requirement_list_deleted));

                axios.post('/purchase/direct_hire/' + vm.record_edit[0]['id'], formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    vm.showMessage('update');
                    vm.loading = false;
                    location.href = this.route_list;

                }).catch(error => {
                    if (typeof(error.response) !== "undefined") {
                        if (error.response.status == 422 || error.response.status == 500) {
                            for (const i in error.response.data.errors) {
                                vm.showMessage(
                                    'custom', 'Error', 'danger', 'screen-error', error.response.data.errors[i][0]
                                );
                            }
                        }
                    }
                    vm.loading = false;
                });
            }
        },

        /**
         * Obtiene un listado de los departamebtos de una institucion
         */
        getDepartments() {
            const vm = this;
            vm.departments = [];

            if (vm.record.institution_id != '') {
                axios.get('/get-departments/' + vm.record.institution_id).then(response => {
                    vm.departments = response.data;
                    if (vm.record_edit) {
                        vm.record.contracting_department_id = vm.record_edit[0]['contracting_department_id'];
                        vm.record.user_department_id = vm.record_edit[0]['user_department_id'];
                    }
                    // vm.getWarehouses();
                    vm.getWarehouseProducts();
                });
            }
        },

        /**
         * Obtiene un listado de los productos en almacen
         */
        getWarehouseProducts() {
            this.products = [];
            axios.get('/warehouse/get-warehouse-products/').then(response => {
                this.products = response.data;
            });
        },

        searchBaseBudgetUnitPrice(list, purchase_base_budget_id){
            for (let idx = 0; idx < list.length; idx++) {
                if (list[idx].relatable_type == 'Modules\\Purchase\\Models\\PurchaseBaseBudget' && list[idx].relatable_id == purchase_base_budget_id ) {
                    return list[idx].unit_price;
                }
            }
        }
    },
    watch: {
        currency_id: function(res, ant) {
            if (res != ant && !this.load_data_edit) {
                this.record_items = [];

                this.requirement_list_deleted = [];
                if (this.requirement_list.length > 0) {
                    this.requirement_list_deleted = this.requirement_list;
                }
                this.requirement_list = [];

                this.sub_total = 0;
                this.tax_value = 0;
                this.total = 0;
            } else {
                this.load_data_edit = false;
            }
            if (res) {
                axios.get('/currencies/info/' + res).then(response => {
                    this.record.currency = response.data.currency;
                })
            }
        },
        purchase_supplier_id(newVal) {
            if (newVal) {
                axios.get('/purchase/get-purchase-supplier-object/' + newVal).then(response => {
                    this.record.purchase_supplier_object = response.data;
                    this.record.purchase_supplier_id = newVal;
                });
                axios.get('/purchase/suppliers/' + newVal).then(response => {
                    this.supplier = response.data.records;
                });
            }
        },
    },
    computed: {
        currency_symbol: function() {
            return (this.record.currency) ? this.record.currency.symbol : '';
        },
        currency_decimal_places: function() {
            if (this.record.currency) {
                return this.record.currency.decimal_places;
            }
        },
        currency: function() {
            return (this.record.currency) ? this.record.currency : null;
        },
        getRecordItems: function() {
            return this.record_items;
        }
    }
};
</script>
