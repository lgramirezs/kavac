<template>
    <div>
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Institución</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <select2
                            :options="institutions"
                            v-model="record.institution_id"
                        ></select2>
                        <input type="hidden" v-model="record.id" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Fecha</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <input
                            type="date"
                            class="form-control input-sm"
                            v-model="record.compromised_at"
                            title="Indique la fecha del compromiso"
                            id="compromised_at"
                            data-toggle="tooltip"
                        />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">
                            Documento Origen
                            <a
                                class="btn btn-sm btn-info btn-action btn-tooltip"
                                href="javascript:void(0)"
                                data-original-title="Buscar documento"
                                title="Buscar documento"
                                data-toggle="modal"
                                data-target="#add_source"
                                v-if="record.institution_id"
                            >
                                <i class="fa fa-search"></i>
                            </a>
                            <a
                                class="btn btn-sm btn-default btn-action btn-tooltip"
                                href="javascript:void(0)"
                                data-original-title="Quitar documento de origen"
                                title="Quitar documento de origen"
                                v-if="document_number !== ''"
                                data-toggle="tooltip"
                            >
                                <i class="icofont icofont-eraser"></i>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group is-required">
                        <input
                            type="text"
                            v-model="record.source_document"
                            class="form-control input-sm"
                            title="Indique el número de documento de origen que genera el compromiso"
                            data-toggle="tooltip"
                            :readonly="document_number !== ''"
                        />
                    </div>
                </div>
                <!-- Modal para agregar documentos de origen que generaron un precompromiso -->
                <div class="modal fade" tabindex="-1" role="dialog" id="add_source">
                    <div class="modal-dialog vue-crud" role="document">
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
                                <h6>
                                    <i class="ion-arrow-graph-up-right"></i>
                                    Agregar documento
                                </h6>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 pad-top-20">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Fecha</th>
                                                    <th>Monto</th>
                                                    <th>Sel.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(source, index) in document_sources"
                                                    :key="index"
                                                >
                                                    <td>{{ source.sourceable.code }}</td>
                                                    <td>{{ format_date(source.created_at) }}</td>
                                                    <td>{{ source.budget_stages[0].amount }}</td>
                                                    <td>
                                                        <a
                                                            href="#"
                                                            data-original-title="Agregar documento"
                                                            class="btn btn-sm btn-info btn-action btn-tooltip"
                                                            @click="addDocument(source.id)"
                                                        >
                                                            <i class="fa fa-plus-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                <!--<button type="button" @click="addDocument"
                                                                                class="btn btn-primary btn-sm btn-round btn-modal-save">
                                                                        Agregar
                                                                </button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Descripción</label>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group is-required">
                        <ckeditor
                            :editor="ckeditor.editor"
                            id="description"
                            data-toggle="tooltip"
                            title="Indique una descripción para el compromiso"
                            :config="ckeditor.editorConfig"
                            class="form-control"
                            name="description"
                            tag-name="textarea"
                            rows="3"
                            v-model="record.description"
                        ></ckeditor>
                    </div>
                </div>
            </div>
            <hr />
            <div class="pad-top-40">
                <h6 class="text-center card-title">
                    Cuentas presupuestarias de gastos
                </h6>
                <div class="row">
                    <div class="col-md-12 pad-top-20">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="col-4">Acción Específica</th>
                                    <th class="col-2">Cuenta</th>
                                    <th class="col-3">Descripción</th>
                                    <th class="col-2">Monto</th>
                                    <th class="col-1">
                                        <a
                                            class="btn btn-sm btn-info btn-action btn-tooltip"
                                            href="#"
                                            data-original-title="Agregar cuenta presupuestaria"
                                            data-toggle="modal"
                                            data-target="#add_account"
                                        >
                                            <i class="fa fa-plus-circle"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in record.accounts" :key="index">
                                    <td>{{ account.spac_description }}</td>
                                    <td>{{ account.code }}</td>
                                    <td>{{ account.description }}</td>
                                    <td class="text-right">
                                        {{ formatToCurrency(account.amount, "") }}
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="hidden"
                                            name="account_id[]"
                                            readonly
                                            :value="
                                                account.specific_action_id + '|' + account.account_id
                                            "
                                        />
                                        <input
                                            type="hidden"
                                            name="budget_account_amount[]"
                                            readonly
                                            :value="account.amount"
                                        />
                                        <a
                                            class="btn btn-sm btn-warning btn-action btn-tooltip"
                                            href="#"
                                            data-original-title="Editar cuenta presupuestaria"
                                            data-toggle="modal"
                                            data-target="#add_account"
                                            @click="editAccount(index)"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a
                                            class="btn btn-sm btn-danger btn-action"
                                            href="#"
                                            @click="deleteAccount(index)"
                                            title="Eliminar este registro"
                                            data-toggle="tooltip"
                                        >
                                            <i class="fa fa-minus-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="pad-top-40">
                <h6 class="text-center card-title">
                    Cuentas presupuestarias de impuestos
                </h6>
                <div class="row">
                    <div class="col-md-12 pad-top-20">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Acción Específica</th>
                                    <th>Cuenta</th>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(tax_account, index) in record.tax_accounts"
                                    :id="tax_account.parent_account_id"
                                    :key="index"
                                >
                                    <td>{{ tax_account.spac_description }}</td>
                                    <td>{{ tax_account.code }}</td>
                                    <td>{{ tax_account.description }}</td>
                                    <td class="text-right">
                                        {{ formatToCurrency(tax_account.amount, "") }}
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="hidden"
                                            name="account_id[]"
                                            readonly
                                            :value="
                                                tax_account.specific_action_id +
                                                '|' +
                                                tax_account.account_id
                                            "
                                        />
                                        <input
                                            type="hidden"
                                            name="budget_tax_account_amount[]"
                                            readonly
                                            :value="tax_account.amount"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal para agregar cuentas presupuestarias -->
                <div class="modal fade" tabindex="-1" role="dialog" id="add_account">
                    <div class="modal-dialog vue-crud" role="document">
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
                                <h6>
                                    <i class="ion-arrow-graph-up-right"></i>
                                    Agregar cuentas
                                </h6>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" v-if="errors.length > 0">
                                    <ul>
                                        <li v-for="(error, index) in errors" :key="index">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-12" v-if="hasDocumentSelected()">
                                        {{ setItemCompromise() }}
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group is-required">
                                            <label>Acción Específica:</label>
                                            <select2
                                                :options="specific_actions"
                                                @input="getAccounts"
                                                v-model="specific_action_id"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group is-required">
                                            <label>Cuenta:</label>
                                            <select2
                                                :options="accounts"
                                                @input="getAmountAccounts()"
                                                v-model="account_id"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Concepto:</label>
                                            <input
                                                type="text"
                                                class="form-control input-sm"
                                                data-toggle="tooltip"
                                                v-model="account_concept"
                                                title="Indique el concepto de la cuenta presupuestaria a agregar"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4">
                                        <div class="form-group is-required">
                                            <label>Monto:</label>
                                            <input
                                                type="number"
                                                onfocus="$(this).select()"
                                                class="form-control input-sm"
                                                data-toggle="tooltip"
                                                title="Indique el monto a asignar para la cuenta seleccionada"
                                                v-model="account_amount"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <div class="form-group">
                                            <label>Impuesto:</label>
                                            <select2 :options="taxes" v-model="account_tax_id" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-default btn-sm btn-round btn-modal-close"
                                    data-dismiss="modal"
                                    @click="resetAccount"
                                >
                                    Cerrar
                                </button>
                                <button
                                    type="button"
                                    @click="addAccount"
                                    class="btn btn-primary btn-sm btn-round btn-modal-save"
                                >
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button
                type="reset"
                class="btn btn-default btn-icon btn-round"
                data-toggle="tooltip"
                title="Borrar datos del formulario"
                @click="reset"
            >
                <i class="fa fa-eraser"></i>
            </button>
            <button
                type="button"
                class="btn btn-warning btn-icon btn-round"
                data-toggle="tooltip"
                title="Cancelar y regresar"
                @click="redirect_back(route_list)"
            >
                <i class="fa fa-ban"></i>
            </button>
            <button
                type="button"
                class="btn btn-success btn-icon btn-round"
                data-toggle="tooltip"
                title="Guardar registro"
                @click="createRecord('budget/compromises')"
            >
                <i class="fa fa-save"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            record: {
                id: "",
                institution_id: "",
                compromised_at: "",
                source_document: "",
                description: "",
                accounts: [],
                tax_accounts: [],
                documentToCompromise: {},
            },
            errors: [],
            institutions: [],

            /**
             * Campos temporales para agregar las cuentas presupuestarias a comprometer
             */
            taxes: [{ id: "", text: "Seleccione..." }],
            taxesData: [],
            specific_actions: [],
            specific_action_id: "",
            accounts: [],
            account_id: "",
            account_concept: "",
            account_amount: 0,
            selected_account_amount: 0,
            account_tax_id: "",

            /**
             * Campos temporales para agregar documentos al compromiso
             */
            document_sources: [],
            document_number: "",
            editIndex: null,
        };
    },
    props: {
        edit_object: {
            type: String,
            required: false,
        },
    },
    watch: {
        record: {
            deep: true,
            handler: function () {
                //
            },
        },
    },
    methods: {
        /**
         * Inicializa las variables del compromiso a registrar
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        reset: function () {
            /**
             * Campos con información a ser almacenada
             */
            this.record.id = "";
            this.record.institution_id = "";
            this.record.compromised_at = "";
            this.record.accounts = [];
            this.record.tax_accounts = [];
            this.source_document = "";
            this.description = "";
            this.errors = [];

            /**
             * Campos temporales para agregar las cuentas presupuestarias a comprometer
             */
            this.specific_action_id = "";
            this.account_id = "";
            this.account_concept = "";
            this.account_amount = 0;
            this.account_tax_id = "";

            /**
             * Campos temporales para agregar documentos al compromiso
             */
            this.document_sources = [];
            this.document_number = "";
        },
        async loadEditData() {
            let vm = this;

            let editData = JSON.parse(vm.edit_object);
            vm.record.id = editData.id;

            vm.record.compromised_at = moment(editData.compromised_at)
                .add(1, "days")
                .format("YYYY-MM-DD");

            vm.record.institution_id = editData.institution_id;
            vm.record.description = editData.description;
            vm.record.source_document = editData.document_number;

            if (editData.sourceable_type === "Modules\\Purchase\\Models\\PurchaseDirectHire") {
                editData.budget_compromise_details.forEach(async function (word) {
                    let specificAction = {};
                    let account = {};

                    vm.account_concept = word.description.replace(/(<([^>]+)>)/gi, "");;
                    vm.account_amount = word.amount;
                    vm.account_tax_id = word.tax_id;
                });
            } else {

                editData.budget_compromise_details.forEach(async function (word) {
                    let specificAction = {};
                    let account = {};

                    vm.specific_action_id =
                        word.budget_sub_specific_formulation.budget_specific_action_id;
                    vm.account_id = word.budget_account_id;

                    vm.account_concept = word.description;
                    vm.account_amount = word.amount;
                    vm.account_tax_id = word.tax_id;

                    await vm
                        .getSpecificActionDetail(
                            word.budget_sub_specific_formulation.budget_specific_action_id
                        )
                        .then((detail) => (specificAction = detail.record));

                    await vm
                        .getAccountDetail(word.budget_account_id)
                        .then((detail) => (account = detail.record));
                    vm.record.accounts.push({
                        spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                        code: account.code,
                        description: word.description,
                        amount: word.amount,
                        amountEdit: word.amount,
                        operation: '',
                        budgetCompromiseDetails: word.id,
                        specific_action_id:
                            word.budget_sub_specific_formulation.budget_specific_action_id,
                        account_id: word.budget_account_id,
                        tax_id: word.tax_id,
                    });

                    if (vm.account_tax_id) {
                        let tax;
                        let tax_percentage;
                        let tax_description;
                        for (tax of vm.taxesData) {
                            if (word.tax_id == tax.id) {
                                tax_description = tax.description;
                                tax_percentage = tax.histories[0].percentage;
                            }
                        }

                        vm.record.tax_accounts.push({
                            spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                            code: account.code,
                            description: tax_description,
                            amount: (word.amount * tax_percentage) / 100,
                            specific_action_id:
                                word.budget_sub_specific_formulation.budget_specific_action_id,
                            account_id: word.budget_account_id,
                            tax_id: word.tax_id,
                        });
                    }
                });
            }

            //   !vm.specific_action_id && !vm.account_id && !vm.account_concept && !vm.account_amount &&
            //     !vm.account_tax_id
        },
        /**
         * Elimina una cuenta del listado de cuentas agregadas
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         * @param  {integer} index Índice del elemento a eliminar
         */
        deleteAccount(index) {
            let vm = this;
            bootbox.confirm({
                title: "Eliminar cuenta?",
                message: `Esta seguro de eliminar esta cuenta del compromiso actual?`,
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
                        vm.record.accounts.splice(index, 1);
                        vm.record.tax_accounts.splice(index, 1);
                    }
                },
            });
        },
        /**
         * Edita una cuenta del listado de cuentas agregadas
         *
         * @author Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
         * @param  {integer} index Índice del elemento a editar
         */
        editAccount(index) {
            const vm = this;
            vm.account_amount = '';
            vm.account_concept = '';
            vm.account_id = '';
            vm.account_tax_id = '';
            vm.specific_action_id = '';
            vm.editIndex = index;

            vm.account_amount = vm.record.accounts[vm.editIndex]['amount'];
            vm.account_concept = vm.record.accounts[vm.editIndex]['description'];
            vm.account_id = vm.record.accounts[vm.editIndex]['account_id'];
            vm.account_tax_id = vm.record.accounts[vm.editIndex]['tax_id'];
            vm.specific_action_id = vm.record.accounts[vm.editIndex]['specific_action_id'];

            event.preventDefault();
        },
        /**
         * Elimina los valores de los campos en el modal de las cuentas
         *
         * @author Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
         * @param  {integer} index Índice del elemento a editar
         */
        resetAccount() {
            const vm = this;
            vm.account_amount = '';
            vm.account_concept = '';
            vm.account_id = '';
            vm.account_tax_id = '';
            vm.specific_action_id = '';
            vm.editIndex = null;
        },
        /**
         * Agrega una cuenta presupuestaria al compromiso
         *
         * @method     addAccount
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        async addAccount() {
            const vm = this;

            if (
                !vm.specific_action_id &&
                !vm.account_id &&
                !vm.account_concept &&
                !vm.account_amount &&
                !vm.account_tax_id
            ) {
                vm.showMessage(
                    "custom",
                    "Alerta!",
                    "warning",
                    "screen-error",
                    "Debe indicar todos los datos solicitados"
                );
                return;
            }

            if (Number(vm.account_amount) > Number(vm.selected_account_amount)) {
                vm.showMessage(
                    "custom",
                    "Alerta!",
                    "warning",
                    "screen-error",
                    "El monto a comprometer no puede ser mayor al asignado"
                );
                return;
            }

            let specificAction = {};
            let account = {};

            await vm
                .getSpecificActionDetail(vm.specific_action_id)
                .then((detail) => (specificAction = detail.record));

            await vm
                .getAccountDetail(vm.account_id)
                .then((detail) => (account = detail.record));

            if (vm.editIndex != null) {
                let amountEdit = vm.record.accounts[vm.editIndex]['amountEdit'];
                vm.record.accounts.splice(vm.editIndex, 1);

                vm.record.accounts.push({
                    spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                    code: account.code,
                    description: vm.account_concept,
                    amount: vm.account_amount,
                    specific_action_id: vm.specific_action_id,
                    account_id: vm.account_id,
                    tax_id: vm.account_tax_id,
                    amountEdit: amountEdit,
                    operation: '',
                });

                if (vm.account_tax_id) {
                    let tax;
                    let tax_percentage;
                    let tax_description;
                    for (tax of vm.taxesData) {
                        if (vm.account_tax_id == tax.id) {
                            tax_description = tax.description;
                            tax_percentage = tax.histories[0].percentage;
                        }
                    }

                    vm.record.tax_accounts.push({
                        spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                        code: account.code,
                        description: tax_description,
                        amount: (vm.account_amount * tax_percentage) / 100,
                        specific_action_id: vm.specific_action_id,
                        account_id: vm.account_id,
                        tax_id: vm.account_tax_id,
                    });
                }

                $("#add_account").find(".close").click();

                vm.specific_action_id = "";
                vm.account_id = "";
                vm.account_concept = "";
                vm.account_amount = 0;
                vm.account_tax_id = "";
                vm.editIndex = null;
            } else {
                vm.record.accounts.push({
                    spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                    code: account.code,
                    description: vm.account_concept,
                    amount: vm.account_amount,
                    specific_action_id: vm.specific_action_id,
                    account_id: vm.account_id,
                    tax_id: vm.account_tax_id,
                });

                if (vm.account_tax_id) {
                    let tax;
                    let tax_percentage;
                    let tax_description;
                    for (tax of vm.taxesData) {
                        if (vm.account_tax_id == tax.id) {
                            tax_description = tax.description;
                            tax_percentage = tax.histories[0].percentage;
                        }
                    }

                    vm.record.tax_accounts.push({
                        spac_description: `${specificAction.specificable.code}-${specificAction.code} | ${specificAction.name}`,
                        code: account.code,
                        description: tax_description,
                        amount: (vm.account_amount * tax_percentage) / 100,
                        specific_action_id: vm.specific_action_id,
                        account_id: vm.account_id,
                        tax_id: vm.account_tax_id,
                    });
                }

                bootbox.confirm({
                    title: "Agregar cuenta",
                    message: `Desea agregar otra cuenta?`,
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancelar',
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirmar',
                        },
                    },
                    callback: function (result) {
                        if (!result) {
                            $("#add_account").find(".close").click();
                        }

                        vm.specific_action_id = "";
                        vm.account_id = "";
                        vm.account_concept = "";
                        vm.account_amount = 0;
                        vm.account_tax_id = "";
                    },
                });
            }
        },

        /**
         * Agrega un documento al compromiso
         *
         * @method     addDocument
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        addDocument(sourceId) {
            const vm = this;
            vm.record.documentToCompromise = JSON.parse(
                JSON.stringify(
                    vm.document_sources.filter((doc) => {
                        return doc.id === sourceId;
                    })[0]
                )
            );
        },
        /**
         * Obtiene las Acciones Específicas
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         * @param {string} type Tipo de registro
         */
        async getSpecificActions() {
            const vm = this;
            vm.loading = true;
            vm.specific_actions = [];
            vm.accounts = [];

            if (
                vm.record.compromised_at &&
                vm.record.source_document &&
                vm.record.institution_id
            ) {
                let year = vm.record.compromised_at.split("-")[0];
                let url = `${window.app_url}/budget/get-group-specific-actions/${year}/1/${vm.record.institution_id}`;
                await axios
                    .get(url)
                    .then((response) => {
                        vm.specific_actions = response.data;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } else {
                $("#add_account").find(".close").click();
                bootbox.alert(
                    "Debe indicar los datos del compromiso antes de agregar cuentas"
                );
            }

            if (vm.editIndex != null) {
                vm.specific_action_id = vm.record.accounts[vm.editIndex]['specific_action_id'];
            }

            vm.loading = false;
        },
        /**
         * Obtiene las cuentas presupuestarias formuladas de la acción específica seleccionada
         *
         * @method    getAccounts
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        async getAccounts() {
            const vm = this;
            vm.loading = true;
            vm.accounts = [];

            if (vm.specific_action_id) {
                let specificActionId = vm.specific_action_id;
                let compromisedAt = vm.record.compromised_at;
                await axios
                    .get(
                        `${window.app_url}/budget/get-opened-accounts/${specificActionId}/${compromisedAt}`
                    )
                    .then((response) => {
                        if (response.data.result) {
                            vm.accounts = response.data.records;
                        }
                        if (
                            response.data.records.length === 1 &&
                            response.data.records[0].id === ""
                        ) {
                            vm.showMessage(
                                "custom",
                                "Alerta!",
                                "danger",
                                "screen-error",
                                `No existen cuentas aperturadas para esta acción específica o con saldo para la fecha
                                                                seleccionada`
                            );
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
                if (vm.editIndex != null) {
                    vm.account_id = vm.record.accounts[vm.editIndex]['account_id'];
                }
            }

            vm.loading = false;
        },
        /**
         * Obtiene las cuentas presupuestarias formuladas de la acción específica seleccionada
         *
         * @method   getAmountAccounts
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        getAmountAccounts() {
            const vm = this;

            const result = vm.accounts.filter(
                (account) => account.id == vm.account_id
            );
             if( typeof result[0] !== 'undefined' ){
                     vm.selected_account_amount = result[0].amount;
             }
        },

        /**
         * Obtiene los registros precomprometidos que aún no han sido comprometidos
         *
         * @method     getDocumentSources
         *
         * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         *
         * @license    [description]
         *
         * @return     {[type]}              [description]
         */
        getDocumentSources() {
            let vm = this;
            let appUrl = window.app_url;
            let institutionId = vm.record.institution_id;
            let year = window.execution_year;
            vm.loading = true;
            axios
                .get(
                    `${appUrl}/budget/compromises/get-document-sources/${institutionId}/${year}`
                )
                .then((response) => {
                    vm.document_sources = response.data.records;
                    vm.loading = false;
                })
                .catch((error) => {
                    console.warn(error);
                });
        },
        /**
         * Determina si se ha seleccionado un documento desde otras fuentes para ser comprometido
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         *
         * @return    {Boolean}              Devuelve verdadero si tiene un documento seleccionado,
         *                                   de lo contrario devuelve falso
         */
        hasDocumentSelected() {
            const vm = this;
            let compromise = vm.record.documentToCompromise;
            return (
                typeof compromise.budget_compromise_details !== "undefined" &&
                compromise.budget_compromise_details.length > 0
            );
        },
        /**
         * Muestra el item del compromiso proveniente de fuentes externas que se esta comprometiendo
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         *
         * @return {String} Texto con información del ítem a comprometer
         */
        setItemCompromise() {
            const vm = this;
            let totalItems =
                vm.record.documentToCompromise.budget_compromise_details.length;
            let currentItem = vm.record.accounts.length;
            return `Item ${currentItem} / ${totalItems}`;
        },
        /**
         * Listado de impuestos
         *
         * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         */
        getTaxes() {
            const vm = this;
            axios
                .get(`${window.app_url}/get-taxes`)
                .then((response) => {
                    if (response.data.records.length > 0) {
                        vm.taxesData = response.data.records;
                        for (let tax of vm.taxesData) {
                            vm.taxes.push({
                                id: tax.id,
                                text: tax.name + " " + tax.histories[0].percentage + "%",
                            });
                        }
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        /**
         * Método que permite actualizar información
         *
         * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
         * @author  Daniel Contreras <dcontreras@cenditel.gob.ve> | <exodiadaniel@gmail.com>
         *
         * @param  {string} url Ruta de la acci´on que modificará los datos
         */
        updateRecord(url) {
            const vm = this;
            vm.loading = true;
            var fields = {};
            url = vm.setUrl(url);

            for (let account of vm.record.accounts) {
                if (parseFloat(account.amountEdit) == parseFloat(account.amount)) {
                    account.operation = 'I';
                    account.amountEdit = account.amountEdit;
                }
                if (parseFloat(account.amountEdit) > parseFloat(account.amount)) {
                    account.operation = 'S';
                    account.amountEdit = parseFloat(account.amountEdit) - parseFloat(account.amount);
                }
                if (parseFloat(account.amountEdit) < parseFloat(account.amount)) {
                    console.log(parseFloat(account.amount))
                    console.log(parseFloat(account.amountEdit))
                    account.operation = 'R';
                    account.amountEdit = parseFloat(account.amountEdit) - parseFloat(account.amount);
                }
                if (typeof account.amountEdit === 'undefined') {
                    account.amountEdit = 0;
                }

                if (typeof account.operation === 'undefined') {
                    account.operation = '';
                }
            }

            for (var index in vm.record) {
                fields[index] = vm.record[index];
            }
            axios.patch(`${url}${(url.endsWith('/'))?'':'/'}${vm.record.id}`, fields).then(response => {
                if (typeof(response.data.redirect) !== "undefined") {
                    location.href = response.data.redirect;
                }
                else {
                    vm.readRecords(url);
                    vm.reset();
                    vm.loading = false;
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
                vm.loading = false;
            });
        },
    },
    created() {},
    mounted() {
        let vm = this;
        vm.reset();
        vm.getInstitutions();
        vm.taxesData = [];
        vm.getTaxes();
        setTimeout(() => {
            if (vm.edit_object) {
                vm.loadEditData();
            }
        }, 1000);

        $("#add_source")
            .on("shown.bs.modal", function () {
                /** Carga los documentos que faltan por comprometer */
                vm.getDocumentSources();
            })
            .on("hide.bs.modal", function () {
                /** @type array Inicializa el arreglo de los documentos por comprometer */
                vm.document_sources = [];
            });

        $("#add_account")
            .on("shown.bs.modal", function () {
                if (vm.specific_actions.length === 0) {
                    /** Carga las acciones específicas para la respectiva formulación */
                    vm.getSpecificActions();
                }
            })
            .on("hide.bs.modal", function () {
                /** @type {Array} Inicializa el arreglo de acciones específicas a seleccionar */
                vm.specific_actions = [];
                /** @type array Inicializa el arreglo de las cuentas presupuestarias seleccionadas */
                vm.accounts = [];
            });
    },
};
</script>
