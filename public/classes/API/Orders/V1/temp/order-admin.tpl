<?php 
    $props = json_decode($order->props, true);
    $code = $props['Код']['value'] ?? null;
?>
<div>Код заказа: <?= $code; ?></div>
