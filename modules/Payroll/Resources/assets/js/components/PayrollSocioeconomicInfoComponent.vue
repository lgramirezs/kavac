<template>
    <div id="PayrollSocioeconomicInfo" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="PayrollSocioeconomicInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:60rem">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h6>
                        <i class="icofont icofont-read-book ico-2x"></i>
                         Información Detallada de los Datos Socioeconómicos
                    </h6>
                </div>

                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active text-center" id="general" role="tabpanel">
                            <hr>
                            <h6 class="text-center">Datos básicos del Trabajador</h6><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Nombres del Trabajador</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_staff ? record.payroll_staff.first_name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Apellidos del Trabajador</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_staff ? record.payroll_staff.last_name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Estado civil del Trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{record.marital_status ? record.marital_status.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="record.marital_status_id == 2">
                                <hr>
                                <h6 class="text-center">Información de la pareja del Trabajador</h6><br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Nombres y apellidos de la pareja del trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.full_name_twosome }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Cédula de identidad de la pareja del trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.id_number_twosome }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Fecha de nacimiento de la pareja del trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.birthdate_twosome }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-center">Información de hijos del Trabajador</h6><br>
                            <div v-if="record.payroll_childrens">
                                <div v-for="child in record.payroll_childrens">
                                    <div class="row" >
                                        <div class="col-md-4">
                                        <strong>Nombres:</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ child.first_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Apellidos:</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ child.last_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Cédula de identidad:</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ child.id_number }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Fecha de nacimiento:</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ child.birthdate }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>¿Es estudiante?</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ child.is_student ? 'Si' : 'No' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div v-if="child.is_student">
                                    <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                            <strong>Nivel de escolaridad:</strong>
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ child.payroll_schooling_level_id && child.payroll_schooling_level ? child.payroll_schooling_level.name : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <strong>Centro de estudios:</strong>
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ child.study_center }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <strong>¿Posee discapacidad?</strong>
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ child.has_disability ? 'Si' : 'No' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3" v-if="child.has_disability">
                                            <strong>Discapacidad:</strong>
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ child.payroll_disability ? child.payroll_disability.name : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        props: {
            payroll_socioeconomic_id: Number,
        },
        data() {
            return {
                record: {
                    id: '',
                    full_name_twosome: '',
                    id_number_twosome: '',
                    birthdate_twosome: '',
                    payroll_staff_id: '',
                    marital_status_id: '',
                    payroll_childrens: [],
                },
                errors: [],
                //payroll_staffs: [],
                payroll_socioeconomic: [],
                marital_status: [],
                payroll_schooling_levels: [],
                payroll_disabilities: []
            }
        },
        methods: {
            /**
             * Método que borra todos los datos del formulario
             *
             * @author  William Páez <wpaez@cenditel.gob.ve>
             */
            reset() {
                this.record = {
                    id: '',
                    full_name_twosome: '',
                    id_number_twosome: '',
                    birthdate_twosome: '',
                    payroll_staff_id: '',
                    marital_status_id: '',
                    payroll_childrens: [],
                };
            },

            getSocioeconomic() {
                axios.get(`${window.app_url}/payroll/socioeconomics/${this.payroll_socioeconomic_id}`).then(response => {
                    this.record = response.data.record;
                });
            },
        },

        created() {
            //this.getPayrollStaffs((this.payroll_employment_id)?this.payroll_employment_id:'filter');
            this.getPayrollSocioeconomic((this.payroll_employment_id)?this.payroll_employment_id:'filter');
            this.getMaritalStatus();
            this.getPayrollSchoolingLevels();
            this.getPayrollDisabilities();
            this.record.payroll_childrens = [];
        },

        mounted() {
            if(this.payroll_socioeconomic_id) {
                this.getSocioeconomic();
            }
        },

        // watch: {
        //     record: {
        //         deep: true,
        //         handler: function(event) {
        //             const vm = this;                                                                               
        //             for(const i in vm.record.payroll_childrens){
        //                 console.log(vm.record.payroll_childrens[i].is_student);
                        
        //             }
        //         }
        //     }
        // }
    };
</script>