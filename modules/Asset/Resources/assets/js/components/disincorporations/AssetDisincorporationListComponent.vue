<template>
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
			<div slot="code" slot-scope="props" class="text-center">
				<span>
					{{ props.row.code }}
				</span>
			</div>
			<div slot="motive" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.asset_disincorporation_motive)?props.row.asset_disincorporation_motive.name:'N/A' }}
				</span>
			</div>
			<div slot="created" slot-scope="props" class="text-center">
				<span>
					{{ (props.row.date)?format_date(props.row.date):format_date(props.row.created_at) }}
				</span>
			</div>
			<div slot="id" slot-scope="props" class="text-center">
				<div class="d-inline-flex">
					<asset-disincorporation-info
						:route_list="app_url + '/asset/disincorporations/vue-info/' + props.row.id">
					</asset-disincorporation-info>

					<button @click="editForm(props.row.id)"
							class="btn btn-warning btn-xs btn-icon btn-action"
							title="Modificar registro" data-toggle="tooltip" type="button">
						<i class="fa fa-edit"></i>
					</button>
					<button @click="deleteRecord(props.row.id, '')"
							class="btn btn-danger btn-xs btn-icon btn-action"
							title="Eliminar registro" data-toggle="tooltip"
							type="button">
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
</template>

<script>
	export default {
		data() {
			return {
				records: [],
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
				columns: ['code', 'motive', 'created', 'id']
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
				'motive': 'Motivo',
				'created': 'Fecha de desincorporación',
				'id': 'Acción'
			};
			this.table_options.sortable = ['code', 'motive', 'created'];
			this.table_options.filterable = ['code', 'motive', 'created'];
			this.table_options.orderBy = { 'column': 'code'};
		},
		mounted () {
			this.loadAssets(`${this.route_list}/${this.perPage}/${this.page}`);
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
		},
	};
</script>
