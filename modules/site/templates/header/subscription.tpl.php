<div id="header">
  <div class="container">
    <div>
      <div class="dropdown" data-target="category">
        <a href="#">分类&nbsp;<small class="glyphicon glyphicon-expand"></small></a>
      </div>
      <div class="dropdown" data-target="filter">
        <a href="#">状态&nbsp;<small class="glyphicon glyphicon-expand"></small></a>
      </div>
    </div>
    <div>
      <ul id="category" class="dropdown-content">
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => 'all',
            'read' => $read
        )) ?>">所有</a></li>
<?php foreach (UserWechatCategory::findAll() as $category): ?>
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => $category->getId(),
            'read' => $read
        )) ?>"><?php echo $category->getName() ?></a></li>
<?php endforeach; ?>
      </ul>
      <ul id="filter" class="dropdown-content">
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => $category->getId(),
            'read' => 'all'
        )) ?>" data-filter="all" class="active">全部</a></li>
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => $category->getId(),
            'read' => 0
        )) ?>">未读</a></li>
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => $category->getId(),
            'read' => 1
        )) ?>">已读</a></li>
      </ul>
    </div>
  </div>
</div>