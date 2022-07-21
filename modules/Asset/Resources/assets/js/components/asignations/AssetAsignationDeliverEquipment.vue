<template>
    <div>
        <a class="btn btn-success btn-xs btn-icon btn-action" data-toggle="tooltip"
           href="#" title="Entregar Equipos"
           :disabled="(state == 'Asignado') ? false : true"
           @click="(state == 'Asignado') ? initRecords('add_delivery' + index) : viewMessage()">
           <i class="icofont icofont-computer"></i>
        </a>
        <div :id="'add_delivery' + index" class="modal fade text-left" tabindex="-1" role="dialog">
            <div class="modal-dialog vue-crud" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" @click="reset()">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="icofont icofont-tasks-alt ico-2x"></i>
                            Nueva gestión de entrega de equipos
                        </h6>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
							<ul>
								<li v-for="error in errors" :key="error">{{ error }}</li>
							</ul>
						</div>
                        <div class="tab-content">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <b>Seleccione los equipos a ser entregados</b>
                                </div>
                                <div class="col-md-12">
                                    <v-client-table ref="tableevent" :columns="columns_equipments" :data="equipments"
                                                    :options="table_options" @row-click="toggleActive">
                                        <div class="text-center" slot="h__check">
                                            <label class="form-checkbox">
                                                <input class="cursor-pointer" type="checkbox" v-model="selectAll"
                                                    @click="select()" />
                                            </label>
                                        </div>
                                        <div class="text-center" slot="check" slot-scope="props">
                                            <label class="form-checkbox">
                                                <input class="cursor-pointer" type="checkbox" :value="props.row.asset_id"
                                                    :id="'checkbox_'+ props.row.asset_id" v-model="selected" />
                                            </label>
                                        </div>
                                    </v-client-table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default btn-sm btn-round btn-modal-close" type="button"
                                data-dismiss="modal" @click="reset()">
                            Cerrar
                        </button>
                        <button class="btn btn-primary btn-sm btn-round btn-modal-save" type="button"
                                @click="deliverEquipment(index)">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data() {
            return {
                record: {
                    id:'',
                    asset_asignation_id: '',
                    equipments: {assigned: [], 
                                possible_deliveries: [],
                                delivered: []}
                },
                equipments: [],
                records: [],
                errors: [],
                columns_equipments: ['check', 'asset.inventory_serial','asset.serial','asset.marca','asset.asset_institutional_code'],

                selected: [],
                selectAll: false,

                table_options: {
                    rowClassCallback(row) {
                        var checkbox = document.getElementById('checkbox_' + row.asset_id);
                        return ((checkbox)&&(checkbox.checked))? 'selected-row cursor-pointer' : 'cursor-pointer';
                    },
                    headings: {
                        'asset.inventory_serial': 'Código',
                        'asset.serial': 'Serial',
                        'asset.marca': 'Marca',
                        'asset.asset_institutional_code': 'Código org.',
                    },
                    sortable: ['type','asset.inventory_serial', 'asset.serial', 'asset.marca', 'asset.asset_institutional_code'],
                    filterable: ['type','asset.inventory_serial', 'asset.serial', 'asset.marca', 'asset.asset_institutional_code']
                }
            }
        },
        props: {
            index: Number,
            route_list: String,
            state: String,
        },
        methods:{
            toggleActive({ row }) {
                const vm = this;
                var checkbox = document.getElementById('checkbox_' + row.asset_id);

                if ((checkbox)&&(checkbox.checked == false)){
                    var index = vm.selected.indexOf(row.asset_id);
                    if (index >= 0){
                        vm.selected.splice(index,1);
                    }
                    else {
                        checkbox.click();
                    }
                }
                else if ((checkbox)&&(checkbox.checked == true)){
                    var index = vm.selected.indexOf(row.asset_id);
                    if (index >= 0){
                        checkbox.click();
                    }
                    else {
                        vm.selected.push(row.asset_id);
                    }
                }
            },
            select() {
                const vm = this;
                vm.selected = [];
                $.each(vm.equipments, (index, campo) => {
                    var checkbox = document.getElementById('checkbox_' + campo.asset_id);

                    if (!vm.selectAll){
                        vm.selected.push(campo.asset_id);
                    }
                    else if (checkbox && checkbox.checked){
                        checkbox.click();
                    }
                });
            },
            /**
             * Método que borra todos los datos del formulario
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {
                this.record = {
                    id:'',
                    asset_asignation_id: '',
                    equipments: []
                };
                this.selected = [];
            },

            initRecords(modal_id){
                const vm = this;
                vm.reset();
                vm.record.asset_asignation_id = vm.index;

                vm.loadEquipment(vm.route_list);
                if ($("#" + modal_id).length) {
                    $("#" + modal_id).modal('show');
                }
            },
            loadEquipment(url){
				const vm = this;
                vm.equipments = [];
                url = vm.setUrl(url);

				axios.get(url).then(response => {
					vm.equipments = response.data.records.asset_asignation_assets;
                    vm.equipments = vm.equipments.filter(asset => asset.asset.asset_status_id == 1);
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

            viewMessage() {
                const vm = this;
                vm.showMessage(
                    'custom', 'Alerta', 'danger', 'screen-error',
                    'La solicitud está en un tramite que no le permite acceder a esta funcionalidad'
                );
                return false;
            },

            deliverEquipment(index) {
                const vm = this;

                if (!vm.selected.length > 0){
                    bootbox.alert("Debe agregar al menos un elemento a la solicitud de entrega");
                    return false;
                };
                let equipments_id = [];
                vm.errors = [];
                vm.equipments.forEach(element => {
                    equipments_id.push(element.asset_id);
                });
                vm.record.equipments = {assigned: [...equipments_id.filter(id => !vm.selected.includes(id))], 
                                        possible_deliveries: vm.selected,
                                        delivered: []},
                                        
                axios.put(`${window.app_url}/asset/asignations/deliver-equipment/${vm.record.asset_asignation_id}`, vm.record).then(response => {
                    if (typeof(response.data.redirect) !== "undefined") {
                        location.href = response.data.redirect;
                    }
                    else {
                        vm.readRecords(url);
                        vm.reset();
                        vm.showMessage('store');
                        vm.reset();
                    }
                }).catch(error => {
                    vm.errors = [];

                    if (typeof(error.response) !="undefined") {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                });
            },
        },
    };
</script>
