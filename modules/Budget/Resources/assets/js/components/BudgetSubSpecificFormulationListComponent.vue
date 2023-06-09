<template>
  <v-client-table :columns="columns" :data="records" :options="table_options">
    <div slot="id" slot-scope="props" class="text-center">
      <button
        @click="showRecord(props.row.id)"
        v-if="route_show"
        class="btn btn-info btn-xs btn-icon btn-action btn-tooltip"
        title="Ver registro"
        data-toggle="tooltip"
        data-placement="bottom"
        type="button"
      >
        <i class="fa fa-eye"></i>
      </button>
      <button
        @click="editForm(props.row.id)"
        v-if="!props.row.assigned"
        class="btn btn-warning btn-xs btn-icon btn-action btn-tooltip"
        title="Modificar registro"
        data-toggle="tooltip"
        data-placement="bottom"
        type="button"
      >
        <i class="fa fa-edit"></i>
      </button>
      <button
        @click="asignR(props.row.id)"
        v-if="!props.row.assigned"
        class="btn btn-success btn-xs btn-icon btn-action btn-tooltip"
        title="Asignar Presupuesto"
        data-toggle="tooltip"
        data-placement="bottom"
        type="button"
      >
        <i class="fa fa-check"></i>
      </button>
      <button
        @click="deleteRecord(props.row.id, '')"
        class="btn btn-danger btn-xs btn-icon btn-action btn-tooltip"
        title="Eliminar registro"
        data-toggle="tooltip"
        data-placement="bottom"
        type="button"
      >
        <i class="fa fa-trash-o"></i>
      </button>
    </div>
    <div slot="year" slot-scope="props" class="text-center">
      {{ props.row.year }}
    </div>
    <div slot="specific_action" slot-scope="props">
      {{ props.row.specific_action.code }} -
      {{ props.row.specific_action.name }}
    </div>
    <div slot="total_formulated" slot-scope="props">
      {{
        formatToCurrency(props.row.total_formulated, props.row.currency.symbol)
      }}
    </div>
    <div slot="assigned" slot-scope="props">
      <span class="text-danger text-bold" v-if="!props.row.assigned">NO</span>
      <span class="text-success text-bold" v-else>SI</span>
    </div>
  </v-client-table>
</template>

<script>
export default {
  data() {
    return {
      records: [],
      assigned: {
        _method: "PUT",
        assigned: "1",
      },
      columns: [
        "code",
        "year",
        "specific_action",
        "total_formulated",
        "assigned",
        "id",
      ],
    };
  },
  created() {
    this.table_options.headings = {
      code: "Código",
      year: "Año",
      specific_action: "Acc. Específica",
      total_formulated: "Total Formulado",
      assigned: "Asignado",
      id: "Acción",
    };
    this.table_options.sortable = ["code", "year", "specific_action"];
    this.table_options.filterable = ["code", "year", "specific_action"];
    this.table_options.columnsClasses = {
      code: "col-md-2",
      name: "col-md-1",
      specific_action: "col-md-4",
      total_formulated: "col-md-2 text-right",
      assigned: "col-md-1 text-center",
      id: "col-md-2",
    };
    this.table_options.orderBy = { column: "code" };
  },
  mounted() {
    this.initRecords(this.route_list, "");
  },
  methods: {
    /**
     * Inicializa los datos del formulario
     *
     * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    reset() {},
    asignR(id) {
      const vm = this;
      var dialog = bootbox.confirm({
        title: "Esta seguro de asignar esta formulación?",
        message:
          "Una vez asignado no puede ser modificado",
        size: "medium",
        buttons: {
          cancel: {
            label: '<i class="fa fa-times"></i> Cancelar',
          },
          confirm: {
            label: '<i class="fa fa-check"></i> Confirmar',
          },
        },
        callback: function (result) {
          if (result) {
            axios({
              method: "post",
              url: `${window.app_url}/budget/subspecific-formulations/${id}`,
              data: vm.assigned,
            })
              .then((response) => {
                vm.errors = [];
                vm.showMessage("store");
              })
              .catch(error => {
                if (typeof(error.response) !== "undefined") {
                  vm.showMessage(
                      'custom', 'Acceso Denegado', 'danger', 'screen-error', error.response.data.message
                  );
                }
            });
          }
        },
      });
      setTimeout(function() {
        vm.initRecords(vm.route_list, "");
      }, 2000);
    },
  },
};
</script>
