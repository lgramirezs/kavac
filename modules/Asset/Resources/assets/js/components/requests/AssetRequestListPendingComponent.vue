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
			<div slot="id" slot-scope="props" class="text-center">
				<button @click="acceptRequest(props.index)"
						class="btn btn-success btn-xs btn-icon btn-action"
						title="Aceptar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-check"></i>
				</button>

				<button @click="rejectedRequest(props.index)"
						class="btn btn-danger btn-xs btn-icon btn-action" title="Rechazar Solicitud" data-toggle="tooltip" type="button">
					<i class="fa fa-ban"></i>
				</button>
			</div>
			 <div slot="created_at" slot-scope="props" class="text-center">
                <span>
                    {{ format_date(props.row.created_at) }}
                </span>
            </div>
			<div slot="delivery_date" slot-scope="props" class="text-center">
                <span>
                    {{ format_date(props.row.delivery_date) }}
                </span>
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
				columns: ['state', 'user.name', 'created_at', 'delivery_date', 'id'],
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
			this.loadAssets(`${this.route_list}/${this.perPage}/${this.page}`);
			//this.readRecords(this.route_list);
			this.table_options.headings = {
				'state': 'Estado',
				'user.name': 'Solicitante',
				'created_at': 'Fecha de emisión',
				'delivery_date': 'Fecha de entrega',
				'id': 'Acción'
			};
			this.table_options.sortable = ['state','created_at','delivery_date'];
			this.table_options.filterable = ['state','created_at','delivery_date'];

		},
		methods: {

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
				var fields = this.records[index-1];
				var id = this.records[index-1].id;

				axios.put(this.route_update+'/request-approved/'+id, fields).then(response => {
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
			rejectedRequest(index)
			{
				const vm = this;
				var fields = this.records[index-1];
				var id = this.records[index-1].id;

				axios.put(this.route_update+'/request-rejected/'+id, fields).then(response => {
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

		}
	};
</script>
