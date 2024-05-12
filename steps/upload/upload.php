<?php $relPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>

<link rel="stylesheet" href="<?= $relPath . "/upload.css" ?>">
<script src="<?= $relPath . "/../../lib/circle-progress.min.js" ?>" defer></script>
<script src="<?= $relPath . "/upload.js" ?>" defer></script>

<div id="uploader-panel" class="scrollbarhidden">
    <span class="big-text">Upload</span>
    <div id="wall">
        
    </div>
</div>
<?php
    include('../../modules/toasts/toasts.php');
    include('../../modules/authModal/authModal.php');
?>