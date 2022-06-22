<template>
    <div id="BudgetSpecificDataModalLabel" class="modal fade" tabindex="-1" role="dialog" 
         aria-labelledby="BudgetSpecificDataModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:60rem">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h6>
                        <i class="icofont icofont-read-book ico-2x"></i>
                         Información Detallada de créditos adicionales
                    </h6>
                </div>

                <div class="modal-body">
                    <div class="tab-content">   
                        <div class="tab-pane active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Fecha de creación:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ format_date(record.created_at) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Institución:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.institution.name}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Documento:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.document }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Descripción:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.description }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                    <button type="button" class="btn btn-default btn-sm btn-round btn-modal-close"
                            data-dismiss="modal">
                        Cerrar
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    code: '',
                    active: '',
                    description: '',
                    created_at: '',
                    to_date: '',
                    specificable_id: '',
                    document: '',
                    institution: {}
                },
                errors: [],
            }
        },
        created() {
              const vm = this;
           console.log(vm.record);
        
        },
        methods: {
            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Jesús Paredes<danielparedessotillo13@gmail.com>
             */
            reset() {
            },  
        },
    }
</script>