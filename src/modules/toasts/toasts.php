<?php $relPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>

<link rel="stylesheet" href="<?= $relPath . "/toasts.css" ?>">
<script src="<?= $relPath . "/toasts.js" ?>"></script>
<div class="toast hidden-toast" id="error-toast">
    <span class="toast-icon material-symbols-rounded">warning</span>
    <div>
        <p class="toast-status">Error...</p>
        <p class="toast-message"></p>
        <p class="detailed-message"></p>
    </div>
    <div class="actions">
        <span class="material-symbols-rounded" onclick="hideToast()">close</span>
        <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
    </div>
</div>
<div class="toast hidden-toast" id="success-toast">
    <span class="toast-icon material-symbols-rounded">done_all</span>
    <div>
        <p class="toast-status">Success !</p>
        <p class="toast-message"></p>
        <p class="detailed-message"></p>
    </div>
    <div class="actions">
        <span class="material-symbols-rounded" onclick="hideToast()">close</span>
        <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
    </div>
</div>
<div class="toast hidden-toast" id="info-toast">
    <span class="toast-icon material-symbols-rounded">info</span>
    <div>
        <p class="toast-status">Information</p>
        <p class="toast-message"></p>
        <p class="detailed-message"></p>
    </div>
    <div class="actions">
        <span class="material-symbols-rounded" onclick="hideToast()">close</span>
        <span class="material-symbols-rounded" onclick="copyToastMessage($(this))">content_copy</span>
    </div>
</div>
<div id="toast-hider"></div>