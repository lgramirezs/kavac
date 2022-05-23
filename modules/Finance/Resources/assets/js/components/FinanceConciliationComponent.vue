<template>
    <div>
        <div class="alert alert-danger" v-if="errors.length > 0">
            <div class="alert-icon">
                <i class="now-ui-icons objects_support-17"></i>
            </div>
            <strong>Cuidado!</strong> Debe verificar los siguientes errores antes de continuar:
            <button type="button" class="close" data-dismiss="alert"
                aria-label="Close">
                <span aria-hidden="true">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </span>
            </button>
            <ul>
                <li v-for="(error, index) in errors"
                    :key="index">{{ error }}
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group is-required">
                    <label>Cuenta bancaria:</label>
                    <select2 :options="" v-model=""></select2>
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
                },
                errors: [],
                records: [],
                columns: [
                ],
            }
        },
        methods: {
            /**
             * Método que limpia todos los datos del formulario.
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {
                this.record = {
                };
            },
        },
        created() {
        },
        mounted() {
        },
    };
</script>
