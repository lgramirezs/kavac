<template>
    <section>
        <button @click="addRecord('show_purchase_order_direct_hire_'+id, getUrlShow(`/purchase/direct_hire/${id}`), $event)" 
            class="btn btn-info btn-xs btn-icon btn-action" 
            title="Visualizar registro" 
            data-toggle="tooltip" 
            v-has-tooltip>
            <i class="fa fa-eye"></i>
        </button>
        <div class="modal fade text-left" tabindex="-1" role="dialog" :id="'show_purchase_order_direct_hire_'+id">
            <div class="modal-dialog vue-crud" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="reset" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="fa fa-list inline-block"></i>
                            Información de orden de compra: Contratación directa
                        </h6>
                    </div>
                    <!-- Fromulario -->
                    <div class="modal-body" v-if="records">
                        <hr>
                        <h6>Datos Básicos</h6>
                        <div class="row">
                            <div class="col-3">
                                <strong class="d-block">Fecha</strong> {{ format_date(records.date ? records.date : records.created_at) }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Año fiscal</strong> {{ records.fiscal_year.year }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Denominación del requerimiento</strong> 
                                {{ records.purchase_supplier_object.name }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Fuente de financiamiento</strong> {{ records.funding_source }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Descripción de contratación</strong> {{ records.description }}
                            </div>
                            <div class="col-12">
                                <br>
                                <h6 class="card-title">Datos institucionales</h6>
                            </div>
                            <div class="col-6">
                                <strong class="d-block">Institución</strong> {{ records.institution.name }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Unidad contratante</strong> {{ records.contrating_department.name }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Unidad usuario</strong> {{ records.user_department.name }}
                            </div>
                            <div class="col-12">
                                <br>
                                <h6 class="card-title">Datos de proveedor</h6>
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Proveedor</strong> {{ records.purchase_supplier.name }}
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Dirección fiscal del proveedor</strong> <p v-html="records.purchase_supplier.direction"></p>
                            </div>
                            <div class="col-3">
                                <strong class="d-block">Número de certificado (RNC)</strong> 
                                {{
                                    records.purchase_supplier.rnc_certificate_number 
                                    ? records.purchase_supplier.rnc_status+' - '+records.purchase_supplier.rnc_certificate_number
                                    : 'No definido'
                                }}
                            </div>
                        </div>
                        <br>
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
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props: ['id'],
    data() {
        return {
            records: null,
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
        }
    },
};
</script>
