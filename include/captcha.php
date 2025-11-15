<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/apiKeys.php';
global $captchaPublicApiKey;
?>
<script>
    window.captchaPublicKey = '<?= $captchaPublicApiKey ?>'
</script>
