<template>
    <a class="dropdown-item" href="javascript:void(0)" id="lock-screen-option" @click="lockScreenNow"
       title="Bloquear pantalla de la aplicación" data-toggle="tooltip" data-placement="left">
        <i class="ion-android-lock"></i>Bloquear Pantalla
    </a>
</template>

<script>
    export default {
        data() {
            return {}
        },
        methods: {
            async lockScreenNow() {
                const vm = this;
                
                vm.lockscreen.timer_timeout = setTimeout(function() {
                    $(document.body).addClass('modalBlur');
                    $(".modal-lockscreen").modal('show');
                    window.screen_locked = true;
                    axios.post('/set-lockscreen-data', {
                        lock_screen: true
                    }).catch(error => {
                        console.warn(error);
                    });
                }, 0 * 60000);
                vm.lockscreen.lock = true;
				vm.lockscreen.time = 0;
            }
        }
    }
</script>