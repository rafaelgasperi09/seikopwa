<?php echo $__env->make('frontend.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<!-- App Capsule -->
<div id="appCapsule">
    <?php echo $__env->yieldContent('content'); ?>


  
</div>
<!-- * App Capsule -->

<?php echo $__env->make('frontend.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <?php echo $__env->make('frontend.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /www/wwwroot/appgmp.entorno-virtual.com/resources/views/frontend/main-layout.blade.php ENDPATH**/ ?>