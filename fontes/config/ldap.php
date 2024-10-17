<?php

return [

    'logging' => env('LDAP_LOGGING', false),

    'connections' => [
        'default' => [
            'auto_connect' => env('LDAP_AUTO_CONNECT', false),

            'connection' => Adldap\Connections\Ldap::class,

            'settings' => [

                'schema' => env('LDAP_SCHEMA', '') == 'OpenLDAP' ?
                    Adldap\Schemas\OpenLDAP::class :
                    ( env('LDAP_SCHEMA', '') == 'FreeIPA' ?
                        Adldap\Schemas\FreeIPA::class :
                        Adldap\Schemas\ActiveDirectory::class ),

                // remove the default values of these options:
                'hosts' => explode(' ', env('LDAP_HOSTS', '')),
                'base_dn' => env('LDAP_BASE_DN', ''),
                'username' => env('LDAP_USERNAME', ''),
                'password' => env('LDAP_PASSWORD', ''),
                // and talk to your LDAP administrator about these other options.
                // do not modify them here, use .env!
                'account_prefix' => env('LDAP_ACCOUNT_PREFIX', ''),
                'account_suffix' => env('LDAP_ACCOUNT_SUFFIX', ''),
                'port' => env('LDAP_PORT', 389),
                'timeout' => env('LDAP_TIMEOUT', 5),
                'follow_referrals' => env('LDAP_FOLLOW_REFERRALS', false),
                'use_ssl' => env('LDAP_USE_SSL', false),
                'use_tls' => env('LDAP_USE_TLS', false),

            ],
        ],
    ],
];
