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
                    <!-- <a class="btn btn-primary btn-xs btn-icon" :href="url_start_certificate + props.row.id"
                        title="Imprimir Acta de inicio" data-toggle="tooltip" v-has-tooltip target="_blank">
                        <i class="fa fa-print" style="text-align: center;"></i>
                    </a> -->
                    <a class="btn btn-primary btn-xs btn-icon" :href="purchase_order_pdf + props.row.id"
                        title="Imprimir orden de compra" data-toggle="tooltip" v-has-tooltip target="_blank">
                        <i class="fa fa-print" style="text-align: center;"></i>
                    </a>
                    <button @click="deleteRecord(props.row.id, '/purchase/direct_hire')"
                        class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro"
                        data-toggle="tooltip" v-has-tooltip>
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
            purchase_order_pdf: `${window.app_url}/purchase/direct_hire/purchase_order/pdf/`,
            columns: [
                'code',
                'fiscal_year.year',               
                'funding_source',
                'description',
                'id'
            ],
        }
    },
    created() {
        this.table_options.headings = {
            'code': 'Codigo',
            'fiscal_year.year': 'Año fiscal',          
            'funding_source': 'Fuente de financiamiento',
            'description': 'Denominación especifica del requerimiento',
            'id': 'Acción'
        };
        this.table_options.columnsClasses = {
            'code': 'col-xs-2 text-center',
            'fiscal_year.year': 'col-xs-2 text-center',                    
            'funding_source': 'col-xs-3 text-center',
            'description': 'col-xs-4 text-center',
            'id': 'col-xs-1'
        };
        this.table_options.sortable = ['fiscal_year.year'];
        this.table_options.filterable = ['fiscal_year.year'];
    },
    mounted() {
        const vm = this;
        axios.get(`${window.app_url}/purchase/direct_hire/vue-list`).then(response => {
            vm.records = response.data.records;
        });
    }
};
</script>
