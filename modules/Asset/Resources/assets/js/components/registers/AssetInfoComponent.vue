<template>
    <div>
        <a class="btn btn-info btn-xs btn-icon btn-action"
            href="#" title="Ver información del Bien" data-toggle="tooltip" v-has-tooltip
            @click="addRecord('add_asset', route_list ,$event)">
            <i class="fa fa-info-circle"></i>
        </a>
        <div class="modal fade text-left" tabindex="-1" role="dialog" id="add_asset">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="icofont icofont-read-book ico-2x"></i>
                            Información del Bien Registrado
                        </h6>
                    </div>

                    <div class="modal-body">

                        <ul class="nav nav-tabs custom-tabs justify-content-center" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#general" id="info_general" role="tab">
                                    <i class="ion-android-person"></i> Información General
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#purchase" role="tab">
                                    <i class="ion-social-dropbox-outline"></i>
                                    Procedencia
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="general" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Tipo</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_type">
                                                </span>
                                            </div>
                                            <input type="hidden" id="id">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Categoria</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_category">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Subcategoria</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_subcategory">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Categoria especifica</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_specific">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Código</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_codigo"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Marca</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_marca">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Modelo</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_model">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Valor</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_value">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="purchase" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Forma de adquisición</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_acquisition_type">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Fecha de adquisición</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_acquisition_date">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Ubicación</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_ubication">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Proveedor</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="purchase_supplier">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Condición física</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_condition">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Estatus de uso</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_status">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Función de uso</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_use_function">
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Serial de fábrica</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12" id="asset_serial">
                                                </span>
                                            </div>
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
                errors: [],
                records: [],
                supplier: null,
            }
        },
        methods: {
            /**
             * Método que borra todos los datos del formulario
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
             */
            reset() {
            },

            /**
             * Reescribe el método de initRecords para cambiar su comportamiento por defecto.
             * Inicializa los registros base del formulario
             *
             * @author Henry Paredes <hparedes@cenditel.gob.ve>
             */
            initRecords(url,modal_id){
                this.errors = [];
                this.reset()
                const vm = this;
                
                
                document.getElementById("info_general").click();
                    
                        
                        vm.getSupplier(vm.records.purchase_supplier_id);
                       
                        document.getElementById('asset_type').innerText = (vm.records.asset_type)?vm.records.asset_type.name:'';
                        document.getElementById('asset_category').innerText = (vm.records.asset_category)?vm.records.asset_category.name:'';
                        document.getElementById('asset_subcategory').innerText = (vm.records.asset_subcategory)?vm.records.asset_subcategory.name:'';
                        document.getElementById('asset_specific').innerText = (vm.records.asset_specific_category)?vm.records.asset_specific_category.name:'';
                        document.getElementById('asset_codigo').innerText = vm.records.inventory_serial;

                        document.getElementById('asset_acquisition_type').innerText = (vm.records.asset_acquisition_type)?vm.records.asset_acquisition_type.name:'';
                        document.getElementById('asset_acquisition_date').innerText = (vm.records.acquisition_date)?vm.format_date(vm.records.acquisition_date):'';
                        document.getElementById('asset_ubication').innerText = (vm.records.address)?vm.records.address.replace( /(<([^>]+)>)/ig, '') + ', ' +
                                                                                                    vm.records.parish.name + ', ' +  vm.records.parish.municipality.name + ', ' +
                                                                                                    vm.records.parish.municipality.estate.name + ', '+ 
                                                                                                    vm.records.parish.municipality.estate.country.name :'N/A';
                        
                       setTimeout(()=>{
                            document.getElementById('purchase_supplier').innerText = (vm.supplier)? vm.supplier : 'N/A';
                       }, 1500);
                       
                        document.getElementById('asset_condition').innerText = (vm.records.asset_condition)?vm.records.asset_condition.name:'';
                        document.getElementById('asset_status').innerText = (vm.records.asset_status_id == 11)?vm.records.asset_status.name + ': ' 
                                                                                                                + vm.records.asset_disincorporation_asset.asset_disincorporation.asset_disincorporation_motive.name:
                                                                                                                vm.records.asset_status.name;

                        document.getElementById('asset_use_function').innerText = (vm.records.asset_use_function)?vm.records.asset_use_function.name:'N/A';
                        document.getElementById('asset_serial').innerText = (vm.records.serial)?vm.records.serial:'';
                        document.getElementById('asset_marca').innerText = (vm.records.marca)?vm.records.marca:'';
                        document.getElementById('asset_model').innerText = (vm.records.model)?vm.records.model:'';
                        document.getElementById('asset_value').innerText = (vm.records.value)?vm.records.value:'';
                    
                    
                        if ($("#" + modal_id).length) {
                            $("#" + modal_id).modal('show');
                        }
                           
            }, 

            getSupplier(id) {
				const vm = this;
				
                if(id){
                    axios.get(`${window.app_url}/purchase/suppliers/${id}`).then(response => {
                        vm.supplier = response.data.records.name;
					});
                }	
			},

        },
        created(){
            this.readRecords(this.route_list);
        },
    };
</script>
