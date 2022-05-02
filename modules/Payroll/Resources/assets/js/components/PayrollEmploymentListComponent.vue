<template>
    <div>
        <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
            <div slot="id" slot-scope="props" class="text-center">
                <button @click.prevent="setDetails('EmploymentInfo', props.row.id, 'PayrollEmploymentInfo')"
                        class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
                        title="Ver registro" data-toggle="tooltip" data-placement="bottom" type="button">
                    <i class="fa fa-eye"></i>
                </button>
                <button @click="editForm(props.row.id)" v-if="!props.row.assigned"
                        class="btn btn-warning btn-xs btn-icon btn-action btn-tooltip"
                        title="Modificar registro" data-toggle="tooltip" data-placement="bottom" type="button">
                    <i class="fa fa-edit"></i>
                </button>
                <button @click="deleteRecord(props.row.id, '')"
                        class="btn btn-danger btn-xs btn-icon btn-action btn-tooltip"
                        title="Eliminar registro" data-toggle="tooltip" data-placement="bottom"
                        type="button">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <div slot="active" slot-scope="props" class="text-center">
                <span v-if="props.row.active">SI</span>
                <span v-else>NO</span>
            </div>
        </v-client-table>
        <payroll-employment-info
            ref="EmploymentInfo">
        </payroll-employment-info>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                records: [],
                record: [],
                columns: ['payroll_staff.first_name', 'institution_email', 'active', 'id'],
            }
        },

        created() {
            this.table_options.headings = {
                'payroll_staff.first_name': 'Trabajador',
                'institution_email': 'Correo Electrónico Institucional',
                'active': '¿Está Activo?',
                'id': 'Acción'
            };
            this.table_options.sortable = ['payroll_staff.first_name', 'institution_email'];
            this.table_options.filterable = ['payroll_staff.first_name', 'institution_email'];
        },

        mounted() {
            this.initRecords(this.route_list, '');
        },

        methods: {
            reset() {

            },

            /**
             * Método que establece los datos del registro seleccionado para el cual se desea mostrar detalles
             *
             * @method    setDetails
             *
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             * @param     string   ref       Identificador del componente
             * @param     integer  id        Identificador del registro seleccionado
             * @param     object  var_list  Objeto con las variables y valores a asignar en las variables del componente
             */
            setDetails(ref, id, modal ,var_list = null) {
                const vm = this;
                if (var_list) {
                    for(var i in var_list){
                        vm.$refs[ref][i] = var_list[i];
                    }
                }else{
                    vm.$refs[ref].record = vm.$refs.tableResults.data.filter(r => {
                        return r.id === id;
                    })[0];
                }
                vm.$refs[ref].id = id;

                $(`#${modal}`).modal('show');

                vm.antiquity(vm.$refs[ref].record);
                vm.time_worked(vm.$refs[ref].record);
                vm.diff_datetimes(vm.$refs[ref].record.start_date, vm.$refs[ref].record);
            },

            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Daniel Contreras <dcontreras@cenditel.gob.ve>
             */
            reset() {
            },

            /**
             * Método que calcula los años en otras instituciones públicas
             *
             * @method     antiquity
             *
             * @author     Daniel Contreras <dcontreras@cenditel.gob.ve>
             *
             */
            antiquity(record) {
                const vm = this;
                record.years_apn = 0;
                let years = 0;
                if (record.payroll_previous_job) {
                    for (let job of record.payroll_previous_job){
                        if(job.payroll_sector_type.name == 'Público'){
                            let now = job.start_date;
                            let ms = moment(job.end_date,"YYYY-MM-DD HH").diff(moment(now,"YYYY-MM-DD"));
                            let d = moment.duration(ms);

                            years += d._data.years;

                            record.years_apn = years;
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
            time_worked(record) {
                const vm = this;
                var now = record.start_date;
                var ms = moment(record.end_date,"YYYY-MM-DD HH").diff(moment(now,"YYYY-MM-DD HH"));
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
                    record.time_worked = time.years + ' ' + time.months + ' ' + time.days;
                } else {
                    record.time_worked = 0;
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
            diff_datetimes(dateThen, record) {
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
                    record.institution_years = time.years + ' ' + time.months + ' ' + time.days;
                } else {
                    record.institution_years = 0;
                };

                if(data_years) {
                    record.service_years = data_years + record.years_apn;
                } else {
                    record.service_years = record.years_apn;
                }
            },
        }
    };
</script>
