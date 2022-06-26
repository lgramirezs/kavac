<template>

    <div class="col-md-12">
        <div class="form-group form-inline pull-right VueTables__limit-2">
            <div class="VueTables__limit-field">
                <label class="">Registros</label>
                <select2 :options="perPageValues"
                    v-model="perPage">
                </select2>
            </div>
        </div>

        <v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
            <div slot="code" slot-scope="props" class="text-center">
                <span>
                    {{ props.row.code }}
                </span>
            </div>
            <div slot="type" slot-scope="props" class="text-center">
                <div v-for="type in types" :key="type.id">
                    <span v-if="props.row.type == type.id">
                        {{ type.text }}
                    </span>
                </div>
            </div>
            <div slot="created_at" slot-scope="props" class="text-center">
                <span>
                    {{ format_date(props.row.created_at) }}
                </span>
            </div>
            <div v-html="props.row.motive" slot="motive" slot-scope="props"
                class="text-center">
            </div>
            <div slot="id" slot-scope="props" class="d-flex justify-content-center">

                <asset-request-info
                    :route_list="app_url+'/asset/requests/vue-info/'+props.row.id">
                </asset-request-info>

                <asset-request-extension
                    :requestid="props.row.id"
                    :delivery_date="props.row.delivery_date"
                    :state="props.row.state">
                </asset-request-extension>

                <asset-request-event
                    :id="props.row.id"
                    :state="props.row.state">
                </asset-request-event>

                <button
                    @click="deliverEquipment(props.index)"
                    class="btn btn-primary btn-xs btn-icon btn-action"
                    :disabled="((props.row.state == 'Aprobado')||(props.row.state == 'Pendiente por entrega'))?false:true"
                    data-toggle="tooltip" title="Entregar Equipos" type="button">
                    <i class="icofont icofont-computer"></i>
                </button>

                <button
                    @click="editForm(props.row.id)"
                    class="btn btn-warning btn-xs btn-icon btn-action"
                    :disabled="(props.row.state == 'Pendiente')?false:true"
                    title="Editar Solicitud" data-toggle="tooltip" type="button">
                    <i class="icofont icofont-edit"></i>
                </button>

                <button
                    @click="deleteRecord(props.index, '')"
                    class="btn btn-danger btn-xs btn-icon btn-action"
                    title="Eliminar registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-trash-o"></i>
                </button>

            </div>

        </v-client-table>
        <div class="VuePagination-2 row col-md-12 ">
            <nav class="text-center">
                <ul class="pagination VuePagination__pagination" style="">
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk" v-if="page != 1">
                        <a class="page-link" @click="changePage(1)">PRIMERO</a>
                    </li>
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk disabled">
                        <a class="page-link">&lt;&lt;</a>
                    </li>
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-page" v-if="page > 1">
                        <a class="page-link" @click="changePage(page - 1)">&lt;</a>
                    </li>
                    <li :class="(page == number)?'VuePagination__pagination-item page-item active':'VuePagination__pagination-item page-item'" v-for="(number, index) in pageValues" :key="index" v-if="number <= lastPage">
                        <a class="page-link active" role="button" @click="changePage(number)">{{number}}</a>
                    </li>
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-next-page" v-if="page < lastPage">
                        <a class="page-link" @click="changePage(page + 1)">&gt;</a>
                    </li>
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-next-chunk disabled">
                        <a class="page-link">&gt;&gt;</a>
                    </li>
                    <li class="VuePagination__pagination-item page-item  VuePagination__pagination-item-prev-chunk" v-if="lastPage != page">
                        <a class="page-link" @click="changePage(lastPage)">ÚLTIMO</a>
                    </li>
                </ul>
                <p class="VuePagination__count text-center col-md-12" style=""> </p>
            </nav>
        </div>
    </div>


</template>

<script>
    export default {
        data() {
            return {
                records: [],
                errors: [],
                page: 1,
                total: '',
                perPage: 10,
                lastPage: '',
                pageValues: [1,2,3,4,5,6,7,8,9,10],
                perPageValues: [
                    {
                        'id': 10,
                        'text': '10'
                    },
                    {
                        'id': 25,
                        'text': '25'
                    },
                    {
                        'id': 50,
                        'text': '50'
                    }
                ],
                columns: ['code', 'type', 'motive', 'created_at', 'state', 'id'],

                types: [
                    {"id":"","text":"Seleccione..."},
                    {"id":1,"text":"Prestamo de Equipos (Uso Interno)"},
                    {"id":2,"text":"Prestamo de Equipos (Uso Externo)"},
                    {"id":3,"text":"Prestamo de Equipos para Agentes Externos"}
                ],
            }
        },
        watch: {
            perPage(res) {
                if (this.page == 1){
                    this.loadAssets(`${this.route_list}/${this.perPage}/${this.page}`);
                } else {
                    this.changePage(1);
                }
            },
            page(res) {
                this.loadAssets(`${this.route_list}/${this.perPage}/${res}`);
            },
        },

        created() {
            this.table_options.headings = {
                'code': 'Código',
                'type': 'Tipo de solicitud',
                'motive': 'Motivo',
                'created_at': 'Fecha de emisión',
                'state': 'Estado de la solicitud',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'type','motive','created_at','state'];
            this.table_options.filterable = ['code', 'type','motive','created_at','state'];

        },
        mounted () {
            this.loadAssets(`${this.route_list}/${this.perPage}/${this.page}`);
            //this.initRecords(this.route_list, '');
        },
        methods: {
            /**
             * Inicializa los datos del formulario
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
             */
            reset() {

            },
            loadAssets(url, fields) {
                const vm = this;
                axios.get(url, fields).then(response => {
                    if (typeof(response.data.records) !== "undefined") {
                        vm.records  = response.data.records;
                        vm.total    = response.data.total;
                        vm.lastPage = response.data.lastPage;
                        vm.$refs.tableMax.setLimit(vm.perPage);
                    }
                });
            },
            /**
             * Cambia la pagina actual de la tabla
             *
             * @author Henry Paredes <hparedes@cenditel.gob.ve>
             *
             * @param [Integer] $page Número de pagina actual
             */
            changePage(page) {
                const vm = this;
                vm.page = page;
                var pag = 0;
                while(1) {
                    if (pag + 10 >= vm.page) {
                        pag += 1;
                        break;
                    } else {
                        pag += 10;
                    }
                }
                vm.pageValues = [];
                for (var i = 0; i < 10; i++) {
                    vm.pageValues.push(pag + i);
                }
            },

            deliverEquipment(index) {
                const vm = this;
                var fields = this.records[index-1];
                var id = this.records[index-1].id;

                axios.put(`${window.app_url}/asset/requests/deliver-equipment/${id}`, fields).then(response => {
                    if (typeof(response.data.redirect) !== "undefined") {
                        location.href = response.data.redirect;
                    }
                    else {
                        vm.readRecords(url);
                        vm.reset();
                        vm.showMessage('update');
                    }
                }).catch(error => {
                    vm.errors = [];

                    if (typeof(error.response) !="undefined") {
                        for (var index in error.response.data.errors) {
                            if (error.response.data.errors[index]) {
                                vm.errors.push(error.response.data.errors[index][0]);
                            }
                        }
                    }
                });
            },
            /**
             * Reescribe el método deleteRecord para cambiar su comportamiento por defecto
             * Método para la eliminación de registros
             *
             * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             *
             * @param  {integer} index Elemento seleccionado para su eliminación
             * @param  {string}  url   Ruta que ejecuta la acción para eliminar un registro
             */
            deleteRecord(index, url) {
                var url = (url)?vm.setUrl(url):this.route_delete;
                var records = this.records;
                var confirmated = false;
                var index = index - 1;
                const vm = this;

                bootbox.confirm({
                    title: "Eliminar registro?",
                    message: "Esta seguro de eliminar este registro?",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancelar'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirmar'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            confirmated = true;
                            axios.delete(url + '/' + records[index].id).then(response => {
                                if (typeof(response.data.error) !== "undefined") {
                                    /** Muestra un mensaje de error si sucede algún evento en la eliminación */
                                    vm.showMessage('custom', 'Alerta!', 'warning', 'screen-error', response.data.message);
                                    return false;
                                }
                                if (typeof(response.data.redirect) !== "undefined") {
                                    location.href = response.data.redirect;
                                }
                            }).catch(error => {
                                vm.logs('mixins.js', 498, error, 'deleteRecord');
                            });
                        }
                    }
                });

                if (confirmated) {
                    if (typeof(response.data.redirect) !== "undefined") {
                        location.href = response.data.redirect;
                    }
                }
            },

        }
    };
</script>
