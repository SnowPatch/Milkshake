<?php
  $this->props([
    'title' => ['value' => 'Milkshake'],
    'text' => ['type' => 'String', 'default' => 'test']
  ]);
?>

<!DOCTYPE html>
<html lang="en">
<?php $this->include('includes.head'); ?>
<body>

<?php 
  $this->component('headline', [
    'text' => $this->text
  ]); 
?>

</body>
</html>