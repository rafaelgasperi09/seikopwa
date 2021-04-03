
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
    <h4 class="subtitle">Listado asignado</h4>
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
            <?php $__currentLoopData = array(1,2,3,4,5,6,7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <li class="multi-level">
                 <a href="#" class="item">
                     <div class="imageWrapper">
                         <img src="assets/img/sample/photo/1.jpg" alt="image" class="imaged w64">
                     </div>
                     <div class="in">
                         <div>Simple List</div>
                     </div>
                 </a>
                 <!-- sub menu -->
                 <ul class="listview simple-listview">
                    <li>Item 1</li>
                    <li>Item 2</li>
                    <li>Item 3</li>
                </ul>
                <!-- * sub menu -->
             </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 

         </ul> 
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/appgmp.entorno-virtual.com/resources/views/frontend/equipos.blade.php ENDPATH**/ ?>