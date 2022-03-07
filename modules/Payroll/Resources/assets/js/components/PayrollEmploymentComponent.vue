<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Registrar los Datos Laborales</h6>
                    <div class="card-btns">
                        <a href="#" class="btn btn-sm btn-primary btn-custom" @click="redirect_back(route_list)"
                           title="Ir atrás" data-toggle="tooltip">
                            <i class="fa fa-reply"></i>
                        </a>
                        <a href="#" class="card-minimize btn btn-card-action btn-round" title="Minimizar"
                           data-toggle="tooltip">
                            <i class="now-ui-icons arrows-1_minimal-up"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <div class="container">
                            <div class="alert-icon">
                                <i class="now-ui-icons objects_support-17"></i>
                            </div>
                            <strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                    @click.prevent="errors = []">
                                <span aria-hidden="true">
                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                </span>
                            </button>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Trabajador:</label>
                                <select2 :options="payroll_staffs"
                                    v-model="record.payroll_staff_id">
                                </select2>
                                <input type="hidden" v-model="record.id">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Años en otras instituciones públicas:</label>
                                <input type="text" class="form-control input-sm"
                                    v-model="record.years_apn" v-input-mask
                                    data-inputmask="'alias': 'numeric',
                                                    'allowMinus': 'false'"/>
                            </div>
                        </div>
                        <div class="col-md-12" id="HelpTechnicalSpecifications">
                            <br>
                            <h6 class="card-title">Trabajos anteriores <i class="fa fa-plus-circle cursor-pointer"
                                @click="addPreviousJob()"></i></h6>
                            <div class="row" v-for="(job, index) in record.previous_jobs">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="organization_name">Nombre de la organización:</label>
                                        <input type="text" id="organization_name" class="form-control input-sm" data-toggle="tooltip"
                                            title="Nombre de la organización" v-model="job.organization_name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group is-required">
                                        <label>Teléfono de la organización</label>
                                        <input type="text" class="form-control input-sm" placeholder="+00-000-0000000"
                                               v-model="job.organization_phone" v-input-mask
                                               data-inputmask="'mask': '+99-999-9999999'"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group is-required">
                                        <label>Tipo de sector:</label>
                                        <select2 :options="payroll_sector_types"
                                            v-model="job.payroll_sector_type_id">
                                        </select2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group is-required">
                                        <label>Cargo:</label>
                                        <select2 :options="payroll_positions"
                                            v-model="job.payroll_position_id">
                                        </select2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group is-required">
                                        <label>Tipo de Personal:</label>
                                        <select2 :options="payroll_staff_types"
                                            v-model="job.payroll_staff_type_id">
                                        </select2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group is-required">
                                        <label>Fecha de inicio:</label>
                                        <input type="date" class="form-control input-sm"
                                            v-model="job.start_date"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de cese:</label>
                                        <input type="date" class="form-control input-sm"
                                            v-model="job.end_date"/>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <button class="mt-4 btn btn-sm btn-danger btn-action" type="button" @click="removeRow(index, record.previous_jobs)"
                                            title="Eliminar este dato" data-toggle="tooltip">
                                                <i class="fa fa-minus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Fecha de ingreso a la institución:</label>
                                <input type="date" class="form-control input-sm"
                                    v-model="record.start_date"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de egreso de la institución:</label>
                                <input type="date" class="form-control input-sm"
                                    v-model="record.end_date"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>¿Está Activo?</label>
                                <div class="col-md-12">
                                    <div class="col-12 bootstrap-switch-mini">
                                        <input id="active" name="active" type="checkbox" class="form-control bootstrap-switch"
                                            data-toggle="tooltip" data-on-label="SI" data-off-label="NO"
                                            title="Indique si el trabajador está activo o no"
                                            v-model="record.active" value="true"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" v-if="!record.active">
                            <div class="form-group is-required">
                                <label>Tipo de Inactividad:</label>
                                <select2 :options="payroll_inactivity_types"
                                    v-model="record.payroll_inactivity_type_id">
                                </select2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo Institucional:</label>
                                <input type="email" class="form-control input-sm"
                                    v-model="record.institution_email"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Descripción de Funciones:</label>
                                <ckeditor :editor="ckeditor.editor" id="function_description" data-toggle="tooltip"
                                          title="Indique una descripción para las funciones"
                                          :config="ckeditor.editorConfig" class="form-control"
                                          name="function_description" tag-name="textarea" rows="3"
                                          v-model="record.function_description"></ckeditor>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Tipo de Cargo:</label>
                                <select2 :options="payroll_position_types"
                                    v-model="record.payroll_position_type_id">
                                </select2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Cargo:</label>
                                <select2 :options="payroll_positions"
                                    v-model="record.payroll_position_id">
                                </select2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Tipo de Personal:</label>
                                <select2 :options="payroll_staff_types"
                                    v-model="record.payroll_staff_type_id">
                                </select2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Tipo de Contrato:</label>
                                <select2 :options="payroll_contract_types"
                                    v-model="record.payroll_contract_type_id">
                                </select2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Institución:</label>
                                <select2 :options="institutions" @input="getDepartments()"
                                         v-model="record.institution_id"></select2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Departamento:</label>
                                <select2 :options="departments" v-model="record.department_id"
                                         id="department"></select2>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer pull-right">
                    <button class="btn btn-default btn-icon btn-round" data-toggle="tooltip" type="button"
                        title="Borrar datos del formulario" @click="reset"><i class="fa fa-eraser"></i>
                    </button>
                    <button type="button" class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                            title="Cancelar y regresar" @click="redirect_back(route_list)">
                        <i class="fa fa-ban"></i>
                    </button>
                    <button type="button" @click="createRecord('payroll/employments')"
                        class="btn btn-success btn-icon btn-round">
                        <i class="fa fa-save"></i>
                    </button>
                </div>

            </div>
        </div>

    </div>
</template>
<script>
    export default {
        props: {
            payroll_employment_id: Number,
        },
        data() {
            return {
                record: {
                    id: '',
                    payroll_staff_id: '',
                    institution_id: '',
                    years_apn: '',
                    start_date: '',
                    end_date: '',
                    active: '',
                    payroll_inactivity_type_id: '',
                    institution_email: '',
                    function_description: '',
                    payroll_position_type_id: '',
                    payroll_position_id: '',
                    payroll_staff_type_id: '',
                    institution_id: '',
                    department_id: '',
                    payroll_contract_type_id: '',
                    previous_jobs: [],
                },
                errors: [],
                payroll_staffs: [],
                payroll_inactivity_types: [],
                payroll_position_types: [],
                payroll_positions: [],
                payroll_staff_types: [],
                departments: [],
                payroll_contract_types: [],
                institutions: [],
            }
        },
        methods: {
            async getDepartments() {
                let vm = this;
                vm.departments = [];
                if (vm.record.institution_id) {
                    await axios.get(`${window.app_url}/get-departments/${vm.record.institution_id}`).then(response => {
                        vm.departments = response.data;
                    });
                    /*if (vm.record.id) {
                        vm.record.department_id = vm.record.department.id;
                    }*/
                }
            },
            async getEmployment() {
                let vm = this;
                await axios.get(`${window.app_url}/payroll/employments/${vm.payroll_employment_id}`).then(response => {
                    let data = response.data.record;

                    vm.record = {
                        id: data.id,
                        payroll_staff_id: data.payroll_staff_id,
                        institution_id: data.institution_id,
                        years_apn: data.years_apn,
                        start_date: data.start_date,
                        end_date: data.end_date ? data.end_date : '',
                        active: data.active,
                        payroll_inactivity_type_id: data.payroll_inactivity_type_id ? data.payroll_inactivity_type_id : '',
                        institution_email: data.institution_email ? data.institution_email : '',
                        function_description: data.function_description ? data.function_description : '',
                        payroll_position_type_id: data.payroll_position_type_id,
                        payroll_position_id: data.payroll_position_id,
                        payroll_staff_type_id: data.payroll_staff_type_id,
                        department_id: data.department_id,
                        payroll_contract_type_id: data.payroll_contract_type_id,
                        previous_jobs: data.payroll_previous_job ? data.payroll_previous_job : '',
                    }

                    vm.record.institution_id = response.data.record.department.institution_id;
                });
            },

            /**
             * Agrega los campos para registrar el trabajo anterior de un trabajador
             *
             * @author Daniel Contreras <dcontreras@cenditel.gob.ve>
             */
            addPreviousJob() {
                const vm = this;
                vm.record.previous_jobs.push({
                    organization_name: '',
                    organization_phone: '',
                    payroll_sector_type_id: '',
                    payroll_position_id: '',
                    payroll_staff_type_id: '',
                    start_date: '',
                    end_date: '',
                    payroll_employment_id: '',
                });
            },
            reset() {
                this.record = {
                    id: '',
                    institution_id: '',
                    payroll_staff_id: '',
                    years_apn: '',
                    start_date: '',
                    end_date: '',
                    active: false,
                    payroll_inactivity_type_id: '',
                    institution_email: '',
                    function_description: '',
                    payroll_position_type_id: '',
                    payroll_position_id: '',
                    payroll_staff_type_id: '',
                    department_id: '',
                    payroll_contract_type_id: ''
                };
            },
        },
        created() {
            this.record.active = true;
            this.record.previous_jobs = [];
            this.getPayrollStaffs();
            this.getPayrollInactivityTypes();
            this.getPayrollPositionTypes();
            this.getPayrollPositions();
            this.getPayrollStaffTypes();
            this.getPayrollContractTypes();
            this.getInstitutions();
            this.getPayrollSectorTypes()
        },
        mounted() {
            if(this.payroll_employment_id) {
                this.getEmployment();
            }
            this.switchHandler('active');
        },
        watch: {
            record: {
                deep: true,
                handler: function() {
                    const vm = this;
                    if (!vm.record.active) {
                        $('#active').bootstrapSwitch('state', false, true);
                    }
                }
            }
        }
    };
</script>
