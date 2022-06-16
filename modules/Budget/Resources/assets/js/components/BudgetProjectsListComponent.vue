<template>
    <div>
        <v-client-table :columns="columns" :data="records" :options="table_options">
            <div slot="created_at" slot-scope="props" class="text-center">
						<span>
							{{ (props.row.created_at)? format_date(props.row.created_at):'N/A' }}
						</span>
					</div>
            <div slot="id" slot-scope="props" class="text-center">
                <button
                    @click="show_info(props.row.id)"
                    class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
                    title="Ver registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-eye"></i>
                </button>
                <button
                    @click="editForm(props.row.id)"
                    class="btn btn-warning btn-xs btn-icon btn-action"
                    title="Modificar registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-edit"></i>
                </button>
                <button
                    @click="deleteRecord(props.row.id, '')"
                    class="btn btn-danger btn-xs btn-icon btn-action"
                    title="Eliminar registro"
                    data-toggle="tooltip"
                    type="button">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <div slot="active" slot-scope="props" class="text-center">
                <span v-if="props.row.active">SI</span>
                <span v-else>NO</span>
            </div>
        </v-client-table>
        <!-- Modal -->
        <div
      id="show_employment"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="BudgetCentralizedActionsInfoModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg text-left"
        role="document"
        style="max-width: 60rem; color: #636e7b; font-size: 13px"
      >
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
            <h6 style="font-size: 1em">
              <i class="icofont icofont-read-book ico-2x"></i>
               Información detallada del Proyecto
            </h6>
          </div>

          <div class="modal-body">
            <div class="tab-content">
              <div class="tab-pane active" id="general" role="tabpanel">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Institución :</strong>
                      <div class="row">
                        <span  class="col-md-12">
                         <a id="institution"></a>  
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Dependencia:</strong>
                      <div class="row">
                        <span class="col-md-12">
                          <a id="department"></a>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Responsable:</strong>
                      <div class="row">
                        <span class="col-md-12">
                          <a id="responsable"></a>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Cargo del Responsable:</strong>
                      <div class="row">
                        <span class="col-md-12">
                          <a id="payroll_position"></a>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Codigo:</strong>
                      <div class="row">
                        <span class="col-md-12">
                          <a id="code"></a>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Código ONAPRE:</strong>
                      <div class="row">
                        <span class="col-md-12">
                          <a id="onapre_code"></a>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Nombre:</strong>
                      <div class="row">
                        <span class="col-md-12">
                         <a  id="name"></a>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <strong>Activo:</strong>
                      <div class="row">
                        <span class="col-md-12">
                         <a  id="active"></a>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default btn-sm btn-round btn-modal-close"
                data-dismiss="modal"
              >
                Cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
       
        <!-- Modal -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                records: [],
                columns: ['created_at','code', 'name', 'active', 'id']
            }
        },
        created() {
            this.table_options.headings = {
                'created_at': 'Fecha',
                'code': 'Código',
                'name': 'Proyecto',
                'active': 'Activo',
                'id': 'Acción'
            };
            this.table_options.sortable = ['code', 'name'];
            this.table_options.filterable = ['code', 'name'];
            this.table_options.columnsClasses = {
                 'created_at': 'col-md-2',
                'code': 'col-md-2 text-center',
                'name': 'col-md-4 text-center',
                'active': 'col-md-2 text-center',
                'id': 'col-md-2 text-center'
            };
        },
        mounted() {
            this.initRecords(this.route_list, '');

        },
        methods: {
            /**
             * Inicializa los datos del formulario
             *
             * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
             */
            reset() {
            },
            /**
             * Método que abre el modal, realiza la consulta y pasa los datos.
             */
            show_info(id) {
                axios.get(`${window.app_url}/budget/projects/get-detail-project/${id}`)
                .then(response => {
                    this.record = response.data;
                    $('#name').html(this.record.project.name);
                    $('#institution').html(this.record.cargo.payroll_employment.department.institution.name);
                    $('#department').html(this.record.cargo.payroll_employment.department.name);
                    $('#responsable').html(this.record.cargo.first_name + ' ' + this.record.cargo.last_name);
                    $('#payroll_position').html(this.record.cargo.payroll_employment.payroll_position.name);
                    $('#code').html(this.record.project.code);
                    $('#onapre_code').html(this.record.project.onapre_code);
                    $('#active').html((this.record.project.active === true) ? 'Sí' : 'No');
                });
                $('#show_employment').modal('show');
            }
        }
    };
</script>
