<template>
    <div id="BudgetTransferListModel" class="modal fade" tabindex="-1" role="dialog" 
         aria-labelledby="BudgetSpecificDataModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:60rem">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h6>
                        <i class="icofont icofont-read-book ico-2x"></i>
                         Información Detallada de traspasos
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
                    <h6 class="text-center">Cuentas presupuestarias</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="4" class="border-right">
                                    Datos de Origen
                                </th>
                                <th colspan="4">
                                    Datos de Destino
                                </th>
                            </tr>
                            <tr>
                                <th>Acción Específica</th>
                                <th>Cuenta</th>
                                <th>Descripción</th>
                                <th class="border-right">Monto</th>
                                <th>Acción Específica</th>
                                <th>Cuenta</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in $parent.modification_accounts" :key="index">
                                <td class="text-center">{{ account.from_spac_description }}</td>
                                <td class="text-center">{{ account.from_code }}</td>
                                <td class="text-center">{{ account.from_description }}</td>
                                <td class="text-center border-right">
                                    {{ account.from_amount }}
                                </td>
                                <td class="text-center">{{ account.to_spac_description }}</td>
                                <td class="text-center">{{ account.to_code }}</td>
                                <td class="text-center">{{ account.to_description }}</td>
                                <td class="text-center">{{ account.to_amount }}</td>
                            </tr>
                        </tbody>
                    </table>

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
        },
        methods: {
            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Daniel Ordaz<danielordaz61@gmail.com>
             */
            reset() {
            },  
        },
    }
</script>