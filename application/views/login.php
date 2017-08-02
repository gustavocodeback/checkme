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
        <meta name="description" content="Página de login do app checkme">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?PHP echo base_url('img/logo.png'); ?>" />
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper" style="height: 100vh">
            <div class="login-box">
                <div class="login-logo">
                    <img class="control-img" src="<?PHP echo base_url( 'img/logo.png' ); ?> " height="50px">
                </div>
                <div class="login-box-body">
                    <form id="formpk" action="<?PHP echo site_url('login/logar'); ?>" method="post">
                        <?PHP if ( isset( $errors ) && !empty( $errors ) ): ?>
                        <div class="alert alert-danger">
                            <?PHP echo $errors; ?>
                        </div>
                        <?PHP endif; ?>
                        <div class="form-group has-feedback">
                            <input type="text" name="email" class="form-control" placeholder="E-mail" required>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-8"></div>
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Logar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- carrega o css -->
        <?PHP if ( isset( $css ) ): ?>
        <?PHP foreach ( $css as $file ):?>
        <link rel="stylesheet" href="<?PHP echo $file; ?>">
        <?PHP endforeach; ?>
        <?PHP endif; ?>

        <!-- carrega o javascript -->
        <?PHP if ( isset( $js ) ): ?>
        <?PHP foreach ( $js as $file ):?>
        <script src="<?PHP echo $file ?>"></script>
        <?PHP endforeach; ?>
        <?PHP endif; ?>
    </body>
</html>