<?php
  $this->props([
    'text' => ['type' => 'String', 'required' => true]
  ]);
?>

<div class="headline">
  <h1><?= $this->text ?></h1>
</div>