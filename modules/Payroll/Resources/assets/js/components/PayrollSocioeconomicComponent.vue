<template>
    <section id="PayrollSocioeconomicForm">
        <div class="card-body">
            <div class="alert alert-danger" v-if="errors.length > 0">
                <div class="container">
                    <div class="alert-icon">
                        <i class="now-ui-icons objects_support-17"></i>
                    </div>
                    <strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                            @click.prevent="errors = []">
                        <span aria-hidden="true">
                            <i class="now-ui-icons ui-1_simple-remove"></i>
                        </span>
                    </button>
                    <ul>
                        <li v-for="error in errors" :key="error">{{ error }}</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4" id="helpSocioeconomicStaff">
                    <div class="form-group is-required">
                        <label>Trabajador:</label>
                        <select2 :options="payroll_socioeconomic"
                                 v-model="record.payroll_staff_id"></select2>
                        <input type="hidden" v-model="record.id">
                    </div>
                </div>

                <div class="col-md-4" id="helpSocioeconomicMaritalStatus">
                    <div class="form-group is-required">
                        <label>Estado Civil:</label>
                        <select2 :options="marital_status"
                            v-model="record.marital_status_id"></select2>
                    </div>
                </div>
            </div>

            <div class="row" v-if="record.marital_status_id == 2">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nombres y apellidos de la pareja del trabajador:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.full_name_twosome"/>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cédula de Identidad de la pareja del trabajador:</label>
                        <input type="text" class="form-control input-sm"
                            v-model="record.id_number_twosome"/>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>fecha de nacimiento de la pareja del trabajador:</label>
                        <input type="date" class="form-control input-sm"
                            v-model="record.birthdate_twosome"/>
                    </div>
                </div>
            </div>

            <hr>
            <h6 class="card-title" id="helpSocioeconomicChildren">
                Hijos del Trabajador <i class="fa fa-plus-circle cursor-pointer" @click="addPayrollChildren"></i>
            </h6>
            <div class="row" v-for="(payroll_children, index) in record.payroll_childrens" :key="index">
                <div class="col-3">
                    <div class="form-group is-required">
                        <input type="text" placeholder="Nombres del hijo del trabajador" data-toggle="tooltip"
                            title="Indique los nombres del hijo del trabajador" v-model="payroll_children.first_name"
                            class="form-control input-sm">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <input type="text" placeholder="Apellidos del hijo del trabajador" data-toggle="tooltip"
                            title="Indique los apellidos del hijo del trabajador" v-model="payroll_children.last_name"
                            class="form-control input-sm">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <input type="text" placeholder="Cédula de Identidad del hijo del trabajador" data-toggle="tooltip"
                            title="Indique la cédula de indentidad del hijo del trabajador"
                            v-model="payroll_children.id_number" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <input type="date" placeholder="Fecha de Nacimiento del hijo del trabajador" data-toggle="tooltip"
                            title="Indique la fecha de nacimiento del hijo del trabajador"
                            v-model="payroll_children.birthdate" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-3">
                    <div class="row col-md-6">
                        <div class="form-group">
                            <label>¿Es estudiante?</label>
                            <div class="row col-md-14">
                                <div class="col-14 bootstrap-switch-mini">
                                    <input type="checkbox" :id="'mySwicth' + `${index}`"
                                        class="form-control bootstrap-switch" data-toggle="tooltip"
                                        data-on-label="SI" data-off-label="NO"
                                        title="Indique si el hijo es estudiante o no"
                                        value="true"
                                        v-model="payroll_children.is_student"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-14" v-if="payroll_children.is_student">
                        <div class="form-group is-required" id="helpChildSchoolingLevelname">
                                <label>¿Nivel de escolaridad?</label>
                                <select2 :options="payroll_schooling_levels"
                                        v-model="payroll_children.payroll_schooling_level_id">
                                </select2>
                        </div>
                        <div class="form-group is-required">
                            <label>Centro de estudio</label>
                            <input type="text" placeholder="Nombre del centro de estudio" data-toggle="tooltip"
                                title="Indique el nombre del centro de estudio"
                                v-model="payroll_children.study_center" class="form-control input-sm">
                        </div>
                    </div>
                    <br>
                </div>
                <div class="col-3">
                    <div class="row col-md-10">
                        <div class="form-group">
                            <label>¿Posee una Discapacidad?</label>
                            <div class="row col-md-14">
                                <div class="col-14 bootstrap-switch-mini">
                                    <input id="has_disability" name="has_disability" type="checkbox"
                                    class="form-control bootstrap-switch sel_has_disability"
                                    data-toggle="tooltip" data-on-label="SI" data-off-label="NO"
                                    title="Indique si el trabajador posee una discapacidad o no"
                                    v-model="payroll_children.has_disability" value="true"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-14" id="helpChildDisabilityName" v-if="payroll_children.has_disability">
                        <div class="form-group is-required">
                            <label>Discapacidad</label>
                            <select2 :options="payroll_disabilities" v-model="payroll_children.payroll_disability_id">
                            </select2>
                        </div>
                    </div>
                </div>
                <div class="row col-1">
                    <div class="form-group">
                        <br>
                        <button class="btn btn-sm btn-danger btn-action" type="button"
                            @click="removeRow(index, record.payroll_childrens)"
                            title="Eliminar este dato" data-toggle="tooltip" data-placement="right">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer pull-right" id="helpParamButtons">
            <button class="btn btn-default btn-icon btn-round" data-toggle="tooltip" type="button"
                title="Borrar datos del formulario" @click="reset"><i class="fa fa-eraser"></i>
            </button>
            <button type="button" class="btn btn-warning btn-icon btn-round" data-toggle="tooltip"
                    title="Cancelar y regresar" @click="redirect_back(route_list)">
                <i class="fa fa-ban"></i>
            </button>
            <button type="button" @click="createRecord('payroll/socioeconomics')" data-toggle="tooltip"
                    title="Guardar registro" class="btn btn-success btn-icon btn-round">
                <i class="fa fa-save"></i>
            </button>
        </div>
    </section>
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

            /**
             * Agrega una nueva columna para el registro de hijos del trabajador
             *
             * @author William Páez <wpaez@cenditel.gob.ve>
             */
            addPayrollChildren() {
                this.record.payroll_childrens.push({
                    first_name: '',
                    last_name: '',
                    id_number: '',
                    birthdate: '',
                    is_student: false,
                    has_disability: false,
                    payroll_schooling_level_id: '',
                    payroll_disability_id:'',
                    study_center: ''
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
