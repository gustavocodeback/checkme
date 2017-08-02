<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Check-me</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?PHP echo base_url('img/logo.png'); ?>" />

        <!-- carrega o css -->
        <?PHP if ( isset( $css ) ): ?>
        <?PHP foreach ( $css as $file ):?>
        <link rel="stylesheet" href="<?PHP echo $file; ?>">
        <?PHP endforeach; ?>
        <?PHP endif; ?>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">

        <div class="wrapper" style="height: 100vh;">
            <?PHP $this->load->view( 'partials/navbar' ); ?>
            <?PHP $this->load->view( 'partials/aside' ); ?>
            <?PHP $this->load->view( 'partials/wrapper' ); ?>

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                <b>Versão</b> 0.0.1
                </div>
                <strong>Copyright &copy; <a href="http://www.dubsolucoes.com/">Dub Soluções</a>.</strong> Todos direitos reservados.
                reserved.
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
        
        <!-- carrega o javascript -->
        <?PHP if ( isset( $js ) ): ?>
        <?PHP foreach ( $js as $file ):?>
        <script src="<?PHP echo $file ?>"></script>
        <?PHP endforeach; ?>
        <?PHP endif; ?>

        <script>
        $(document).ready( function() {
            $('#toggleSidebar').click( function(){
                $('body').toggleClass('sidebar-open');
            })
        })
        </script>
    </body>
</html>