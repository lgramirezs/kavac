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
			<div slot="observation" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.observation)? props.row.observation:'No definido'}}
				</span>
			</div>
			<div slot="created_at" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.created_at)? format_date(props.row.created_at):'N/A'}}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<button @click="acceptRequest(props.index)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-success btn-xs btn-icon btn-action"
						title="Aceptar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-check"></i>
				</button>

				<button @click="rejectedRequest(props.row.id)"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Rechazar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-ban"></i>
				</button>

				<button @click="deleteRecord(props.row.id, 'requests/deliveries')"
						:disabled="(props.row.state == 'Aprobado')? true:false"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro" data-toggle="tooltip" type="button">
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
				record: {
					id: '',
					observation: '',
					state: '',
					asset_request_id: '',
				},

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
				columns: ['asset_request.code', 'state', 'user.name', 'created_at', 'observation','id'],
			}
		},
		
		created() {
			this.loadAssets(`${this.route_list}/${this.perPage}/${this.page}`);

			//this.readRecords(this.route_list);
			this.table_options.headings = {
				'asset_request.code': 'Código de solicitud',
				'state': 'Estado de entrega',
				'user.name': 'Solicitante',
				'created_at': 'Fecha de emisión',
				'observation': 'Observaciones',
				'id': 'Acción'
			};
			this.table_options.sortable = ['asset_request.code', 'state', 'user.name', 'created_at'];
			this.table_options.filterable = ['asset_request.code', 'state', 'user.name', 'created_at'];
			
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
		methods: {
			reset() {
				this.record = {
					id: '',
					observation: '',
					state: '',
					asset_request_id: '',
				}
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

			acceptRequest(index)
			{
				const vm = this;
				var fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Aprobado';
				vm.record.asset_request_id = fields.asset_request.id;
				var dialog = bootbox.confirm({
				    title: 'Aprobar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='request_observation'></textarea></div></div></div>",
				    size: 'medium',
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
							vm.record.observation = document.getElementById('request_observation').value;
	    					vm.createRecord('asset/requests/deliveries');
	    				}
	    			}
	    		});

			},
			rejectedRequest(index)
			{
				const vm = this;
				var fields = vm.records[index-1];
				vm.record.id = fields.id;
				vm.record.state = 'Rechazado';
				vm.record.asset_request_id = fields.asset_request.id;
				var dialog = bootbox.confirm({
				    title: 'Rechazar entrega de equipos?',
				    message: "<div class='row'><div class='col-md-12'><div class='form-group'><label>Observaciones generales</label> <textarea data-toggle='tooltip' class='form-control input-sm' title='Indique las observaciones presentadas en la solicitud' id='request_observation'></textarea></div></div></div>",
				    size: 'medium',
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
							vm.record.observation = document.getElementById('request_observation').value;
	    					vm.createRecord('asset/requests/deliveries');
	    				}
	    			}
	    		});

			},

		}
	};
</script>
