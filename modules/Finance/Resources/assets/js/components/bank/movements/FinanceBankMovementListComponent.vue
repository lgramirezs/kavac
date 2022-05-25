<template>
    <div>
        <h1>Componente de listar</h1>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                /*records: [],
                record: [],
                columns: ['payroll_staff.first_name', 'institution_email', 'active', 'id'],*/
            }
        },

        created() {
            /*this.table_options.headings = {
                'payroll_staff.first_name': 'Trabajador',
                'institution_email': 'Correo Electrónico Institucional',
                'active': '¿Está Activo?',
                'id': 'Acción'
            };
            this.table_options.sortable = ['payroll_staff.first_name', 'institution_email'];
            this.table_options.filterable = ['payroll_staff.first_name', 'institution_email'];*/
        },

        mounted() {
            //this.initRecords(this.route_list, '');
        },

        methods: {
            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Daniel Contreras <dcontreras@cenditel.gob.ve>
             */
            reset() {
            },
        }
    };
</script>
