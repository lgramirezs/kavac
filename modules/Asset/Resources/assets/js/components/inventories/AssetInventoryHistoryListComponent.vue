<template>
	<div>
		<div class="card">
			<div class="card-header">
				<h6 class="card-title text-uppercase">Historial de Inventario de Bienes</h6>
				<div class="card-btns">
					<a href="#" class="btn btn-sm btn-primary btn-custom" @click="redirect_back(route_list)"
					   title="Ir atrás" data-toggle="tooltip">
						<i class="fa fa-reply"></i>
					</a>
					<a href="#" class="btn btn-sm btn-primary btn-custom" @click="createRecord('asset/inventory-history')"
					   title="Guardar estado actual de inventario" data-toggle="tooltip">
						<i class="fa fa-plus-circle"></i>
					</a>
					<a href="#" class="card-minimize btn btn-card-action btn-round" title="Minimizar"
					   data-toggle="tooltip">
						<i class="now-ui-icons arrows-1_minimal-up"></i>
					</a>
				</div>
			</div>

			<div class="card-body">
				<div class="form-group form-inline pull-right VueTables__limit-2">
					<div class="VueTables__limit-field">
						<label class="">Registros</label>
						<select2 :options="perPageValues"
							v-model="perPage">
						</select2>
					</div>
            	</div>
				<v-client-table :columns="columns" :data="records" :options="table_options" ref="tableMax">
					
					<div slot="created_at" slot-scope="props" class="text-center">
						<span>
							{{ (props.row.created_at)? format_date(props.row.created_at):'N/A' }}
						</span>
					</div>
					<div slot="id" slot-scope="props" class="text-center">
						
						<div class="d-inline-flex">

							<button @click="createReport(props.row.code, 'create_report')"
									class="btn btn-primary btn-xs btn-icon btn-action"
									title="Generar reporte de bienes" data-toggle="tooltip"
									type="button" v-has-tooltip>
								<i class="fa fa-file-pdf-o"></i>
							</button>

				    		<button @click="deleteRecord(props.row.id, 'asset/inventory-history/delete')"
									class="btn btn-danger btn-xs btn-icon btn-action"
									title="Eliminar registro" data-toggle="tooltip"
									type="button" v-has-tooltip>
								<i class="fa fa-trash-o"></i>
							</button>
						</div>
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
		</div>

		<!--div class="modal fade text-left" tabindex="-1" role="dialog" id="create_report">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h6>
							<i class="icofont icofont-file-pdf ico-2x"></i>
							Generar Reporte de Bienes?
						</h6>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<strong>Tipo de Reporte</strong>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>General</label>
									<div class="col-12">
										<input type="radio" name="type_report" value="general" checked=""
                                               id="sel_general_report"
                                               class="form-control bootstrap-switch bootstrap-switch-mini sel_type_report"
                                               data-on-label="SI" data-off-label="NO">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Por Clasificación</label>
									<div class="col-12">
										<input type="radio" name="type_report" value="clasification" id="sel_clasification_report" class="form-control bootstrap-switch bootstrap-switch-mini sel_type_report" data-on-label="SI" data-off-label="NO">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Por Dependencia</label>
									<div class="col-12">
										<input type="radio" name="type_report" value="dependence" id="sel_dependence_report" class="form-control bootstrap-switch bootstrap-switch-mini sel_type_report" data-on-label="SI" data-off-label="NO">
									</div>
								</div>
							</div>
						</div>
					</div>

	                <div class="modal-footer">

	                	<button data-bb-handler="cancel" type="button" class="btn btn-default btn-modal-close" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

	                	<button data-bb-handler="confirm" type="button" class="btn btn-primary" @click="createReport('create_report')"><i class="fa fa-check"></i> Confirmar</button>

		            </div>

		        </div>
		    </div>
		</div-->
	</div>
</template>

<script>
	export default {
		data() {
			return {
				record: {
					id: '',
					code: '',
					type_report: '',
				},
				records: [],
				search: '',
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
				columns: ['code', 'created_at', 'registered', 'assigned', 'disincorporated', 'id'],
			}
		},
		watch: {
            perPage(res) {
                if (this.page == 1){
                    this.loadAssets(`${window.app_url}/asset/inventory-history/vue-list/${this.perPage}/${this.page}`);
                } else {
                    this.changePage(1);
                }
            },
            page(res) {
                this.loadAssets(`${window.app_url}/asset/inventory-history/vue-list/${this.perPage}/${res}`);
            },
        },

		created() {
			this.table_options.headings = {
				'code': 'Código',
				'created_at': 'Fecha de creación',
				'registered': 'Bienes registrados',
				'assigned': 'Bienes asignados',
				'disincorporated': 'Bienes desincorporados',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'created_at', 'registered', 'assigned', 'disincorporated'];
			this.table_options.filterable = ['code', 'created_at', 'registered', 'assigned', 'disincorporated'];
			this.table_options.orderBy = { 'column': 'id'};
		},
		mounted () {
			this.loadAssets(`${window.app_url}/asset/inventory-history/vue-list/${this.perPage}/${this.page}`);
			this.switchHandler('type_report');
		},
		methods: {
			/**
			 * Inicializa los datos del formulario
			 *
			 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
			 */
			reset() {
				this.record = {
					id: '',
					code: '',
					type_report: 'general',
				}
			},
			/*showReport(code, modal_id) {
				const vm = this;
				vm.reset();
				vm.record.code = code;
				if ($("#" + modal_id).length) {
					$("#" + modal_id).modal('show');
				}
			},*/

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

			createReport(code, modal_id) {
				const vm = this;
				vm.record.code = code;
				if (vm.record.type_report == 'dependence') {
					return false;
				}
				var url = `${window.app_url}/asset/reports/${vm.record.type_report}/show/${vm.record.code}`;
				window.open(url, '_blank');
				if ($("#" + modal_id).length) {
					$("#" + modal_id).modal('hide');
				}
			}
		}
	};
</script>
