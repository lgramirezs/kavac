<?php

/** Proveedores de servicios generales del sistema */
namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LoginEventHandler;
use App\Listeners\RecordFailedLoginAttempt;

/**
 * @class EventServiceProvider
 * @brief Proveedor de servicios de los eventos
 *
 * Gestiona los proveedores de servicios de los eventos
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Las asignaciones de detectores de eventos para la aplicación..
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginEventHandler::class,
        ],
        Failed::class => [
            RecordFailedLoginAttempt::class,
        ],
    ];

    /**
     * Registre cualquier evento para su aplicación.
     *
     * @method  boot
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
