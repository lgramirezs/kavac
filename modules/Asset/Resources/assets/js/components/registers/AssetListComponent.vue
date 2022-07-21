<template>
    <v-client-table :columns="columns" :data="records" :options="table_options">
            <div slot="institution" slot-scope="props" class="text-center">
                <span>
                    {{ (props.row.institution)?props.row.institution.acronym:'N/A' }}
                </span>
            </div>
            <div slot="asset_condition" slot-scope="props" class="text-center">
                <span>
                    {{ (props.row.asset_condition)?props.row.asset_condition.name:'N/A' }}
                </span>
            </div>
            <div slot="asset_status" slot-scope="props" class="text-center">
                <span>
                    {{ (props.row.asset_status_id == 11)?props.row.asset_status.name + ': ' 
                                                        + props.row.asset_disincorporation_asset.asset_disincorporation.asset_disincorporation_motive.name:
                                                        props.row.asset_status.name }}
                </span>
            </div>
            <div slot="id" slot-scope="props" class="text-center">
                <div class="d-inline-flex">
                    <asset-info
                        :route_list="app_url+'/asset/registers/info/' + props.row.id">
                    </asset-info>
                   
                    <button 
                        @click="assignAsset(props.row.id)"
                        class="btn btn-primary btn-xs btn-icon btn-action"
                        title="Asignar Bien"
                        data-toggle="tooltip"
                        :disabled="((isAssigned(props.row.asset_asignation_asset))
                                    &&(props.row.asset_disincorporation_asset == null)
                                    &&(isReq(props.row.asset_request_asset))
                                    &&(props.row.asset_status_id == 10)
                                    &&(props.row.asset_condition_id == 1)
                                    &&(props.row.asset_type_id == 1))?false:true"
                        type="button" v-has-tooltip>
                        <i class="fa fa-filter"></i>
                    </button>
                    <button
                        @click="disassignAsset(props.row.id)"
                        class="btn btn-danger btn-xs btn-icon btn-action"
                        title="Desincorporar Bien"
                        data-toggle="tooltip"
                        :disabled="((isAssigned(props.row.asset_asignation_asset))
                                    &&(props.row.asset_disincorporation_asset == null)
                                    &&(isReq(props.row.asset_request_asset))
                                    &&(props.row.asset_type_id == 1))?false:true"
                        type="button" v-has-tooltip>
                        <i class="fa fa-chain"></i>
                    </button>

                    <button
                        @click="editForm(props.row.id)"
                        class="btn btn-warning btn-xs btn-icon btn-action"
                        title="Modificar registro"
                        data-toggle="tooltip"
                        :disabled="((isAssigned(props.row.asset_asignation_asset))
                                    &&(props.row.asset_disincorporation_asset == null)
                                    &&(isReq(props.row.asset_request_asset)))?false:true"
                        type="button"
                        v-has-tooltip>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button
                        @click="deleteRecord(props.row.id, '')"
                        class="btn btn-danger btn-xs btn-icon btn-action"
                        title="Eliminar registro"
                        data-toggle="tooltip"
                        :disabled="((isAssigned(props.row.asset_asignation_asset))
                                    &&(props.row.asset_disincorporation_asset == null)
                                    &&(isReq(props.row.asset_request_asset)))?false:true"
                        type="button" v-has-tooltip>
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </v-client-table>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
                supplier: [],
                columns: [
                    'inventory_serial', 'institution', 'asset_condition','asset_status','serial','marca','asset_institutional_code', 'id'
                ]
            }
        },
        created() {
            this.table_options.headings = {
                'inventory_serial': 'Código',
                'institution': 'Organización',
                'asset_condition': 'Condición física',
                'asset_status': 'Estatus de uso',
                'serial': 'Serial',
                'marca': 'Marca',
                'asset_institutional_code': 'Código de bien organizacional',
                'id': 'Acción'
            };
            this.table_options.sortable = [
                'inventory_serial', 'institution', 'asset_condition', 'asset_status', 'serial', 'marca', 'asset_institutional_code'
            ];
            this.table_options.filterable = [
                'inventory_serial', 'institution', 'asset_condition', 'asset_status', 'serial', 'marca', 'asset_institutional_code'
            ];
           this.table_options.orderBy = { 'column': 'id'};
        },
        mounted () {
            this.readRecords(this.route_list);
        },
        methods: {
            /**
             * Función que verifica si un bien está en proceso de solicitud,
             * entregado o rechazado
             *
             * @author Francisco J. P. Ruiz <javierrupe19@gmail.com>
             */
            isReq(value){

                if(value === null){
                    return true;
                }else{
                    if(value.asset_request.state === 'Entregados' || value.asset_request.state === 'Rechazado'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },

            /**
             * Función que verifica si un bien está asignado o ha sido entregado
             *
             *
             * @author Francisco J. P. Ruiz <javierrupe19@gmail.com>
             */
            isAssigned(value){

                if(value === null){
                    return true;
                }else{
                    if(value.asset_asignation.state === 'Entregados' || value.asset_request.state === 'Entrega parcial'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            /**
             * Inicializa los datos del formulario
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
             */
            reset() {

            },

            /**
             * Redirige al formulario de asignación de bienes institucionales
             *
             * @author Henry Paredes <hparedes@cenditel.gob.ve>
             *
             * @param [Integer] $id Identificador único del bien
             */
            assignAsset(id)
            {
                location.href = `${window.app_url}/asset/asignations/asset/${id}`;
            },

            /**
             * Redirige al formulario de desincorporación de bienes institucionales
             *
             * @author Henry Paredes <hparedes@cenditel.gob.ve>
             *
             * @param [Integer] $id Identificador único del bien
             */
            disassignAsset(id)
            {
                location.href = `${window.app_url}/asset/disincorporations/asset/${id}`;
            },
            /**
             * Reescribe el método initRecords para cambiar su comportamiento por defecto
             * Inicializa los registros base del formulario
             *
             * @author Henry Paredes <hparedes@cenditel.gob.ve>
             *
             * @param {string} url      Ruta que obtiene los datos a ser mostrado en listados
             * @param {string} modal_id Identificador del modal a mostrar con la información solicitada
             */
            initRecords(url, modal_id) {
                this.errors = [];
                this.reset();
                const vm = this;
                url = vm.setUrl(url);
               
                axios.get(url).then(response => {
                    if (typeof(response.data.records) !== "undefined") {
                        vm.records  = response.data.records;
                        vm.total    = response.data.total;
                        vm.lastPage = response.data.lastPage;
                        vm.$refs.tableMax.setLimit(vm.perPage);
                    }
                    if ($("#" + modal_id).length) {
                        $("#" + modal_id).modal('show');
                    }
                }).catch(error => {
                    if (typeof(error.response) !== "undefined") {
                        if (error.response.status == 403) {
                            vm.showMessage(
                                'custom', 'Acceso Denegado', 'danger', 'screen-error', error.response.data.message
                            );
                        }
                        else {
                            vm.logs('resources/js/all.js', 343, error, 'initRecords');
                        }
                    }
                });
            },
        }
    };
</script>
