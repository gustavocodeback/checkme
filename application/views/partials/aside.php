<aside class="main-sidebar">
    <section class="sidebar" style="overflow-y: scroll; height: calc( 100vh - 70px ); ">
      <ul class="sidebar-menu">
        <?PHP if ( isset( $menu ) ) :?>
            <?PHP foreach ( $menu as $item ):?>
                <li class="header"><?PHP echo $item['desc_classificacao']; ?></li>
                <?PHP foreach ( $item['rotinas'] as $rotina ): ?>
                <li class="<?PHP echo ( isset( $rotina['active'] ) && $rotina['active'] ) ? 'active ' : ''; ?>" >
                    <a href="<?PHP echo site_url($rotina['desc_link'] ); ?>"><?PHP echo $rotina['desc_rotina']; ?></a>
                </li>
                <?PHP endforeach; ?>
            <?PHP endforeach; ?>
        <?PHP endif; ?>
      </ul>
    </section>
  </aside>