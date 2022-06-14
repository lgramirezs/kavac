{{-- Barra de Navegación Superior --}}
<nav class="navbar navbar-expand-lg bg-info fixed-top">
	<div class="container-left">
		<a class="logo text-decoration-none" href="{{ route('index') }}">
            @include('layouts.logo-images', ['logo_nav' => true, 'logo_name' => true])
        </a>
        <div class="float-right">
            <div class="menu-collapse" data-toggle="tooltip" data-placement="right" title="{{ __('Minimizar panel de menú') }}">
                <i class="now-ui-icons arrows-1_minimal-left"></i>
            </div>
        </div>
	</div>
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
			data-target="#app-navbar-info" aria-controls="navbarSupportedContent" aria-expanded="false"
			aria-label="Toggle navigation">
		<i class="fa fa-navicon"></i>
	</button>
	<div class="collapse navbar-collapse"
		 id="app-navbar-info" data-nav-image="{{ asset('images/blurred-image.jpg') }}">
		<div class="navbar-translate">
			@php
             if(isset(auth()->user()->profile)){
				 if(isset(auth()->user()->profile->institution_id)){
                  $institution = App\Models\Institution::where(['id' => auth()->user()->profile->institution_id])->first();

                   
				 } else{
                  $institution = App\Models\Institution::where(['active' => true, 'default' => true])->first();

			      }
			

        

			 }
			 else{
                  $institution = App\Models\Institution::where(['active' => true, 'default' => true])->first();

			 }
			
			
				
			@endphp
			@if ($institution)
				<div class="navbar-brand">
					@if ($institution->logo()->exists() && file_exists(base_path($institution->logo->url)))
						<img src="{{ asset($institution->logo->url, Request::secure()) }}" alt="{{ __('logo') }}"
							 class="img-fluid">
					@endif
					{!! $institution->acronym !!} | {!! $institution->name !!}
				</div>
			@endif
		</div>

		<ul class="navbar-nav">
			@if (Auth::user()->hasVerifiedEmail())
                @php
                    $parameters = App\Models\Parameter::where([
                        'active' => true, 'required_by' => 'core', 'p_value' => 'true'
                    ])->get();
                    $notify = $parameters->filter(function($param) {
                        return $param->p_key === 'notify';
                    })->first();
                    $chat = $parameters->filter(function($param) {
                        return $param->p_key === 'chat';
                    })->first();
                    $support = $parameters->filter(function($param) {
                        return $param->p_key === 'support';
                    })->first();
                @endphp
				@if ($notify)
                    <notifications :unreads="{{ auth()->user()->unreadNotifications }}"
                                   :user-id="{!! auth()->user()->id !!}" 
                                   list-notifications-url="{!! route('notifications.list') !!}"></notifications>
				@endif

                @if ($chat)
					<li class="nav-item">
						<a class="nav-link btn btn-sm btn-info" href="javascript:void(0)" title="{{ __('Chat') }}"
                           data-toggle="tooltip">
							<i class="now-ui-icons ui-2_chat-round"></i>
						</a>
					</li>
				@endif
				@if ($support)
					<li class="nav-item">
						<a class="nav-link btn btn-sm btn-info" href="javascript:void(0)"
                           title="{{ __('Contacte con soporte técnico') }}" data-toggle="tooltip">
							<i class="now-ui-icons objects_support-17"></i>
						</a>
					</li>
				@endif
				@if(Auth::user()->hasRole('admin'))
					<li class="nav-item">
						<a class="nav-link btn btn-sm btn-info"
						   href="{{ route('backup.index') }}"
						   title="{{ __('Respaldos de Base de Datos') }}"
						   data-toggle="tooltip">
							<i class="fa fa-database"></i>
						</a>
					</li>
				@endif
			@endif
            <li class="nav-item">
                <a class="nav-link btn btn-sm btn-info fullscreen"
                   href="javascript:void(0)" onclick="fullScreen()"
                   title="{{ __('Pantalla completa') }}"
                   data-toggle="tooltip">
                    <i class="mdi mdi-fullscreen" style="font-size: 1.287em"></i>
                </a>
            </li>
			<li class="nav-item dropdown">
				<a href="javascript:void(0)" class="nav-link dropdown-toggle btn btn-sm btn-info"
                   id="list_options_user" data-toggle="dropdown" aria-expanded="false"
                   title="{{ __('Mi configuración y datos') }}">
				   	<i class="now-ui-icons users_single-02" aria-hidden="true"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right"
					 aria-labelledby="list_options_user">
					<a class="dropdown-header">{{ __('USUARIO') }}</a>
					@if (Auth::user()->hasVerifiedEmail())
						<a class="dropdown-item" href="{{ route('my.settings') }}" data-toggle="tooltip"
                           data-placement="left" title="{{ __('Establecer configuración personalizada') }}">
							<i class="ion-gear-a"></i>{{ __('Configurar Cuenta') }}</a>
						<a class="dropdown-item" href="{{ url('users') . "/" . Auth::user()->id }}"
						   title="{{ __('Actualizar datos de perfil del usuario') }}"
						   data-toggle="tooltip" data-placement="left">
							<i class="ion-person"></i>{{ __('Mi Perfil') }}</a>
						{{-- <a class="dropdown-item" href="{{ url('users') . "/" . Auth::user()->id }}/?tab=activity"
                           title="{{ __('Ver actividad en la aplicación') }}" data-toggle="tooltip"
                           data-placement="left">
							<i class="ion-ios-star"></i>{{ __('Registro de Actividad') }}</a> --}}
						<lock-screen-option></lock-screen-option>
						<a id="doc-user" class="dropdown-item" href="" target="_blank"
                           title="{{ __('Ayuda') }} / {{ __('Manual de usuario') }}" data-toggle="tooltip"
                           data-placement="left">
							<i class="ion-help-circled"></i>{{ __('Ayuda') }}</a>
						<div class="divider"></div>
					@endif
					<a class="dropdown-item" href="{{ route('logout') }}" title="{{ __('Salir de la aplicación') }}"
					   data-toggle="tooltip" data-placement="left"
					   onclick="event.preventDefault();logout();">
						<i class="ion-log-out"></i> {{ __('Salir') }}
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST"
						  style="display: none;">
						{{ csrf_field() }}
					</form>
				</div>
			</li>
		</ul>
	</div>
</nav>
