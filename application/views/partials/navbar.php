<header class="main-header">

  <a href="index.php" class="logo">
    <span class="logo-mini">S</span>
    <span class="logo-lg">
      <img class="control-img" width="80px" src="<?PHP echo base_url( 'img/logo.png' ); ?>" width="100px">
    </span>
  </a>

  <nav class="navbar navbar-static-top">
    <a id="toggleSidebar" class="sidebar-toggle visible-xs" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="width: 50px">
                <?PHP $foto = isset($user['foto']) && !empty($user['foto']) ? $user['foto'] : 'avatar.jpg'; ?>
                <img class="img-circle" src="<?PHP echo base_url('uploads/'.$foto ); ?>" width="100%" alt="Profile">
          </a>
          <ul class="dropdown-menu">
            <li class="header">
              <b>Ações</b>
            </li>
            <li>
            <ul class="menu">
              <li>
                <a href="<?PHP echo site_url('usuarios/index/edit/'.$user['id'] ); ?>">
                  <i class="fa fa-edit text-aqua"></i> Editar conta
                </a>
              </li>
              <li>
                <a href="<?PHP echo site_url('login/logout'); ?>">
                  <i class="fa fa-sign-out text-aqua"></i> Sair
                </a>
              </li>
            </ul>
        </li>
      </ul>
    </div>
  </nav>

</header>