<template>
    <section>
        <v-client-table :columns="columns" :data="records" :options="table_options">
            <div slot="id" slot-scope="props" class="text-center">
                <div class="d-inline-flex">
                    <purchase-order-direct-hire-show 
                        :id="props.row.id">
                    </purchase-order-direct-hire-show>
                    <button @click="editForm(props.row.id)" 
                        class="btn btn-warning btn-xs btn-icon btn-action" 
                        title="Modificar registro" 
                        data-toggle="tooltip" 
                        v-has-tooltip>
                        <i class="fa fa-edit"></i>
                    </button>
                    <!-- <a class="btn btn-primary btn-xs btn-icon" :href="url_start_certificate+props.row.id" title="Imprimir Acta de inicio" data-toggle="tooltip" v-has-tooltip target="_blank">
                        <i class="fa fa-print" style="text-align: center;"></i>
                    </a> -->
                    <button @click="deleteRecord(props.row.id,'/purchase/direct_hire')" 
                        class="btn btn-danger btn-xs btn-icon btn-action" 
                        title="Eliminar registro" 
                        data-toggle="tooltip" 
                        v-has-tooltip>
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </v-client-table>
        <hr>
    </section>
</template>
<script>
export default {
    data() {
        return {
            records: [],
            url_start_certificate: `${window.app_url}/purchase/direct_hire/start_certificate/pdf/`,
            columns: [
                'fiscal_year.year',
                'funding_source',
                'description',
                'id'
            ],
        }
    },
    created() {
        this.table_options.headings = {
            'fiscal_year.year': 'Año fiscal',
            'funding_source': 'Fuente de financiamiento',
            'description': 'Denominación especifica del requerimiento',
            'id': 'Acción'
        };
        this.table_options.columnsClasses = {
            'fiscal_year.year': 'col-xs-2',
            'funding_source': 'col-xs-4',
            'description': 'col-xs-5',
            'id': 'col-xs-1'
        };
    },
    mounted() {
        const vm = this;
        axios.get(`${window.app_url}/purchase/direct_hire/vue-list`).then(response => {
            vm.records = response.data.records;
        });
    }
};
</script>
