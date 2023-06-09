<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        Restaurar Registros eliminados
                        <a class="btn-help" href="javascript:void(0)" data-toggle="tooltip"
                            title="haz click para ver la ayuda guiada de este elemento" @click="initUIGuide(helpFile)">
                            <i class="ion ion-ios-help-outline cursor-pointer"></i>
                        </a>
                    </h6>
                    <div class="card-btns">
                        <a class="btn btn-sm btn-primary btn-custom" title="Ir atrás" data-toggle="tooltip"
                            :href="route_previous" v-has-tooltip>
                            <i class="fa fa-reply"></i>
                        </a>
                        <a class="card-minimize btn btn-card-action btn-round" href="javascript:void(0)"
                            title="Minimizar" data-toggle="tooltip">
                            <i class="now-ui-icons arrows-1_minimal-up"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <b>Filtros</b>
                        </div>
                        <div id="helpRestoreFilterFromDate" class="form-group col-md-2">
                            <div class="input-group input-sm">
                                <span class="input-group-addon">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                </span>
                                <input id="startDeleteAt" class="form-control" type="date" data-toggle="tooltip"
                                    placeholder="Fecha" title="Desde la fecha" v-model="start_delete_at">
                            </div>
                        </div>
                        <div id="helpRestoreFilterToDate" class="form-group col-md-2">
                            <div class="input-group input-sm">
                                <span class="input-group-addon">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                </span>
                                <input id="endDeleteAt" class="form-control" type="date" data-toggle="tooltip"
                                    placeholder="Fecha" title="Hasta la fecha" v-model="end_delete_at">
                            </div>
                        </div>
                        <div id="helpRestoreFilterModule" class="form-group col-md-2">
                            <select id="restoreSearchModule" class="form-control select2" v-model="module_delete_at">
                                <option value="">Módulo</option>
                                <option :value="mod.originalName" v-for="(mod, index) in modules" :key="index">
                                    {{ mod.name }}
                                </option>
                            </select>
                        </div>
                        <div id="helpRestoreFilterButton" class="form-group col-md-2">
                            <button class="btn btn-info btn-icon btn-xs-responsive px-3" type="button"
                                data-toggle="tooltip" title="Buscar registros eliminados" @click="readRecords">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span class="form-text text-muted">
                                A continuación se muestran los 20 registros eliminados más recientes.
                                Si desea obtener mayor información, debe indicar los parámetros de búsqueda.
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div id="helpRestoreTable" class="row">
                        <div class="col-12">
                            <v-client-table :columns="columns" :data="records" :options="table_options">
                                <div slot="registers" slot-scope="props" v-html="props.row.registers"></div>
                                <div slot="id" slot-scope="props" class="text-center">
                                    <button class="btn btn-success btn-xs btn-icon btn-action" type="button"
                                        data-toggle="tooltip" title="Restaurar registro"
                                        @click="restore(props.row.module, props.row.id)">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            </v-client-table>
                        </div>
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
            start_delete_at: '',
            end_delete_at: '',
            module_delete_at: '',
            records: [],
            columns: ['deleted_at', 'module', 'registers', 'id'],
        }
    },
    props: {
        modules: {
            type: Array,
            required: false,
            default: null
        },
        route_previous: {
            type: String,
            required: true,
            default: '/',
        },
    },
    watch: {
        start_delete_at: function() {
            const vm = this;
            $('#endDeteleAt').attr('min', vm.start_delete_at);
        },
        end_delete_at: function() {
            const vm = this;
            if (vm.end_delete_at) {
                $('#startDeleteAt').attr('max', vm.end_delete_at);
            } else {
                if (!$('#startDeleteAt').hasClass('no-restrict')) {
                    $('#startDeleteAt').attr('max', vm.getCurrentDate());
                }
            }
        }
    },
    methods: {
        /**
         * Restaurar registro eliminado
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        restore(moduleClass, id) {
            const vm = this;
            bootbox.confirm('Está seguro de restaurar el registro seleccionado?', function(result) {
                if (result) {
                    vm.loading = true;
                    axios.post('/app/restore-record', {
                        module: moduleClass,
                        id: id
                    }).then(response => {
                        if (response.data.result) {
                            vm.readRecords();
                            vm.showMessage(
                                'custom', 'Éxito', 'success', 'screen-ok', 'Registro restaurado con éxito'
                            );
                        }
                        vm.loading = false;
                    }).catch(error => {
                        console.error(error);
                    });
                }
            });
        },
        /**
         * Método que obtiene los registros a mostrar
         *
         * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         *
         * @param  {string} url Ruta que obtiene todos los registros solicitados
         */
        async readRecords() {
            const vm = this;
            vm.loading = true;
            await axios.post('/app/deleted-records', {
                start_delete_at: vm.start_delete_at,
                end_delete_at: vm.end_delete_at,
                module_delete_at: vm.module_delete_at
            }).then(response => {
                if (response.data.result && typeof(response.data.records) !== "undefined") {
                    vm.records = response.data.records;
                    vm.records.sort(
                        (a, b) => (a.deleted_at > b.deleted_at) ? 1 : ((b.deleted_at > a.deleted_at) ? -1 : 0)
                    );
                }
            }).catch(error => {
                console.error(error);
            });
            vm.loading = false;
        },
    },
    created() {
        const vm = this;
        vm.table_options.headings = {
            'deleted_at': 'Fecha',
            'module': 'Módulo',
            'registers': 'Registros',
            'id': 'Acción'
        };
        vm.table_options.sortable = ['deleted_at', 'module', 'registers'];
        vm.table_options.filterable = ['deleted_at', 'module', 'registers'];
        vm.table_options.columnsClasses = {
            'deleted_at': 'col-md-1',
            'module': 'col-md-3',
            'registers': 'col-md-7',
            'id': 'col-md-1'
        };
    },
    mounted() {
        const vm = this;

        vm.readRecords();

        $('#restoreSearchModule').on('change', function() {
            vm.module_delete_at = $(this).val();
        });
    }
};
</script>
