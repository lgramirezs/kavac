<template>
    <div>
        <button @click="addRecord('show_purchase_quotation_'+id, getUrlShow(`/purchase/quotation/${id}`), $event)" 
            class="btn btn-info btn-xs btn-icon btn-action" 
            title="Visualizar registro" 
            data-toggle="tooltip" 
            v-has-tooltip>
            <i class="fa fa-eye"></i>
        </button>
        <div class="modal fade text-left" tabindex="-1" role="dialog" :id="'show_purchase_quotation_'+id">
            <div class="modal-dialog vue-crud" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="reset" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="fa fa-list inline-block"></i>
                            Cotización
                        </h6>
                    </div>
                    <!-- Fromulario -->
                    <div class="modal-body" v-if="records">
                        <hr>
                        <h6 class="card-title">INFORMACIÓN DE LA COTIZACIÓN</h6>
                        <br>
                        <div class="row">
                            <div class="col-3">
                                <strong>Proveedor:</strong> 
                                {{ getNameStr(records.purchase_supplier) }}
                            </div>

                            <div class="col-3">
                                <strong>Tipo de moneda:</strong> 
                                {{ getNameStr(records.currency) }}
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">Documentos</h6>
                        <div class="row">
                            <div class="col-12">
                                <ul class="feature-list list-group list-group-flush">
                                    <li class="list-group-item" v-for="doc in records.documents" :key="'doc_'+doc.id">
                                        <div class="feature-list-indicator bg-info"></div>
                                        <div class="feature-list-content p-0">
                                            <div class="feature-list-content-wrapper">
                                                <a class="btn btn-simple btn-primary btn-events"
                                                    title="Presione para descargar el documento"
                                                    data-toggle="tooltip"
                                                    target="_blank"
                                                    :href="'/purchase/document/download/'+doc.file"
                                                    :download="records.code + ' - ' + doc.file"
                                                    >
                                                    <i class="fa fa-cloud-download fa-2x"></i>
                                                </a>
                                                <div class="feature-list-content-left ml-4">
                                                    <div class="feature-list-subheading">
                                                        <i class="font-weight-bold">
                                                            {{ records.code }} - {{ doc.file }}
                                                        </i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['id'],
    data() {
        return {
            records: [],
            files: {},
        }
    },
    created() {
        // 
    },
    mounted() {
        //
    },
    methods: {

        /**
         * Método que borra todos los datos del formulario
         *
         * @author  Juan Rosas <jrosas@cenditel.gob.ve> | <juan.rosasr01@gmail.com>
         */
        reset() {
            // 
        },

         getUrlShow(url){
            return `${window.app_url}${url}`;
        },

        getNameStr(str) {
            if(str) {
                for (const element in str) {
                    if(element == 'name') {
                        return str[element];
                    }
                }
            } else return '';
        }
        //
    },
    computed: {
        //
    }
};
</script>
