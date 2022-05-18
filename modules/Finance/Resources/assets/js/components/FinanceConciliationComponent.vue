<template>
    <div>
        <h1>Componente de Conciliación</h1>
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
