<template>
    <section id="PayrollEmploymentForm">
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
                <div class="col-md-4" id="helpEmploymentStaff">
                    <div class="form-group is-required">
                        <label>Trabajador:</label>
                        <select2 :options="payroll_staffs"
                            v-model="record.payroll_staff_id">
                        </select2>
                        <input type="hidden" v-model="record.id">
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentStartDate">
                    <div class="form-group is-required">
                        <label>Fecha de ingreso a la institución:</label>
                        <input @change="diff_datetimes(record.start_date)" type="date" class="form-control input-sm"
                            v-model="record.start_date"/>
                    </div>
                </div>
                <div v-show="record.active == false" class="col-md-4" id="helpEmploymentEndDate">
                    <div class="form-group">
                        <label>Fecha de egreso de la institución:</label>
                        <input @change="time_worked()" type="date" class="form-control input-sm"
                            v-model="record.end_date"/>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentIsActive">
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
                <div class="col-md-4" id="helpEmploymentEmail">
                    <div class="form-group">
                        <label>Correo Institucional:</label>
                        <input type="email" class="form-control input-sm"
                            v-model="record.institution_email"/>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentFunction">
                    <div class="form-group">
                        <label>Descripción de Funciones:</label>
                        <ckeditor :editor="ckeditor.editor" id="function_description" data-toggle="tooltip"
                                  title="Indique una descripción para las funciones"
                                  :config="ckeditor.editorConfig" class="form-control"
                                  name="function_description" tag-name="textarea" rows="3"
                                  v-model="record.function_description"></ckeditor>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentPossitionType">
                    <div class="form-group is-required">
                        <label>Tipo de Cargo:</label>
                        <select2 :options="payroll_position_types"
                            v-model="record.payroll_position_type_id">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentPossition">
                    <div class="form-group is-required">
                        <label>Cargo:</label>
                        <select2 :options="payroll_positions"
                            v-model="record.payroll_position_id">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentStaffType">
                    <div class="form-group is-required">
                        <label>Tipo de Personal:</label>
                        <select2 :options="payroll_staff_types"
                            v-model="record.payroll_staff_type_id">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentContractType">
                    <div class="form-group is-required">
                        <label>Tipo de Contrato:</label>
                        <select2 :options="payroll_contract_types"
                            v-model="record.payroll_contract_type_id">
                        </select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentInstitution">
                    <div class="form-group is-required">
                        <label>Institución:</label>
                        <select2 :options="institutions" @input="getDepartments()"
                                 v-model="record.institution_id"></select2>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentDepartment">
                    <div class="form-group is-required">
                        <label>Departamento:</label>
                        <select2 :options="departments" v-model="record.department_id"
                                 id="department"></select2>
                    </div>
                </div>
                <!-- TRABAJOS ANTERIOS -->
                <div class="col-md-12" id="helpEmploymentPreviousJobs">
                    <br>
                    <h6 class="card-title">Trabajos anteriores <i class="fa fa-plus-circle cursor-pointer"
                        @click="addPreviousJob()"></i></h6>
                    <div class="row" v-for="(job, index) in record.previous_jobs">
                        <div class="col-md-4">
                            <div class="form-group is-required">
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
                                    v-model="job.payroll_sector_type_id" @input="antiquity(); diff_datetimes(record.start_date);">
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
                                <input @change="antiquity(); diff_datetimes(record.start_date);" type="date" class="form-control input-sm"
                                    v-model="job.start_date"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group is-required">
                                <label>Fecha de cese:</label>
                                <input @change="antiquity(); diff_datetimes(record.start_date);" type="date" class="form-control input-sm"
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
                <!-- TRABAJOS ANTERIORES -->
            </div>
            <hr>
            <div class="row">
                <h6 class="card-title col-md-12"> Antigüedad del trabajador</h6>
                <div class="col-md-4" id="helpEmploymentYears">
                    <div class="form-group">
                        <label>Años en otras instituciones públicas:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.years_apn" disabled="true" v-input-mask
                            data-inputmask="'alias': 'numeric',
                                            'allowMinus': 'false'"/>
                    </div>
                </div>
                <div v-if="record.active" class="col-md-4" id="helpEmploymentYears">
                    <div class="form-group">
                        <label>Tiempo laborando en la institución/organización:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.institution_years" disabled="true"/>
                    </div>
                </div>
                <div class="col-md-4" id="helpEmploymentYears">
                    <div class="form-group">
                        <label>Total años de servicio:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.service_years" disabled="true"/>
                    </div>
                </div>
                <div v-if="!record.active" class="col-md-4" id="helpEmploymentYears">
                    <div class="form-group">
                        <label>Tiempo laborado en la institución/organización:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.time_worked" disabled="true"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer pull-right" id="helpParamButtons">
            <button class="btn btn-default btn-icon btn-round" data-toggle="tooltip" type="button"
                title="Borrar datos del formulario" @click="reset"><i class="fa fa-eraser"></i>
            </button>
            <button type="button" class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                    title="Cancelar y regresar" @click="redirect_back(route_list)">
                <i class="fa fa-ban"></i>
            </button>
            <button type="button" @click="generateRecord()" data-toggle="tooltip"
                    title="Guardar registro" class="btn btn-success btn-icon btn-round">
                <i class="fa fa-save"></i>
            </button>
        </div>
    </section>
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
                    institution_years: '',
                    service_years: '',
                    time_worked: '',
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
                }

                if(vm.record.id) {
                    axios.get(`${window.app_url}/payroll/employments/${vm.payroll_employment_id}`).then(response => {
                        let data = response.data.record;
                        vm.record.department_id = data.department_id;
                    });
                }
            },
            async getEmployment() {
                let vm = this;
                await axios.get(`${window.app_url}/payroll/employments/${vm.payroll_employment_id}`).then(response => {
                    let data = response.data.record;

                    vm.record = {
                        id: data.id,
                        payroll_staff_id: data.payroll_staff_id,
                        institution_id: data.department.institution_id,
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
                        department_id: data.department.id,
                        payroll_contract_type_id: data.payroll_contract_type_id,
                        previous_jobs: data.payroll_previous_job ? data.payroll_previous_job : '',
                    }

                    vm.antiquity();
                    vm.diff_datetimes(vm.record.start_date);
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

            generateRecord() {
                const vm = this;
                vm.errors = [];

                if (vm.record.previous_jobs){
                    for (let job of vm.record.previous_jobs){
                        if (job.organization_name == ''){
                            vm.errors.push('El campo nombre de la organización es obligatorio')
                        }
                        if (job.organization_phone == ''){
                            vm.errors.push('El campo teléfono de la organización es obligatorio')
                        }
                        if (job.payroll_sector_type_id == ''){
                            vm.errors.push('El campo tipo de sector es obligatorio')
                        }
                        if (job.payroll_position_id == ''){
                            vm.errors.push('El campo cargo es obligatorio')
                        }
                        if (job.payroll_staff_type_id == ''){
                            vm.errors.push('El campo tipo de personal es obligatorio')
                        }
                        if (job.start_date == ''){
                            vm.errors.push('El campo fecha de inicio es obligatorio')
                        }
                        if (job.end_date == ''){
                            vm.errors.push('El campo fecha de cese es obligatorio')
                        }
                    }
                }
                if (vm.errors < 1) {
                    vm.createRecord('payroll/employments');
                }
            },

            /**
             * Método que calcula los años en otras instituciones públicas
             *
             * @method     antiquity
             *
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             */
            antiquity() {
                const vm = this;
                vm.record.years_apn = 0;
                let years = 0;
                for (let job of vm.record.previous_jobs){
                    for (let sector_type of vm.payroll_sector_types) {
                        if(job.payroll_sector_type_id == sector_type.id && sector_type.text == 'Público'){
                            let now = job.start_date;
                            let ms = moment(job.end_date,"YYYY-MM-DD HH").diff(moment(now,"YYYY-MM-DD"));
                            let d = moment.duration(ms);

                            years += d._data.years;

                            vm.record.years_apn = years;
                        }
                    }
                }
            },

            /**
             * Método que calcula los años en otras instituciones públicas
             *
             * @method     time_worked
             *
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             */
            time_worked() {
                const vm = this;
                var now = vm.record.start_date;
                var ms = moment(vm.record.end_date,"YYYY-MM-DD HH").diff(moment(now,"YYYY-MM-DD HH"));
                var d = moment.duration(ms);
                let data_years = 0;
                let data_months = 0;
                let data_days = 0;
                if (d._data.years < 0){
                    data_years = d._data.years * -1;
                } else {
                    data_years = d._data.years;
                }
                if (d._data.months < 0){
                    data_months = d._data.months * -1;
                } else {
                    data_months = d._data.months
                }
                if (d._data.days < 0){
                    data_days = d._data.days * -1;
                } else {
                    data_days = d._data.days
                }

                let time = {
                    years: `Años: ${data_years}`,
                    months: `Meses: ${data_months}`,
                    days: `Días: ${data_days}`,
                };

                if (data_days > 0) {
                    vm.record.time_worked = time.years + ' ' + time.months + ' ' + time.days;
                } else {
                    vm.record.time_worked = 0;
                };
            },

            /**
             * Método que calcula la diferencia entre dos fechas con marca de tiempo
             *
             * @method     diff_datetimes
             *
             * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             * @param      {string}  dateThen    Fecha a comparar para obtener la diferencia con respecto a la fecha actual
             *
             * @return     {[type]}  Objeto con información de la diferencia obtenida entre las dos fechas
             */
            diff_datetimes(dateThen) {
                const vm = this;
                let now = moment().format("YYYY-MM-DD");
                let ms = moment(dateThen,"YYYY-MM-DD").diff(moment(now,"YYYY-MM-DD"));
                let d = moment.duration(ms);
                let data_years = 0;
                let data_months = 0;
                let data_days = 0;
                if (d._data.years < 0){
                    data_years = d._data.years * -1;
                }
                if (d._data.months < 0){
                    data_months = d._data.months * -1;
                }
                if (d._data.days < 0){
                    data_days = d._data.days * -1;
                }

                let time = {
                    years: `Años: ${data_years}`,
                    months: `Meses: ${data_months}`,
                    days: `Días: ${data_days}`,
                };

                if (data_days > 0) {
                    vm.record.institution_years = time.years + ' ' + time.months + ' ' + time.days;
                } else {
                    vm.record.institution_years = 0;
                };

                if(data_years) {
                    vm.record.service_years = data_years + vm.record.years_apn;
                } else {
                    vm.record.service_years = vm.record.years_apn;
                }
            },
            /**
             * Elimina la fila del elemento indicado
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             * @author Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
             *
             * @param  {integer}      index Indice del elemento a eliminar
             * @param  {object|array} el    Elemento del cual se va a eliminar un elemento
             */
            removeRow: function(index, el) {
                const vm = this;
                el.splice(index, 1);
                vm.antiquity();
                vm.diff_datetimes(vm.record.start_date);
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
