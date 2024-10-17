<?php
return [
    'connection' => env('LDAP_CONNECTION', 'default'),
    'provider' => Adldap\Laravel\Auth\NoDatabaseUserProvider::class,
    'model' => \Modules\Usuario\Entities\Usuario::class,

    'rules' => [
        // Denys deleted users from authenticating.
        Adldap\Laravel\Validation\Rules\DenyTrashed::class,

        // Allows only manually imported users to authenticate.
        // Adldap\Laravel\Validation\Rules\OnlyImported::class,
    ],

    'scopes' => [

        // Only allows users with a user principal name to authenticate.
        // Suitable when using ActiveDirectory.
        Adldap\Laravel\Scopes\UpnScope::class,

        // Only allows users with a uid to authenticate.
        // Suitable when using OpenLDAP.
        // Adldap\Laravel\Scopes\UidScope::class,

    ],


    'identifiers' => [

//        'ldap' => [
//            'locate_users_by' => 'userprincipalname',
//            'bind_users_by' => 'distinguishedname',
//        ],

        'ldap' => [
            'locate_users_by' => env('LDAP_USER_SEARCH_ATTRIBUTE', ''),
            'bind_users_by' => env('LDAP_USER_BIND_ATTRIBUTE', ''),
            'user_format' => env('LDAP_USER_FULL_DN_FMT', ''),
        ],

        'database' => [
            'guid_column' => 'objectguid',
            'username_column' => 'username',
        ],


        'windows' => [
            'locate_users_by' => 'samaccountname',
            'server_key' => 'AUTH_USER',
        ],

    ],

    'passwords' => [
        'sync' => env('LDAP_PASSWORD_SYNC', false),
        'column' => 'password',
    ],

    'login_fallback' => env('LDAP_LOGIN_FALLBACK', false),

//    'sync_attributes' => [
//        'username' => 'userprincipalname',
//        'name' => 'cn',
//    ],

    'sync_attributes' => [
        // 'field_in_local_user_model' => 'attribute_in_ldap_server',
        'nr_matricula' => env('LDAP_USER_SEARCH_ATTRIBUTE', null),
        'no_usuario' => 'cn',
        'ds_email' => 'mail',
        'ds_telefone' => 'telephonenumber',
    ],


    'logging' => [
        'enabled' => env('LDAP_LOGGING', true),
        'events' => [
            \Adldap\Laravel\Events\Importing::class => \Adldap\Laravel\Listeners\LogImport::class,
            \Adldap\Laravel\Events\Synchronized::class => \Adldap\Laravel\Listeners\LogSynchronized::class,
            \Adldap\Laravel\Events\Synchronizing::class => \Adldap\Laravel\Listeners\LogSynchronizing::class,
            \Adldap\Laravel\Events\Authenticated::class => \Adldap\Laravel\Listeners\LogAuthenticated::class,
            \Adldap\Laravel\Events\Authenticating::class => \Adldap\Laravel\Listeners\LogAuthentication::class,
            \Adldap\Laravel\Events\AuthenticationFailed::class => \Adldap\Laravel\Listeners\LogAuthenticationFailure::class,
            \Adldap\Laravel\Events\AuthenticationRejected::class => \Adldap\Laravel\Listeners\LogAuthenticationRejection::class,
            \Adldap\Laravel\Events\AuthenticationSuccessful::class => \Adldap\Laravel\Listeners\LogAuthenticationSuccess::class,
            \Adldap\Laravel\Events\DiscoveredWithCredentials::class => \Adldap\Laravel\Listeners\LogDiscovery::class,
            \Adldap\Laravel\Events\AuthenticatedWithWindows::class => \Adldap\Laravel\Listeners\LogWindowsAuth::class,
            \Adldap\Laravel\Events\AuthenticatedModelTrashed::class => \Adldap\Laravel\Listeners\LogTrashedModel::class,
        ],
    ],

];
