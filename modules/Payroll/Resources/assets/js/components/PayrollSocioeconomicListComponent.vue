<template>
    <div>
        <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableResults">
            <div slot="id" slot-scope="props" class="text-center">
                <button @click.prevent="setDetails('SocioeconomicInfo', props.row.id, 'PayrollSocioeconomicInfo')"
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
        </v-client-table>
        <payroll-socioeconomic-info
            ref="SocioeconomicInfo">
        </payroll-socioeconomic-info>
    </div>                       
</template>
<script>
    export default {
        data() {
            return {
                records: [],
                record: [],
                columns: ['payroll_staff.first_name', 'payroll_staff.last_name', 'marital_status.name', 'id'],
            }
        },

        created() {
            this.table_options.headings = {
                'payroll_staff.first_name': 'Nombre del Trabajador',
                'payroll_staff.last_name': 'Apellido del Trabajador',
                'marital_status.name': 'Estado civil',
                'id': 'Acción'
            };
            this.table_options.sortable = ['payroll_staff.first_name', 'payroll_staff.last_name'];
            this.table_options.filterable = ['payroll_staff.first_name', 'payroll_staff.last_name'];
        },

        mounted() {
            const vm = this;
            vm.initRecords(vm.route_list, '');
        },

        methods: {
            reset() {

            },

            /**
             * Método que establece los datos del registro seleccionado para el cual se desea mostrar detalles
             *
             * @method    setDetails
             *
             * @author     Pablo Sulbaran <psulbaran@cenditel.gob.ve>
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
            },

            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Pablo Sulbaran <psulbaran@cenditel.gob.ve>
             */
            reset() {
            },
        }
    };
</script>