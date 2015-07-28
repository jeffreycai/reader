<div id="header">
  <div class="container">
    <div>
      <div class="dropdown" data-target="category">
        <a href="#"><?php if (!empty($category)): ?><?php echo UserWechatCategory::findById($category)->getName() ?><?php else: ?>分类<?php endif; ?>&nbsp;<small class="glyphicon glyphicon-expand"></small></a>
      </div>
      <a id="filter-readonly" class="glyphicon glyphicon-<?php echo $unread ? 'check' : 'unchecked' ?>" href="<?php echo uri('articles') . build_query_string(array(
          'category' => $category,
          'unread' => $unread ? 0 : 1
      )) ?>">只显示未读</a>
    </div>
    <div>
      <ul id="category" class="dropdown-content">
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => null,
            'unread' => $unread
        )) ?>">所有</a></li>
<?php foreach (UserWechatCategory::findAll() as $category): ?>
        <li><a href="<?php echo uri('articles') . build_query_string(array(
            'category' => $category->getId(),
            'unread' => $unread
        )) ?>"><?php echo $category->getName() ?></a></li>
<?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>