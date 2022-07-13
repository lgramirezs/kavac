<template>
    <div class="modal fade" id="modalUserSettings" tabindex="-1" role="dialog" 
         aria-labelledby="modalUserSettingsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserSettingsTitle">Configuración del usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="custom-control custom-switch" data-toggle="tooltip" 
                                 title="Bloquear / Desbloquear usuario" >
                                <input type="checkbox" class="custom-control-input" id="settingUserBlock" 
                                       v-model="record.blocked_at" :value="true">
                                <label class="custom-control-label" for="settingUserBlock">
                                    Bloquear / Desbloquear usuario
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="custom-control custom-switch" data-toggle="tooltip" 
                                 title="Activar / Desactivar usuario" >
                                <input type="checkbox" class="custom-control-input" id="settingUserActive" 
                                       v-model="record.active" :value="true">
                                <label class="custom-control-label" for="settingUserActive">
                                    Activar / Desactivar usuario
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="setRecord()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                record: {
                    userId: '',
                    blocked_at: false,
                    active: false
                }
            }
        },
        methods: {
            getUserInfo(id) {
                const vm = this;
                if (!id) {
                    return false;
                }
                axios.get(`${window.app_url}/user-info/${id}`).then(response => {
                    vm.record.userId = id;
                    vm.record.blocked_at = (response.data.user.blocked_at !== null) ? true : false;
                    vm.record.active = (response.data.user.active===true)?true:false;
                }).catch(error => {
                    console.error(error);
                });
            },
            async setRecord() {
                const vm = this;
                vm.loading = true;
                await axios.post(`${window.app_url}/auth/settings/users`, {
                    user_id: vm.record.userId,
                    blocked_at: vm.record.blocked_at,
                    active: vm.record.active
                }).then(response => {
                    vm.showMessage(
                        'custom', 'Éxito', 'success', 'screen-ok', 'Configuración establecida'
                    );
                    $("#modalUserSettings").find('.close').click();
                }).catch(error => {
                    console.error(error);
                });
                vm.loading = false;
            }
        },
        mounted() {
            const vm = this;
            
            $("#modalUserSettings").on("show.bs.modal", function () {
                vm.getUserInfo(window.userId || '');
            });
            $('#modalUserSettings').on('hidden.bs.modal', function (e) {
                vm.record = {
                    userId: '',
                    blocked_at: false,
                    active: false
                };
            });
        }
    };
</script>