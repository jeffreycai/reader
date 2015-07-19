

  admin_<?php echo $model ?>_list:
    path: ^\/admin\/<?php echo $model ?>\/list\/?$
    isSecure: 1
    i18n: 1
    controller: <?php echo $module ?>/backend/<?php echo $model ?>_list
  admin_<?php echo $model ?>_edit:
    path: ^\/admin\/<?php echo $model ?>\/edit\/(\d+)\/?$
    isSecure: 1
    i18n: 1
    controller: <?php echo $module ?>/backend/<?php echo $model ?>_edit
  admin_<?php echo $model ?>_create:
    path: ^\/admin\/<?php echo $model ?>\/create\/?$
    isSecure: 1
    i18n: 1
    controller: <?php echo $module ?>/backend/<?php echo $model ?>_create
  admin_<?php echo $model ?>_delete:
    path: ^\/admin\/<?php echo $model ?>\/delete\/(\d+)\/?$
    isSecure: 1
    i18n: 0
    controller: <?php echo $module ?>/backend/<?php echo $model ?>_delete