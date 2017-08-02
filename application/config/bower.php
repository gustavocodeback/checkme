<?php defined('BASEPATH') OR exit('No direct script access allowed');

// arquivos padroes para todas as paginas
$config['autoload'] = [ 'jquery', 'theme', 'bootstrap' ];

// arquivos jquery
$config['jquery'] = [
    'js' => [
        'https://code.jquery.com/jquery-2.1.1.min.js'
        // base_url('assets/jquery/dist/jquery.min.js')
    ],
    'css' => [
        "https://fonts.googleapis.com/css?family=Roboto:400,700"
    ]
];

// arquivos bootstrap
$config['bootstrap'] = [
    'js' => [
        base_url('assets/bootstrap/dist/js/bootstrap.min.js')
    ],
    'css' => [
        base_url('assets/bootstrap/dist/css/bootstrap.min.css')
    ]
];

// arquivos materialize
$config['materialize'] = [
    'js' => [
        base_url('assets/materialize/dist/js/materialize.min.js')
    ],
    'css' => [
        'http://fonts.googleapis.com/icon?family=Material+Icons',
        base_url('assets/materialize/dist/css/materialize.min.css')
    ]
];

// arquivos da navbar
$config['navbar'] = [
    'js'  => [ base_url('assets/navbar/navbar.js') ],
    'css' => [ base_url('assets/navbar/navbar.css') ],
];

// arquivos da padrao
$config['default'] = [
    'js'  => [ base_url('assets/default/default.js') ],
    'css' => [ base_url('assets/default/default.css') ],
];

// arquivos do tema
$config['theme'] = [
    'js' => [
        base_url( "assets/theme/plugins/jQuery/jquery-2.2.3.min.js" ),
        base_url( "assets/theme/plugins/jQuery/jquery.mask.min.js" ),
        base_url( "assets/theme/plugins/fastclick/fastclick.js" ),
        base_url( "assets/theme/js/app.min.js" ),
        base_url( "assets/theme/plugins/datatables/jquery.dataTables.min.js" ),
        base_url( "assets/theme/plugins/sparkline/jquery.sparkline.min.js" ),
        base_url( "assets/theme/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" ),
        base_url( "assets/theme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" ),
        base_url( "assets/theme/plugins/chartjs/Chart.min.js" ),
        base_url( "assets/theme/plugins/moment/moment.js" ),
        base_url( "assets/theme/plugins/moment/moment-with-locales.js" ),
        base_url( "assets/theme/plugins/chosen/chosen.jquery.min.js" ),
        base_url( "assets/theme/plugins/chosen/chosen.proto.min.js" ),
        base_url( "assets/theme/js/pages/dashboard2.js" ),
        base_url( "assets/theme/js/demo.js" )
    ],
    'css' => [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        base_url( "assets/theme/plugins/jvectormap/jquery-jvectormap-1.2.2.css"),
        base_url( "assets/theme/plugins/datatables/dataTables.bootstrap.css"),
        base_url( "assets/theme/plugins/datatables/jquery.dataTables.min.css"),
        base_url( "assets/theme/plugins/chosen/chosen.min.css"),
        base_url( "assets/theme/css/AdminLTE.min.css"),
        base_url( "assets/theme/css/skins/_all-skins.min.css")
    ]
];
