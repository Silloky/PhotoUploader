<?php $relPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>

<link rel="stylesheet" href="<?= $relPath . "/confirmation.css" ?>">
<script src="<?= $relPath . "/confirmation.js" ?>" defer></script>

<div></div>
<div id="reviewer-panel" class="scrollbarhidden">
    <span class="big-text">Review</span>
    <div id="list">
        
    </div>
</div>
<div></div>
<div id="bottom-bar">
    <button type="button">Continue</button>
    <button type="button" onclick="changeStep('editing')">Go back</button>
</div>
<?php
    include('../../modules/toasts/toasts.php');
?>