<template>
    <div class="card">
        <div class="card-header">
			<h6 class="card-title text-uppercase">
				Emisión de Pago
			</h6>
			<div class="card-btns">
				<a href="javascript:void(0)" class="btn btn-sm btn-primary btn-custom" 
                   @click="redirect_back(route_list)" title="Ir atrás" data-toggle="tooltip">
					<i class="fa fa-reply"></i>
				</a>
				<a href="javascript:void(0)" class="card-minimize btn btn-card-action btn-round" 
                   title="Minimizar" data-toggle="tooltip">
					<i class="now-ui-icons arrows-1_minimal-up"></i>
				</a>
			</div>
		</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-4">
                    <h6>Datos del Pago</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Nro. Referencia</label>
                        <select2 :options="[]"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Tipo de retención</label>
                        <select2 :options="[]"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Monto a Pagar</label>
                        <select2 :options="[]"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Parcial</label>
                        <div class="col-12 bootstrap-switch-mini">
                            <input type="checkbox" class='form-control bootstrap-switch' data-on-label="SI" data-off-label="NO">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Agregar cuentas presupuestarias</label>
                        <div class="col-12 bootstrap-switch-mini">
                            <input type="checkbox" class='form-control bootstrap-switch' data-on-label="SI" data-off-label="NO">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group is-required">
                        <label for="" class="control-label">Observaciones</label>
                        <ckeditor :editor="ckeditor.editor" data-toggle="tooltip"
                                    title="Indique una observación para la emisión de pago" 
                                    :config="ckeditor.editorConfig" class="form-control" 
                                    tag-name="textarea" rows="3"></ckeditor>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4 mb-4">
                    <h6>Datos Contables</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm table-striped table-hover">
                        <col class="col-2">
                        <col class="col-5">
                        <col class="col-2">
                        <col class="col-2">
                        <col class="col-1">
                        <thead>
                            <tr>
                                <th>CUENTA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>DEBE</th>
                                <th>HABER</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in accounting" :key="index"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
			<buttonsDisplay :route_list="app_url+'/finance/payment-execute'" display="false"></buttonsDisplay>
		</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    
                },
                accounting: []
            }
        },
        methods: {
            /**
             * Reinicia los campos del formularios
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {},
        },
        async mounted() {
            const vm = this;
            vm.loading = true;
            
            vm.loading = false;
        }
    }
</script>