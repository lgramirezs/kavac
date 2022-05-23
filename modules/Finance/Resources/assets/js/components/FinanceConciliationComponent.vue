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
                    <select2 :options="lines" v-model="account"></select2>
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
                    account: '',
                },
                errors: [],
                records: [],
                columns: [
                ],
                lines: [
                    { "id": "", "text": "Seleccione..." },
                    { "id": 1, "text": "1" , "disabled": null },
                    { "id": 2, "text": "2", "disabled": null },
                    { "id": 3, "text": "3", "disabled": null },
                    { "id": 4, "text": "4", "disabled": null },
                    { "id": 5, "text": "5", "disabled": null },
                    { "id": 6, "text": "6", "disabled": null },
                    { "id": 7, "text": "7", "disabled": null },
                    { "id": 8, "text": "8", "disabled": null },
                    { "id": 9, "text": "9", "disabled": null },
                    { "id": 10, "text": "10", "disabled": null },
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
