
<script>
    $(document).one('common:ready', function() {
        if(common && common.modules.notification && common.modules.notification.menu) {
            common.modules.notification.menu.updateCount(<?= $count ?>);
        }
    });
</script>