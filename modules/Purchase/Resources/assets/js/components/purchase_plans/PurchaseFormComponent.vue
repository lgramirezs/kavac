<template>
    <div class="form-horizontal">
        <div class="card-body">
            <purchase-show-errors ref="purchaseShowError" />
            <div class="row">
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="purchase_types">Tipo de compra</label><br>
                        <select2 :options="purchase_types" id="purchase_types" @input="loadPurchaseProcess()" v-model="record.purchase_type_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="purchase_process">Proceso de compra</label><br>
                        <select2 :options="purchase_process" :disabled="disabledInputProcess" id="purchase_process" v-model="record.purchase_processes_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label" for="responsable">Responsable</label><br>
                        <select2 :options="users" id="responsable" v-model="record.payroll_staff_id"></select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label">Fecha Inicial</label>
                        <input type="date" class="form-control" v-model="record.init_date" tabindex="1">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group is-required">
                        <label class="control-label">Fecha de culminación</label>
                        <input type="date" id="end_date" class="form-control input-sm no-restrict" data-toggle="tooltip"
                                   :min="record.init_date"
                                   title="Indique la fecha de culminación" v-model="record.end_date">
                        <!--<input type="date" class="form-control" v-model="record.end_date" tabindex="1">-->
                    </div>
                </div>
               
                <div class="col-3">
                    <label for="purchase_plan">Plan de compra</label>
                    <label class="custom-control">
                        <button type="button" data-toggle="tooltip" 
                                v-has-tooltip 
                                class="btn btn-sm btn-info btn-import" 
                                title="Presione para subir el archivo del documento." 
                                @click="setFile('purchase_plan')">
                                <i class="fa fa-upload"></i>
                        </button>
                        <input type="file" id="purchase_plan" @change="uploadFile('purchase_plan', $event)" style="display:none;">
                        <span class="badge badge-success" id="status_purchase_plan" style="display:none;">
                            <strong>Documento Cargado.</strong>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <buttonsDisplay route_list="/purchese/purchase_plans" display="false" />
        </div>
    </div>
</template>
<script>
export default {
    props: {
        purchase_types: {
            type: Array,
            default: function() {
                return [];
            }
        },
        purchase_process: {
            type: Array,
            default: function() {
                return [];
            }
        },
        users: {
            type: Array,
            default: function() {
                return [];
            }
        },
        record_edit: {
            type: Object,
            default: function() {
                return null;
            }
        },
    },
    data() {
        return {
            record: {
                end_date: '',
                init_date: '',
                purchase_type_id: '',
                purchase_processes_id: '',
                payroll_staff_id: '',
            },
            disabledInputProcess: false,

            files: {}
        }
    },
    mounted() {
        if (this.record_edit) {
            this.record = this.record_edit;
        }
    },
    methods: {
        reset() {
            const vm = this;
            vm.record = {
                end_date: '',
                init_date: '',
                purchase_type_id: '',
                purchase_processes_id: '',
                payroll_staff_id: '',
            };
            vm.$refs.purchaseShowError.reset();
        },

        uploadFile(inputID, e) {
            let vm = this;
            const files = e.target.files;

            Array.from(files).forEach(file => vm.addFile(file, inputID));
        },
        addFile(file, inputID) {
            if (!file.type.match('application/pdf')) {
                this.showMessage(
                    'custom', 'Error', 'danger', 'screen-error', 'Solo se permiten archivos pdf.'
                );
                return;
            } else {
                this.files[inputID] = file;
                $('#status_' + inputID).show("slow");
            }
        },

        createRecord() {
            const vm = this;

            let formData = new FormData();

            if(vm.files['purchase_plan']){
                formData.append("file", vm.files['purchase_plan'], (vm.files['purchase_plan']) ? vm.files['purchase_plan'].name: '');
            }

            formData.append("purchase_type_id", vm.record.purchase_type_id);
            formData.append("purchase_processes_id", vm.record.purchase_processes_id);
            formData.append("payroll_staff_id", vm.record.payroll_staff_id);
            formData.append("init_date", vm.record.init_date);
            formData.append("end_date", vm.record.end_date);

            vm.loading = true;
            if (!vm.record_edit) {
                axios.post('/purchase/purchase_plans', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    vm.loading = false;
                    vm.showMessage('store');
                    setTimeout(function() {
                        location.href = '/purchase/purchase_plans';
                    }, 2000);
                }).catch(error => {
                    vm.loading = false;
                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                    vm.$refs.purchaseShowError.refresh();
                });
            } else {
                axios.put('/purchase/purchase_plans/' + vm.record_edit.id, vm.record).then(response => {
                    vm.loading = false;
                    vm.showMessage('update');
                    setTimeout(function() {
                        location.href = '/purchase/purchase_plans';
                    }, 2000);
                }).catch(error => {
                    vm.loading = false;
                    vm.errors = [];
                    if (typeof(error.response) != 'undefined') {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                    vm.$refs.purchaseShowError.refresh();
                    vm.loading = false;
                });
            }
        },


        loadPurchaseProcess() {
            const vm = this;
            for (var i = 0; i < vm.purchase_types.length; i++) {
                if (vm.purchase_types[i].id == vm.record.purchase_type_id) {
                    if (vm.record.purchase_processes_id != vm.purchase_types[i].purchase_processes_id) {
                        vm.record.purchase_processes_id = vm.purchase_types[i].purchase_processes_id;

                        vm.disabledInputProcess = true;

                        if (!vm.purchase_types[i].purchase_processes_id) {
                            vm.disabledInputProcess = false;
                        }
                        break;
                    }
                }
            }
        },

    },
    watch: {

    }
};
</script>
