
<?php $__env->startSection('content'); ?>
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Equipos</div>
    <div class="right">

    </div>
</div>

<div class="header-large-title">
    <h1 class="title">Equipos</h1>
</div>
<div class="wide-block pt-2 pb-2">
    <form class="search-form">
        <div class="form-group searchbox">
            <input type="text" class="form-control" value="" placeholder="Escriba el nombre del equipo">
            <i class="input-icon">
                <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
            </i>
        </div>
    </form>
</div>


<div class="section full mt mb">
        <ul class="listview image-listview media mb-2">
            <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="#" class="item">
                    <img src="assets/img/mc.png" alt="image" class="image">
                    <div class="in">
                        <div><?php echo e($i->display_name); ?></div>
                       
                    </div>
                </a>
            </li>            

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         </ul> 
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/appgmp.entorno-virtual.com/resources/views/frontend/equipos.blade.php ENDPATH**/ ?>