<nav style="margin-bottom: 0" role="navigation" class="navbar navbar-default navbar-fixed-top">

  <div class="navbar-header">
    <button data-target=".sidebar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="<?php print_uri('admin') ?>" class="navbar-brand"><?php echo i18n(array('en' => 'Backend Panel', 'zh' => '后台面板')); ?></a>
  </div>


  <ul class="nav navbar-top-links navbar-right">
    <!-- frontend -->
    <li class="dropdown">
      <a href="<?php print_uri('') ?>" title="<?php i18n_echo(array('en' => 'Go to frontend', 'zh' => '前往前台')) ?>"><i class="fa fa-desktop"></i></a>
    </li>
    <?php if ($settings['i18n']): ?>
      <!-- language -->
      <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle">
          <i class="fa fa-language"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu">
          <?php foreach ($settings['i18n_lang'] as $lang => $name): ?>
          <li>
            <a href="/<?php echo get_sub_root(); echo get_language(); ?>/switch/<?php echo $lang ?>"><?php echo $name; ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endif; ?>
    <!-- user -->
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="dropdown-toggle">
        <i class="fa fa-user"></i>  <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu">
        <li>
          <a href="<?php print_uri('admin/logout') ?>"><?php i18n_echo(array('en' => 'Logout', 'zh' => '登出')) ?></a>
        </li>
      </ul>
    </li>

    
    
    <?php echo Backend::renderTopNavRegistry(); ?>

  </ul>

  <div role="navigation" class="navbar-default navbar-static-side">
    <div class="sidebar-collapse">
      <ul id="side-menu" class="nav">
        <li class="sidebar-search">
          <div class="input-group custom-search-form">
            <input type="text" placeholder="Search..." class="form-control">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default">
                <i class="fa fa-search"></i>
              </button>
            </span>
          </div>
          <!-- /input-group -->
        </li>
        <li>
          <a href="<?php print_uri('admin/dashboard') ?>"><i class="fa fa-dashboard fa-fw"></i> <?php i18n_echo(array('en' => 'Dashboard', 'zh' => '面板')) ?></a>
        </li>

        <?php echo Backend::renderSideNavRegistry(); ?>
        
      </ul>
      <!-- /#side-menu -->
    </div>
    <!-- /.sidebar-collapse -->
  </div>
  <!-- /.navbar-static-side -->
</nav>