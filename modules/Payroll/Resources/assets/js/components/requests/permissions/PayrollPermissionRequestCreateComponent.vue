<template>
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Datos de la Solicitud de Permisos</h6>
            <div class="card-btns">
                <a href="#" class="btn btn-sm btn-primary btn-custom" @click="redirect_back(route_list)"
                   title="Ir atrás" data-toggle="tooltip">
                    <i class="fa fa-reply"></i>
                </a>
                <a href="#" class="card-minimize btn btn-card-action btn-round" title="Minimizar" data-toggle="tooltip">
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
                        <label for="date">Fecha de Solicitud</label>
                        <input type="date" readonly data-toggle="tooltip"
                               id="date" class="form-control input-sm"
                               title="Indique la fecha de solicitud" v-model="record.date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="payrollStaff">Trabajador</label>
                        <select2 :options="payroll_staffs"
                                  @input="getPayrollStaff()"
                                  v-model="record.payroll_staff_id"></select2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="payrollPermissionPolicy">Tipo de Permiso</label>
                        <select2 :options="payroll_permission_policies" @input="getPayrollPermissionPolicy()"
                                 v-model="record.payroll_permission_policy_id"></select2>
                    </div>
                </div>
            </div>
            <label>Periodo del Permiso</label>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Desde:</label>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group input-sm">
                                    <span class="input-group-addon">
                                        <i class="now-ui-icons ui-1_calendar-60"></i>
                                    </span>
                                    <input type="date" id="start_date" :disabled="(record.payroll_permission_policy_id == '')" 
                                           @input="getcalculate()" data-toggle="tooltip" title="Indique la fecha de inicio del permiso"
                                           class="form-control no-restrict" :min="addBusinessDaysToDate(payrollPermissionPolicy.anticipation_day + 1)"
                                           v-model="record.start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="payrollPermissionPolicy.time_unit =='hours'">
                            <div class="form-group">
                                <div class="input-group input-sm">
                                    <span class="input-group-addon">
                                        <i class="now-ui-icons ui-1_calendar-60"></i>
                                    </span>
                                    <input type="time" id="start_time" :disabled="(record.payroll_permission_policy_id == '')" 
                                           @input="getcalculate()" data-toggle="tooltip" title="Indique la hora de inicio del permiso"
                                           class="form-control no-restrict" v-model="record.start_time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hasta:</label>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group input-sm">
                                    <span class="input-group-addon">
                                        <i class="now-ui-icons ui-1_calendar-60"></i>
                                    </span>
                                    <input type="date" id="end_date" :disabled="(record.payroll_permission_policy_id == '')"
                                           @input="getcalculate()" data-toggle="tooltip" title="Indique la fecha final del permiso"
                                           class="form-control input-sm no-restrict"
                                           :min="getMinDate()" :max="getMaxDate()" v-model="record.end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="payrollPermissionPolicy.time_unit =='hours'">
                            <div class="form-group">
                                <div class="input-group input-sm">
                                    <span class="input-group-addon">
                                        <i class="now-ui-icons ui-1_calendar-60"></i>
                                    </span>
                                    <input type="time" id="end_time" :disabled="(record.payroll_permission_policy_id == '')" 
                                           @input="getcalculate()" data-toggle="tooltip" title="Indique la hora final del permiso" required=""
                                           class="form-control no-restrict" :min="getMinTime()" v-model="record.end_time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="time_permission">Tiempo de permiso</label>
                        <input type="text" id="time_permission" class="form-control input-sm" data-toggle="tooltip"
                               title="Tiempo de permiso" disabled v-model="record.time_permission">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <label for="motive_permission">Motivo del permiso</label>
                        <input type="text" id="motive_permission" class="form-control input-sm" data-toggle="tooltip"
                            title="Indique el motivo del permiso" v-model="record.motive_permission">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="button" @click="reset()" class="btn btn-default btn-icon btn-round" data-toggle="tooltip"
                    title ="Borrar datos del formulario">
                    <i class="fa fa-eraser"></i>
            </button>
            <button type="button" @click="redirect_back(route_list)"
                        class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                        title="Cancelar y regresar">
                    <i class="fa fa-ban"></i>
            </button>
            <button type="button"  @click="createRecord('payroll/permission-requests')" title="Guardar registro"
                    class="btn btn-success btn-icon btn-round btn-modal-save">
                    <i class="fa fa-save"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    id: '',
                    date: '',
                    payroll_staff_id: '',
                    payroll_permission_policy_id: '',
                    start_date: '',
                    start_time: '',
                    end_date: '',
                    time_permission: '',
                    motive_permission: '',
                },
                errors: [],
                records: [],
                payroll_staffs: [],
                holidays : [],
                payrollPermissionPolicy: {
                    id:        '',
                    time_max:  '',
                    time_min:  '',
                    time_unit: ''

                },
                payroll_permission_policies: []
            }
        },
        methods: {
            loadForm(id){
                const vm = this;

                axios.get(`${window.app_url}/payroll/permission-requests/vue-info/${id}`).then(response => {
                    if(typeof(response.data.record != "undefined")){
                        vm.record = response.data.record;

                    }
                });
            },
            /**
             * Método que borra todos los datos del formulario
             *
             *
             */
            reset() {
                this.record = {
                    id: '',
                    date: new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000)).toISOString().split('T')[0],
                    payroll_staff_id: '',
                    payroll_permission_policy_id: '',
                    start_date: '',
                    start_time: '',
                    end_date: '',
                    time_permission: '',
                    motive_permission: '',
                };
                this.payrollPermissionPolicy = {
                    id:        '',
                    time_max:  '',
                    time_min:  '',
                    time_unit: ''

                };
            },
            getPayrollPermissionPolicy() {
                const vm = this;
                $.each(vm.payroll_permission_policies, function(index, field) {
                    if (field['id'] == '') {
                        vm.payrollPermissionPolicy = {
                                id:            '',
                                time_max:      '',
                                time_min:      '',
                                time_unit:     '',
                                business_days: '',

                            }
                    } else if (field['id'] == vm.record.payroll_permission_policy_id) {
                        vm.payrollPermissionPolicy = field;
                    }
                });
                if (vm.record.start_date < vm.addBusinessDaysToDate(vm.payrollPermissionPolicy.anticipation_day)) {
                    vm.record.start_date = '';
                    vm.record.start_time = '';
                    vm.record.end_date = '';
                    vm.record.end_time = '';
                    vm.record.time_permission = '';
                }
                if (vm.record.start_date > vm.getMinDate()) {
                    vm.record.start_date = '';
                    vm.record.start_time = '';
                    vm.record.end_date = '';
                    vm.record.end_time = '';
                    vm.record.time_permission = '';
                }

                if (vm.record.end_date > vm.getMaxDate()) {
                    vm.record.end_date = '';
                     vm.record.end_time = '';
                    vm.record.time_permission = '';
                }
            },

            addBusinessDaysToDate(days = 0, date = '') {
                const vm = this;
                let remaining = 0;
                
                if (date == '') {
                    date = new Date(new Date().getTime() - (new Date().getTimezoneOffset() * 60000)).toISOString().split('T')[0];
                }
                //console.log(days, date);
                
                const sumarNoLaborables = (f, n) => {
                    for(var i=0; i<n; i++) {
                        /** Se identifica si existen sabados o domingos en el periodo establecido */
                        if( (f.getDay()==6) || (f.getDay()==0) ) {
                            /** Si existe un dia no laborable se agrega a la regla del calendario */
                            remaining++;
                            n++;
                        }
                        f.setTime( f.getTime() + (1000*60*60*24) );
                    }

                }
                sumarNoLaborables(new Date(date.replaceAll('-', '/')), days);

                let newDate = vm.add_period(date, (remaining + days - 1), 'days');
                let arrayDate = newDate.split("/");
                return arrayDate[2] + '-' + arrayDate[1] + '-' + arrayDate[0];

            },

            getMinDate() {
                const vm = this;
                if (vm.payrollPermissionPolicy.time_unit == "days") {
                    if (vm.payrollPermissionPolicy.time_min) {
                        return vm.addBusinessDaysToDate(vm.payrollPermissionPolicy.time_min, vm.record.start_date);
                    } else {
                        return '';
                    }
                } else if (vm.payrollPermissionPolicy.time_unit == "weeks") {
                    if (vm.payrollPermissionPolicy.time_min) {
                        return vm.addBusinessDaysToDate((7 * vm.payrollPermissionPolicy.time_min), vm.record.start_date);
                    } else {
                        return '';
                    }
                } else if (vm.payrollPermissionPolicy.time_unit == "months") {
                    if (vm.payrollPermissionPolicy.time_min) {
                        let dateMin = vm.add_period(vm.record.start_date, vm.payrollPermissionPolicy.time_min, 'months');
                        let date = dateMin.split("/");
                        return date[2] + '-' + date[1] + '-' + date[0];
                    } else {
                        return '';
                    }
                } else {
                    return vm.record.start_date;
                }

            },
            getMinTime() {
                const vm = this;
                if ((vm.record.start_date != '') && (vm.end_date != '') && (vm.record.start_date == vm.record.end_date)) {
                    return vm.record.start_time;
                } else {
                    return '';
                }

            },
            setMinTime() {
                const vm = this;
                if ((vm.record.start_date != '') && (vm.end_date != '') && (vm.record.start_date == vm.record.end_date)) {
                    let start = vm.record.start_time.split(":");
                    let end = vm.record.end_time.split(":");
                    if (start[0] > end[0]) {
                        vm.record.end_time = '';
                        return;
                    } else if (start[0] == end[0]) {
                        if (start[1] > end[1]) {
                            vm.record.end_time = '';
                            return;
                        }
                    }
                }

            },
            getMaxDate() {
                const vm = this;
                if (vm.payrollPermissionPolicy.time_unit == "days") {
                    if (vm.payrollPermissionPolicy.time_max != '') {
                        return vm.addBusinessDaysToDate(vm.payrollPermissionPolicy.time_max, vm.record.start_date);
                    } else {
                        return '';
                    }
                } else if (vm.payrollPermissionPolicy.time_unit == "weeks") {
                    if (vm.payrollPermissionPolicy.time_max != '') {
                        return vm.addBusinessDaysToDate((7 * vm.payrollPermissionPolicy.time_max), vm.record.start_date);
                    } else {
                        return '';
                    }
                } else if (vm.payrollPermissionPolicy.time_unit == "months") {
                    if (vm.payrollPermissionPolicy.time_max != '') {
                        let dateMax = vm.add_period(vm.record.start_date, vm.payrollPermissionPolicy.time_max, 'months');
                        let date = dateMax.split("/");
                        return date[2] + '-' + date[1] + '-' + date[0];
                    } else {
                        return '';
                    }
                } else {
                    return vm.record.start_date;
                }
            },

            getPayrollStaff() {
                const vm = this;
                $.each(vm.payroll_staffs, function(index, field) {
                    if (field['id'] == '') {
                        vm.payrollStaff = '';
                    } else if (field['id'] == vm.record.payroll_staff_id) {
                        vm.payrollStaff = field['text'];
                    }
                });
            },

            getcalculate() {
                const vm = this;
                vm.record.time_permission = '';

                if (vm.record.start_date && vm.record.end_date) {
                    let start_date = new Date(document.getElementById('start_date').value.replaceAll('-', '/'));
                    let end_date   = new Date(document.getElementById('end_date').value.replaceAll('-', '/'));

                    let diff = end_date.getTime() - start_date.getTime()
                    let dias = diff/(1000*60*60*24)
                    let cont = 0;

                    const sumarLaborables = (f, n) => {
                        for(var i=0; i<n; i++) {
                            f.setTime( f.getTime() + (1000*60*60*24) );
                            /** Se identifica si existen sabados o domingos en el periodo establecido */
                            if( (f.getDay()==6) || (f.getDay()==0) ) {
                                /** Si existe un dia no laborable se hace el bucle una unidad mas larga */
                                dias--;
                            }
                        }

                    }

                    if (vm.payrollPermissionPolicy.business_days == true) {
                        let holidayDiscount = [];
                        for (let holiday of vm.holidays) {
                            if (holiday.text != 'Seleccione...') {
                                let holidayDate = new Date(holiday.text)
                                if (holidayDate.getTime() >= start_date && holidayDate.getTime() <= end_date) {
                                    holidayDiscount.push(holiday.text);
                                }
                            }
                        }

                        dias = dias - holidayDiscount.length;
                    }

                    sumarLaborables(start_date, dias);
                    vm.record.time_permission = (dias +1) + ' días';
                }
                if (vm.record.start_time && vm.record.end_time) {
                    const newDate = (partes) => {
                        var date = new Date(0);
                        date.setHours(partes[0]);
                        date.setMinutes(partes[1]);
                        return date;
                    }

                    const prefijo = (num) => {
                        return num < 10 ? ("0" + num) : num; 
                    }

                    var dateDesde = newDate(document.getElementById('start_time').value.split(":"));
                    var dateHasta = newDate(document.getElementById('end_time').value.split(":"));

                    var minutos = (dateHasta - dateDesde)/1000/60;
                    var horas = Math.floor(minutos/60);
                    minutos = minutos % 60;
                    let time = prefijo(horas) + ':' + prefijo(minutos);

                    if (vm.record.time_permission != '') {
                        vm.record.time_permission = ' ' + vm.record.time_permission + ' ' + time + ' horas.';
                    } else {
                        vm.record.time_permission += '.';
                    }

                }
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
        },

        mounted() {
            const vm = this;
            if(this.requestid){
                this.loadForm(this.requestid);
            }
            else {
                vm.record.date = moment(String(new Date())).format('YYYY-MM-DD');
            }

        },
        props: {
            requestid: {
                type: Number
            },
        },
        created() {
            const vm = this;
            vm.getPayrollStaffs();
            vm.getPayrollPermissionPolicies();
            vm.getHolidays();
        },
    };
</script>
