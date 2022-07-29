<template>
    <section id="PayrollVacationRequestForm">
        <div class="card-body">
            <!-- mensajes de error -->
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
            <!-- ./mensajes de error -->
            <div class="row">
                <!-- código de la solicitud -->
                <div class="col-md-4" v-if="id > 0" id="helpPayrollVacationRequestCode">
                    <div class="form-group is-required">
                        <label>Código de la solicitud:</label>
                        <input type="text" readonly
                               data-toggle="tooltip" title=""
                               class="form-control input-sm"
                               v-model="record.code">
                    </div>
                </div>
                <!-- ./código de la solicitud -->
                <!-- fecha de la solicitud -->
                <div class="col-md-4" id="helpPayrollVacationRequestDate">
                    <div class="form-group is-required">
                        <label>Fecha de la solicitud:</label>
                        <input type="date" readonly
                               data-toggle="tooltip"
                               title="Fecha de generación de la solicitud"
                               class="form-control input-sm" v-model="record.created_at">
                        <input type="hidden" v-model="record.id">
                    </div>
                </div>
                <!-- ./fecha de la solicitud -->
                <!-- trabajador -->
                <div class="col-md-4" id="helpPayrollVacationRequestStaff">
                    <div class="form-group is-required">
                        <label>Trabajador:</label>
                        <select2 :options="payroll_staffs"
                                 @input="getPayrollStaffInfo()"
                                 v-model="record.payroll_staff_id">
                        </select2>
                    </div>
                </div>
                <!-- ./trabajador -->
                <!-- año del período vacacional -->
                <div class="col-md-4" id="helpPayrollVacationPeriodYear">
                    <div class="form-group is-required">
                        <label>Año del período vacacional:</label>
                        <v-multiselect :options="vacation_period_years" track_by="text"
                            :hide_selected="false" data-toggle="tooltip"
                            title="Indique los periodos vacaionales"
                            @input="getPayrollVacationPeriods(); addEndDate();"
                            v-model="record.vacation_period_year">
                        </v-multiselect>
                    </div>
                </div>
                <!-- ./año del período vacacional -->
            </div>
            <div class="row">
                <!-- fecha de inicio de vacaciones -->
                <div class="col-md-4" id="helpPayrollVacationStartDate">
                    <div class="form-group is-required">
                        <label>Fecha de inicio de vacaciones:</label>
                        <input type="date" id="start_date"
                               data-toggle="tooltip" title="Fecha de inicio de vacaciones"
                               :min="new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000)).toISOString().split('T')[0]" class="form-control input-sm no-restrict" v-model="record.start_date"
                               @input="addEndDate()">
                    </div>
                </div>
                <!-- ./fecha de inicio de vacaciones -->
                <!-- fecha de culminación de vacaciones -->
                <div class="col-md-4" id="helpPayrollVacationEndDate">
                    <div class="form-group is-required">
                        <label>Fecha de culminación de vacaciones:</label>
                        <input type="date" id="end_date"
                               disabled
                               data-toggle="tooltip"
                               title="Fecha de culminación de vacaciones"
                               class="form-control input-sm no-restrict" v-model="record.end_date">
                    </div>
                </div>
                <!-- ./fecha de culminación de vacaciones -->
                <!-- días solicitudos -->
                <div class="col-md-4" v-if="record.vacation_period_year.length > 0" id="helpPayrollVacationDaysRequested">
                    <div class="form-group is-required">
                        <label>Días solicitados:</label>
                        <input type="text"
                               data-toggle="tooltip" title="Indique la cantidad de días solicitados"
                               @input="updatePendingDays()"
                               class="form-control input-sm"
                               disabled
                               v-model="record.days_requested">
                    </div>
                </div>
                <!-- ./días solicitados -->
            </div>
            <section class="row" v-show="payroll_staff['id'] > 0">
                <div class="col-md-12">
                    <h6 class="card-title"> Información del trabajador </h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong> Nombres: </strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="payroll_staff_first_name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Apellidos:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="payroll_staff_last_name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Fecha de ingreso:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="payroll_staff_start_date"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Cargo:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="payroll_staff_position"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Dependencia:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="payroll_staff_department"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"  v-show="record.vacation_period_year.length > 0">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Días de disfrute según antigüedad:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="vacation_days_to_antiquity"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Días pendientes:</strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12" id="pending_days"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 with-border with-radius" :key="vacation_request_for_period['period']"
                             v-for="vacation_request_for_period in vacation_request_for_periods">
                            <div class="form-group">
                                <strong> Período: </strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12"> {{ vacation_request_for_period['period'] }} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <strong> Días Solicitados: </strong>
                                <div class="row" style="margin: 1px 0">
                                    <span class="col-md-12"> {{ vacation_request_for_period['days_requested'] }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="card-footer text-right" id="helpParamButtons">
            <button type="button" @click="reset()"
                    class="btn btn-default btn-icon btn-round" data-toggle="tooltip"
                    title="Borrar datos del formulario" v-has-tooltip>
                <i class="fa fa-eraser"></i>
            </button>
            <button type="button" @click="redirect_back(route_list)"
                    class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                    title="Cancelar y regresar" v-has-tooltip>
                <i class="fa fa-ban"></i>
            </button>
            <button type="button" @click="createRecord('payroll/vacation-requests')"
                    class="btn btn-success btn-icon btn-round" data-toggle="tooltip"
                    title="Guardar registro" v-has-tooltip>
                <i class="fa fa-save"></i>
            </button>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    id:                   '',
                    code:                 '',
                    status:               '',
                    days_requested:       '',
                    vacation_period_year: '',
                    start_date:           '',
                    end_date:             '',
                    institution_id:       '',
                    payroll_staff_id:     ''
                },

                errors:                       [],
                records:                      [],
                vacation_period_years:        [],
                payroll_staffs:               [],
                payroll_vacation_requests:    [],
                vacation_request_for_periods: [],
                holidays:                     [],
                payroll_vacation_policy:      {},
                payroll_staff:                {
                    id:                        '',
                    code:                      '',
                    first_name:                '',
                    last_name:                 '',
                    payroll_nationality_id:    '',
                    id_number:                 '',
                    passport:                  '',
                    email:                     '',
                    birthdate:                 '',
                    payroll_gender_id:         '',
                    emergency_contact:         '',
                    emergency_phone:           '',
                    parish_id:                 '',
                    address:                   '',
                    has_disability:            '',
                    disability:                '',
                    social_security:           '',
                    has_driver_license:        '',
                    payroll_license_degree_id: '',
                    payroll_blood_type_id:     ''
                }
            }
        },
        props: {
            id: {
                type:     Number,
                required: false,
                default:  ''
            }
        },
        mounted() {
            const vm = this;
            vm.getPayrollStaffs();
            vm.getPayrollVacationPolicy();
            vm.getHolidays();
            if (vm.id > 0) {
                vm.showRecord(vm.id);
            } else {
                vm.record.created_at = vm.format_date(new Date(), 'YYYY-MM-DD');
            }
        },
        created() {
            const vm = this;
            vm.reset();
        },
        methods: {
            /**
             * Método que permite borrar todos los datos del formulario
             *
             * @author    Henry Paredes <hparedes@cenditel.gob.ve>
             */
            reset() {
                const vm  = this;
                vm.record = {
                    id:                   '',
                    code:                 '',
                    status:               '',
                    days_requested:       '',
                    vacation_period_year: '',
                    start_date:           '',
                    end_date:             '',
                    institution_id:       '',
                    payroll_staff_id:     ''
                };
                vm.payroll_vacation_requests    = [];
                vm.vacation_request_for_periods = [];
                vm.record.created_at = vm.format_date(new Date(), 'YYYY-MM-DD');
            },
            /**
             * Método que obtiene la información del trabajador
             *
             * @author    Henry Paredes <hparedes@cenditel.gob.ve>
             */
            getPayrollStaffInfo() {
                const vm = this;
                if (vm.record.payroll_staff_id > 0) {
                    axios.get(`${window.app_url}/payroll/staffs/${vm.record.payroll_staff_id}`).then(response => {
                        vm.payroll_staff = response.data.record;
                        axios.get(
                            `${window.app_url}/payroll/get-vacation-requests/${vm.record.payroll_staff_id}`
                        ).then(response => {
                            /**
                             * Extraer dias solicitados
                             * Número de periodos por año
                             * 
                             * Calcular según politica vacacional:
                             * periodos validos
                             * dias pendiente por periodo
                             * dias acumulados
                             */
                            vm.payroll_vacation_requests = response.data.records;
                            
                            let payroll_staff_date = vm.format_date(
                                vm.payroll_staff['payroll_employment']['start_date'],
                                'YYYY-MM-DD'
                            );
                            let payroll_staff_year = payroll_staff_date.split('-')[0];

                            let year_now = new Date().getFullYear();
                            
                            vm.vacation_period_years = [];
                            vm.vacation_period_years.push({
                                "id":   "",
                                "text": "Seleccione..."
                            });
                            for (var i = parseInt(payroll_staff_year); i <= year_now; i++) {
                                if (i != parseInt(payroll_staff_year)) {
                                    vm.vacation_period_years.push({
                                        "id":   i,
                                        "text": i,
                                        "yearId": i - parseInt(payroll_staff_year)
                                    });
                                }
                            };

                            document.getElementById('payroll_staff_first_name').innerText =
                                vm.payroll_staff['first_name']
                                    ? vm.payroll_staff['first_name']
                                    : 'No definido';
                            document.getElementById('payroll_staff_last_name').innerText =
                                vm.payroll_staff['last_name']
                                    ? vm.payroll_staff['last_name']
                                    : 'No definido';
                            document.getElementById('payroll_staff_start_date').innerText =
                                vm.payroll_staff['payroll_employment']
                                    ? vm.payroll_staff['payroll_employment']['start_date']
                                        ? vm.payroll_staff['payroll_employment']['start_date']
                                        : 'No definido'
                                    : 'No definido';
                            document.getElementById('payroll_staff_department').innerText =
                                vm.payroll_staff['payroll_employment']
                                    ? vm.payroll_staff['payroll_employment']['department']
                                        ? vm.payroll_staff['payroll_employment']['department']['name']
                                            ? vm.payroll_staff['payroll_employment']['department']['name']
                                            : 'No definido'
                                        : 'No definido'
                                    : 'No definido';
                            document.getElementById('payroll_staff_position').innerText =
                                vm.payroll_staff['payroll_employment']
                                    ? vm.payroll_staff['payroll_employment']['payroll_position']
                                        ? vm.payroll_staff['payroll_employment']['payroll_position']['name']
                                            ? vm.payroll_staff['payroll_employment']['payroll_position']['name']
                                            : 'No definido'
                                        : 'No definido'
                                    : 'No definido';
                        });
                    });
                }
            },
            updatePendingDays() {
                const vm = this;
                let vacation_days_to_antiquity = document.getElementById('vacation_days_to_antiquity');
                if (vacation_days_to_antiquity) {
                    let pending_days = document.getElementById('pending_days');
                    if (pending_days) {
                        if (parseInt(vm.record['days_requested']) > parseInt(pending_days.innerText)) {
                            vm.record['days_requested'] = parseInt(pending_days.innerText);
                        }
                    }
                }
            },
            getPayrollVacationPolicy() {
                const vm = this;
                axios.get(`${window.app_url}/payroll/get-vacation-policy`).then(response => {
                    vm.payroll_vacation_policy = response.data.record;
                });
            },
            getPayrollVacationPeriods() {
                const vm = this;
                vm.vacation_request_for_periods = [];
                vm.record.days_requested = 0;
                let days_requested = 0;
                let year = 0;
                if (vm.record.vacation_period_year.length > 0) {
                    $.each(vm.payroll_vacation_requests, function(index, field) {
                        if ((vm.record.vacation_period_year == field['vacation_period_year']) &&
                            (vm.record.id != field['id']) ) {
                            days_requested += field['days_requested'];
                            vm.vacation_request_for_periods.push({
                                period: field['start_date'] + ' - ' + field['end_date'],
                                days_requested: field['days_requested']
                            })
                        }
                    });
                    $.each(vm.vacation_period_years, function(index, field) {
                        $.each(vm.record.vacation_period_year, function(index, year) {
                            if (field['id'] == year.id) {
                                year = field['yearId'];

                                if (vm.payroll_vacation_policy.old_jobs && vm.payroll_staff.payroll_employment
                                        && parseInt(vm.payroll_staff.payroll_employment.years_apn) > 0) {

                                    let maximum_days_per_year;

                                    if (vm.record.days_requested <= 0) {
                                        maximum_days_per_year = (vm.payroll_vacation_policy['additional_days_per_year'] * (year +
                                            parseInt(vm.payroll_staff.payroll_employment.years_apn))) +
                                            vm.payroll_vacation_policy['vacation_days'] <=
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year ?
                                            (vm.payroll_vacation_policy['additional_days_per_year'] * (year +
                                            parseInt(vm.payroll_staff.payroll_employment.years_apn))) +
                                            vm.payroll_vacation_policy['vacation_days'] :
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year
                                    } else {
                                        maximum_days_per_year = (vm.payroll_vacation_policy['additional_days_per_year'] * year) +
                                            vm.payroll_vacation_policy['vacation_days'] <=
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year ?
                                            (vm.payroll_vacation_policy['additional_days_per_year'] * year) +
                                            vm.payroll_vacation_policy['vacation_days'] :
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year
                                    }

                                    maximum_days_per_year = maximum_days_per_year + parseInt(vm.record.days_requested);

                                    document.getElementById('vacation_days_to_antiquity').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days'] ?
                                        maximum_days_per_year :
                                        'No definido';

                                    document.getElementById('pending_days').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days'] ?
                                        maximum_days_per_year :
                                        'No definido';
                                    vm.updatePendingDays();
                                } else if (year >= vm.payroll_vacation_policy.from_year) {
                                    let maximum_days_per_year;

                                    maximum_days_per_year = (vm.payroll_vacation_policy['additional_days_per_year'] * (year -
                                            vm.payroll_vacation_policy.from_year + 2)) + vm.payroll_vacation_policy['vacation_days'] -
                                            vm.payroll_vacation_policy['additional_days_per_year'] <=
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year ?
                                            (vm.payroll_vacation_policy['additional_days_per_year'] * (year -
                                            vm.payroll_vacation_policy.from_year + 2)) + vm.payroll_vacation_policy['vacation_days'] -
                                            vm.payroll_vacation_policy['additional_days_per_year'] :
                                            vm.payroll_vacation_policy.maximum_additional_days_per_year

                                    maximum_days_per_year = vm.record.days_requested ? maximum_days_per_year + parseInt(vm.record.days_requested) :
                                    vm.payroll_vacation_policy['vacation_days'];

                                    document.getElementById('vacation_days_to_antiquity').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days'] ?
                                        maximum_days_per_year :
                                        'No definido';

                                    document.getElementById('pending_days').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days'] ?
                                        maximum_days_per_year :
                                        'No definido';
                                    vm.updatePendingDays();
                                } else {
                                    document.getElementById('vacation_days_to_antiquity').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days']
                                            ? vm.payroll_vacation_policy['vacation_days']
                                            : 'No definido';
                                    document.getElementById('pending_days').innerText =
                                        vm.payroll_vacation_policy['additional_days_per_year'] && vm.payroll_vacation_policy['vacation_days']
                                            ? vm.payroll_vacation_policy['vacation_days'] - days_requested
                                            : 'No definido';
                                    vm.updatePendingDays();
                                }
                                vm.record.days_requested = document.getElementById('vacation_days_to_antiquity').innerText;
                            }
                        });
                    });
                }
            },
            /**
             * Reescribe el método showRecord para cambiar su comportamiento por defecto
             * Método que muestra datos de un registro seleccionado
             *
             * @author    Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             *
             * @param    {integer}    id    Identificador del registro a mostrar
             */
            async showRecord(id) {
                const vm = this;
                await axios.get(`${window.app_url}/payroll/vacation-requests/show/${id}`).then(response => {
                    vm.record = response.data.record;
                    vm.record.created_at = vm.format_date(response.data.record.created_at, 'YYYY-MM-DD');
                });
            },

            /**
             * Método que carga los días feriados
             *
             * @author  Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
             *
             */
            getHolidays() {
                const vm = this;
                let url = vm.setUrl('payroll/get-holidays');

                axios.get(url).then(response => {
                    if (typeof(response.data) !== "undefined") {
                        vm.holidays = response.data;
                    }
                });
            },

            /**
             * Método que agrega la fecha de finalización de las vacaciones
             *
             * @author  Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
             *
             */
            addEndDate() {
                const vm = this;
                let remaining = 0;
                let dias = parseInt(vm.record.days_requested);
                let date = '';
                let holidayDiscount = [];
                let start_date = new Date(vm.record.start_date);

                if (vm.payroll_vacation_policy.business_days) {
                    const sumarLaborables = (f, n) => {
                        const options = { weekday: 'long'};
                        for(let i=0; i<n; i++) {
                            f.setTime( f.getTime() + (1000*60*60*24) );
                            /* Se identifica si existen sabados o domingos en el periodo establecido */
                            if( (f.getDay()==6) || (f.getDay()==0) ) {
                                /* Si existe un dia no laborable se hace el bucle una unidad mas larga */
                                dias++;
                            }
                        }
                    }

                    let i_date = vm.add_period(vm.record.start_date, dias, 'days', 'YYYY-MM-DD');
                    i_date = i_date.replaceAll('-', '');
                    for (let holiday of vm.holidays) {
                        if (holiday.text != 'Seleccione...') {
                            let hDate = new Date(holiday.text);
                            let holidayDate = holiday.text.replaceAll('-', '');

                            hDate.setTime( hDate.getTime() + (1000*60*60*24) );

                            /* Se identifica si existen sabados o domingos en el periodo establecido */
                            if( (hDate.getDay()!=6) && (hDate.getDay()!=0) ) {
                                /* Se busca si existe un día feriado dentro del periodo establecido */
                                if (holidayDate >= vm.record.start_date.replaceAll('-', '') && holidayDate <= i_date) {
                                    holidayDiscount.push(holiday.text);
                                }
                            }
                        }
                    }

                    dias = dias + holidayDiscount.length + 1;

                    sumarLaborables(start_date, dias);

                    date = vm.add_period(vm.record.start_date, dias, 'days', 'YYYY-MM-DD');

                    let endDate = new Date(date);
                    endDate.setTime( endDate.getTime() + (1000*60*60*24) );
                    console.log(date)

                    /* Se identifica si hay un día domingo en el periodo establecido */
                    if(endDate.getDay()==0) {
                        /* Si existe se agrega un día mas a la fecha */
                        endDate.setTime( endDate.getTime() + (1000*60*60*24) );
                    }
                    /* Se identifica si hay un día sábado en el periodo establecido */
                    if(endDate.getDay()==6) {
                        /* Si existe se agregan dos días mas a la fecha */
                        endDate.setTime( endDate.getTime() + (1000*60*60*24) + (1000*60*60*24) );
                    }
                    /* Se coloca la fecha en un formato válido y se agrega al record */
                    vm.record.end_date = vm.format_date(endDate, 'YYYY-MM-DD');
                } else {
                    let days_requested = vm.record.days_requested - 1;
                    date = vm.add_period(vm.record.start_date, days_requested, 'days', 'YYYY-MM-DD');
                    vm.record.end_date = date;
                }
            }
        }
    };
</script>
