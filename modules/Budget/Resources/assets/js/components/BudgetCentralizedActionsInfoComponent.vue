<template>
   <div>
	    	
		<button @click="addRecord('show_entry_', route_list, $event)" class="btn btn-info btn-xs btn-icon btn-action" title="Visualizar registro" data-toggle="tooltip" v-has-tooltip>
            <i class="fa fa-eye"></i>
        </button>
	     <div class="modal fade text-left" tabindex="-1" role="dialog" id='show_entry_'>
            <div class="modal-dialog vue-crud" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="reset" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h6>
                            <i class="fa fa-list inline-block"></i>
                            Acción Centralizada
                        </h6>
                    </div>
                    <!-- Fromulario -->
                    <div class="modal-body">
                         <div class="row">
                            <div class="col-6">
                                 <div class="form-group">
                                    <label>Código</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="budget.code" >
                                </div>
                            </div>
           
                            <div class="col-3">
                                    <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="budget.name" >
                                </div>
                            </div>
      
                            <div class="col-3">
                                    <div class="form-group">
                                    <label>Fecha:</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="budget.custom_date" >
                                </div>
                            </div>  
       
                          
                        </div>
                        <div class="row" style="padding-top: 1rem;">
                            <div class="col-3">
                                    <div class="form-group">
                                    <label>Primer Nombre del Responsable :</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="personal.first_name" >
                                </div>
                            </div>    
                              <div class="col-3">
                                    <div class="form-group">
                                    <label>Apellido del Responsable :</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="personal.last_name" >
                                </div>
                            </div>   
                                
                            <div class="col-6">
                     
                                    <div class="form-group">
                                    <label>Cargo de Responsable:</label>
                                    <input type="text" data-toggle="tooltip" class="form-control input-sm"
                                        disabled="true" v-model="position.name">
                                </div>
                         
                              </div>
                          
                        </div>
                        <div class="row">

                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>

</template>

<script>
	export default {
           props: ['id','route_list'],
		data() {
			return {
				records: [],
                budget:[],
                personal:[],
                position:[], 
				errors: [],

			}
		},
		created() {


		},
		methods: {

            reset() {
            },
                initRecords(url,modal_id){
          
            	this.errors = [];
				this.reset();
         
				const vm = this;
       
				url = vm.setUrl(url);
   
            	axios.get(url).then(response => {
      
              
               vm.budget=response.data.budget;
               vm.personal=response.data.cargo;
               vm.position=response.data.cargo.payroll_employment.payroll_position;
     

					

				}).catch(error => {
	
				});
    
                 if ($("#" + modal_id).length) {
						$("#" + modal_id).modal('show');
					}
            },
            	
             

		},
	};
</script>
