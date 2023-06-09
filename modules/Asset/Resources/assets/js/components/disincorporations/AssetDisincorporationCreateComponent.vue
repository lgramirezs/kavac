<template>
  <section id="AssetDisincorporationForm">
    <div class="card-body">
      <div class="alert alert-danger" v-if="errors.length > 0">
        <div class="container">
          <div class="alert-icon">
            <i class="now-ui-icons objects_support-17"></i>
          </div>
          <strong>Cuidado!</strong> Debe verificar los siguientes errores antes
          de continuar:
          <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
            @click.prevent="errors = []"
          >
            <span aria-hidden="true">
              <i class="now-ui-icons ui-1_simple-remove"></i>
            </span>
          </button>
          <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6" id="helpDisincorporationDate">
          <div class="form-group is-required">
            <label>Fecha de desincorporación</label>
            <div class="input-group input-sm">
              <span class="input-group-addon">
                <i class="now-ui-icons ui-1_calendar-60"></i>
              </span>
              <input
                type="date"
                class="form-control input-sm"
                data-toggle="tooltip"
                title="Fecha de desincorporación"
                v-model="record.date"
              />
            </div>
          </div>
        </div>
        <div class="col-md-6" id="helpDisincorporationMotive">
          <div class="form-group is-required">
            <label>Motivo de la desincorporación</label>
            <select2
              :options="asset_disincorporation_motives"
              data-toggle="tooltip"
              title="Indique el motivo de la desincorporación del bien"
              v-model="record.asset_disincorporation_motive_id"
            ></select2>
            <input type="hidden" v-model="record.id" />
          </div>
        </div>
        <div class="col-md-6" id="helpDisincorporationObservation">
          <div class="form-group is-required">
            <label>Observaciones generales</label>
            <ckeditor
              :editor="ckeditor.editor"
              data-toggle="tooltip"
              id="observations"
              title="Indique alguna observación referente a la desincorporación"
              :config="ckeditor.editorConfig"
              class="form-control"
              name="observations"
              tag-name="textarea"
              rows="3"
              v-model="record.observation"
            ></ckeditor>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label> Adjuntar archivos </label>
            <input
              id="files"
              name="files"
              type="file"
              accept=".doc, .docx, .odt, .pdf, .png, .jpg, .jpeg"
              multiple
            />
          </div>
          <div class="row">
            <div class="form-group">
              <strong>Archivos Subidos</strong>
              <div class="row" style="margin: 1px 0">
                <span class="col-md-12" id="archive"> </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <b>Información de los bienes a ser desincorporados</b>
        </div>
      </div>
      <div class="row" style="margin: 10px 0">
        <div class="col-md-12">
          <b>Filtros</b>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3" id="helpSearchAssetType">
          <div class="form-group">
            <label>Tipo de bien</label>
            <select2
              :options="asset_types"
              @input="getAssetCategories()"
              data-toggle="tooltip"
              title="Indique el tipo del bien"
              v-model="record.asset_type_id"
            ></select2>
          </div>
        </div>

        <div class="col-md-3" id="helpSearchAssetCategory">
          <div class="form-group">
            <label>Categoria general</label>
            <select2
              :options="asset_categories"
              @input="getAssetSubcategories()"
              data-toggle="tooltip"
              title="Indique la categoria general del bien"
              v-model="record.asset_category_id"
            ></select2>
          </div>
        </div>
        <div class="col-md-3" id="helpSearchAssetSubCategory">
          <div class="form-group">
            <label>Subcategoria</label>
            <select2
              :options="asset_subcategories"
              @input="getAssetSpecificCategories()"
              data-toggle="tooltip"
              title="Indique la subcategoria del bien"
              v-model="record.asset_subcategory_id"
            ></select2>
          </div>
        </div>

        <div class="col-md-3" id="helpSearchAssetSpecificCategory">
          <div class="form-group">
            <label>Categoria específica</label>
            <select2
              :options="asset_specific_categories"
              data-toggle="tooltip"
              title="Indique la categoria específica del bien"
              v-model="record.asset_specific_category_id"
            ></select2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button
            type="button"
            id="helpSearchButton"
            @click="filterRecords()"
            class="btn btn-sm btn-primary btn-info float-right"
            title="Buscar registros"
            data-toggle="tooltip"
          >
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>

      <hr />
      
      <v-client-table
        id="helpTable"
        @row-click="toggleActive"
        :columns="columns"
        :data="records"
        :options="table_options"
        ref="tableMax"
      >
        <div slot="h__check" class="text-center">
          <label class="form-checkbox">
            <input
              type="checkbox"
              v-model="selectAll"
              @click="select()"
              class="cursor-pointer"
            />
          </label>
        </div>

        <div slot="check" slot-scope="props" class="text-center">
          <label class="form-checkbox">
            <input
              type="checkbox"
              class="cursor-pointer"
              :value="props.row.id"
              :id="'checkbox_' + props.row.id"
              v-model="selected"
            />
          </label>
        </div>
        <div slot="institution" slot-scope="props" class="text-center">
          <span>{{
            props.row.institution
              ? props.row.institution.name
              : props.row.institution_id
              ? props.row.institution_id
              : "N/A"
          }}</span>
        </div>
        <div slot="asset_condition" slot-scope="props" class="text-center">
          <span>{{
            props.row.asset_condition
              ? props.row.asset_condition.name
              : props.row.asset_condition_id
          }}</span>
        </div>
        <div slot="asset_status" slot-scope="props" class="text-center">
          <span>{{
            props.row.asset_status
              ? props.row.asset_status.name
              : props.row.asset_status_id
          }}</span>
        </div>
      </v-client-table>
    </div>

    <div class="card-footer text-right">
      <div class="row">
        <div class="col-md-3 offset-md-9" id="helpParamButtons">
          <button
            type="button"
            @click="reset()"
            class="btn btn-default btn-icon btn-round"
            data-toggle="tooltip"
            title="Borrar datos del formulario"
          >
            <i class="fa fa-eraser"></i>
          </button>

          <button
            type="button"
            @click="redirect_back(route_list)"
            class="btn btn-warning btn-icon btn-round btn-modal-close"
            data-dismiss="modal"
            title="Cancelar y regresar"
          >
            <i class="fa fa-ban"></i>
          </button>

          <button
            type="button"
            @click="createRecord('asset/disincorporations')"
            class="btn btn-success btn-icon btn-round btn-modal-save"
            title="Guardar registro"
          >
            <i class="fa fa-save"></i>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      record: {
        id: "",
        date: "",
        asset_disincorporation_motive_id: "",
        observation: "",

        asset_type_id: "",
        asset_category_id: "",
        asset_subcategory_id: "",
        asset_specific_category_id: "",
      },

      records: [],
      files: [],

      columns: [
        "check",
        "inventory_serial",
        "institution",
        "asset_condition",
        "asset_status",
        "serial",
        "marca",
        "model",
      ],
      errors: [],

      asset_disincorporation_motives: [],

      asset_types: [],
      asset_categories: [],
      asset_subcategories: [],
      asset_specific_categories: [],

      selected: [],
      selectAll: false,

      table_options: {
        rowClassCallback(row) {
          var checkbox = document.getElementById("checkbox_" + row.id);
          return checkbox && checkbox.checked
            ? "selected-row cursor-pointer"
            : "cursor-pointer";
        },
        headings: {
          inventory_serial: "Código",
          institution: "Ubicación",
          asset_condition: "Condición Física",
          asset_status: "Estatus de Uso",
          serial: "Serial",
          marca: "Marca",
          model: "Modelo",
        },
        sortable: [
          "inventory_serial",
          "institution",
          "asset_condition",
          "asset_status",
          "serial",
          "marca",
          "model",
        ],
        filterable: [
          "inventory_serial",
          "institution",
          "asset_condition",
          "asset_status",
          "serial",
          "marca",
          "model",
        ],
      },
    };
  },
  created() {
    const vm = this;
    let url 
    if(vm.disincorporationid != null){
         url = `${window.app_url}/asset/registers/vue-list/disincorporations/`+vm.disincorporationid;
    }else{
        url = `${window.app_url}/asset/registers/vue-list/disincorporations`;
    }
    
    this.readRecords(url);
    this.getAssetTypes();
    this.getAssetDisincorporationMotives();

    if (this.disincorporationid) {
      this.loadForm(this.disincorporationid);
    }
  },
  mounted() {
    if (this.disincorporationid && !this.assetid) {
      this.loadForm(this.disincorporationid);
    } else if (!this.disincorporationid && this.assetid) {
      this.selected.push(this.assetid);
    }
  },
  props: {
    disincorporationid: Number,
    assetid: Number,
  },
  methods: {
    toggleActive({ row }) {
      const vm = this;
      var checkbox = document.getElementById("checkbox_" + row.id);

      if (checkbox && checkbox.checked == false) {
        var index = vm.selected.indexOf(row.id);
        if (index >= 0) {
          vm.selected.splice(index, 1);
        } else {
          checkbox.click();
        }
      } else if (checkbox && checkbox.checked == true) {
        var index = vm.selected.indexOf(row.id);
        if (index >= 0) {
          checkbox.click();
        } else {
          vm.selected.push(row.id);
        }
      }
    },
    reset() {
      this.record = {
        id: "",
        date: "",
        asset_disincorporation_motive_id: "",
        observation: "",

        asset_type_id: "",
        asset_category_id: "",
        asset_subcategory_id: "",
        asset_specific_category_id: "",
      };
      this.selected = [];
      this.files = [];
      this.selectAll = false;
    },
    select() {
      const vm = this;
      vm.selected = [];
      $.each(vm.records, function (index, campo) {
        var checkbox = document.getElementById("checkbox_" + campo.id);

        if (!vm.selectAll) {
          vm.selected.push(campo.id);
        } else if (checkbox && checkbox.checked) {
          checkbox.click();
        }
      });
    },

    createRecord(url, list = true, reset = true) {
      const vm = this;
      var inputFiles = document.querySelector("#files");
      const formData = new FormData();
      url = vm.setUrl(url);

      vm.errors = [];
      if (!vm.selected.length > 0) {
        bootbox.alert(
          "Debe agregar al menos un elemento de la tabla a la solicitud"
        );
        return false;
      }
      if (this.record.id) {
        vm.selected = vm.selected.filter(
          (ele, pos) => vm.selected.indexOf(ele) == pos
        );

        for (var i = 0; i < vm.selected.length; i++) {
          let file = inputFiles.files[i];
          formData.append(`assets[]`, vm.selected[i]);
          // formData.append("files[" + i + "]", file);
        }
        //vm.record.assets = vm.selected;
        // this.updateRecord(url);
        url = vm.setUrl(url);
        vm.loading = true;
        Object.keys(vm.record).forEach((key) => {
          if (vm.record[key] == null) {
            vm.record[key] = "";
          }
          formData.append(key, vm.record[key]);
        });
        for (var i = 0; i < inputFiles.files.length; i++) {
          let file = inputFiles.files[i];
          formData.append(
            `files[]`,
            inputFiles.files[i],
            inputFiles.files[i].name
          );
        }

        axios({
          method: "post",
          url: url + "/Updatefiles/" + vm.record.id,
          data: formData,
        })
          .then((response) => {
            if (typeof response.data.redirect !== "undefined") {
              location.href = response.data.redirect;
            } else {
              vm.errors = [];
              if (reset) {
                vm.reset();
              }
              if (list) {
                vm.readRecords(url);
              }
              vm.loading = false;
              vm.showMessage("update");
            }
          })
          .catch((error) => {
            vm.errors = [];

            if (typeof error.response != "undefined") {
              for (var index in error.response.data.errors) {
                if (error.response.data.errors[index]) {
                  vm.errors.push(error.response.data.errors[index][0]);
                }
              }
            }

            vm.loading = false;
          });
      } else {
        vm.loading = true;
        for (var index in vm.record) {
          formData.append(index, vm.record[index]);
        }
        for (var i = 0; i < inputFiles.files.length; i++) {
          let file = inputFiles.files[i];

          formData.append("files[" + i + "]", file);
        }
        formData.append("assets", vm.selected);
        axios
          .post(url, formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            if (typeof response.data.redirect !== "undefined") {
              location.href = response.data.redirect;
            } else {
              vm.errors = [];
              if (reset) {
                vm.reset();
              }
              if (list) {
                vm.readRecords(url);
              }
              vm.loading = false;
              vm.showMessage("store");
            }
          })
          .catch((error) => {
            vm.errors = [];

            if (typeof error.response != "undefined") {
              for (var index in error.response.data.errors) {
                if (error.response.data.errors[index]) {
                  vm.errors.push(error.response.data.errors[index][0]);
                }
              }
            }

            vm.loading = false;
          });
      }
    },
    updateRecord(url) {
      const vm = this;
      var inputFiles = document.querySelector("#files");
      var formData = new FormData();
      vm.loading = true;
      var fields = {};
      url = vm.setUrl(url);

      for (var index in vm.record) {
        fields[index] = vm.record[index];
      }
      formData.append(fields);

      for (var i = 0; i < inputFiles.files.length; i++) {
        let file = inputFiles.files[i];

        formData.append("files[" + i + "]", file);
      }
      formData.append("assets", vm.selected);

      axios
        .patch(
          `${url}${url.endsWith("/") ? "" : "/"}${vm.record.id}`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          if (typeof response.data.redirect !== "undefined") {
            location.href = response.data.redirect;
          } else {
            vm.errors = [];
            if (reset) {
              vm.reset();
            }
            if (list) {
              vm.readRecords(url);
            }
            vm.loading = false;
            vm.showMessage("update");
          }
        })
        .catch((error) => {
          vm.errors = [];

          if (typeof error.response != "undefined") {
            for (var index in error.response.data.errors) {
              if (error.response.data.errors[index]) {
                vm.errors.push(error.response.data.errors[index][0]);
              }
            }
          }

          vm.loading = false;
        });
    },
    loadForm(id) {
      const vm = this;
      var fields = {};
      var tipo = "";
      var documents = "";

      axios
        .get(`${window.app_url}/asset/disincorporations/vue-info/${id}`)
        .then((response) => {
          if (typeof (response.data.records != "undefined")) {
            vm.record = response.data.records;
            fields = response.data.records.asset_disincorporation_assets;
            $.each(fields, function (index, campo) {
              vm.selected.push(campo.asset.id);
            });
          }
        });
      axios
        .get(`${window.app_url}/asset/disincorporations/get-documents/${id}`)
        .then((response) => {
          documents = response.data;

          let fileText = ``;
          documents.records.forEach(function (files) {
            fileText += `<div class ="row">`;
            fileText += `<a href='${window.app_url}/asset/disincorporations/get-documents/show/${files.file}'>${files.file}</a>`;

            fileText += "</div>";
          });

          document.getElementById("archive").innerHTML = fileText;
        })
        .catch((error) => {
          if (typeof error.response !== "undefined") {
            if (error.response.status == 403) {
              vm.showMessage(
                "custom",
                "Acceso Denegado",
                "danger",
                "screen-error",
                error.response.data.message
              );
            } else {
              vm.logs("resources/js/all.js", 343, error, "initRecords");
            }
          }
        });
    },
    
    filterRecords() {
      const vm = this;
      var url = `${window.app_url}/asset/registers/search/clasification`;

      var filters = {
        asset_type: vm.record.asset_type_id,
        asset_category: vm.record.asset_category_id,
        asset_subcategory: vm.record.asset_subcategory_id,
        asset_specific_category: vm.record.asset_specific_category_id,
      };

      axios.post(url, filters).then((response) => {
        vm.records = response.data.records;
      });
    },
    /**
     * Obtiene los datos de los motivos de una desincorporación
     *
     * @author Henry Paredes <hparedes@cenditel.gob.ve>
     */
    getAssetDisincorporationMotives() {
      const vm = this;
      vm.asset_disincorporation_motives = [];
      axios
        .get(`${window.app_url}/asset/disincorporations/get-motives`)
        .then((response) => {
          vm.asset_disincorporation_motives = response.data;
        });
    },
  },
};
</script>
