
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
<?php if(isset($subEquipos)): ?>
    <div class="extraHeader">
            <ul class="nav nav-tabs style1" role="tablist">
            <?php
                $selected="true";$active="active";
            ?>
            <?php $__currentLoopData = $subEquipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item ">
                    <a class="nav-link <?php echo e($active); ?>" data-toggle="tab" href="#<?php echo e($s->name); ?>" role="tab" aria-selected="<?php echo e($selected); ?>">
                        <?php echo e($s->display_name); ?>

                    </a>
                </li>
                <?php
                    $selected="false";$active="";
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
    </div>
    <?php $__currentLoopData = $subEquipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="tab-pane fade show active" id="<?php echo e($s->name); ?>" role="tabpanel">
        <div class="section full mt-1">
            
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<div class="section full mt mb">
        <ul class="listview image-listview media mb-2">
            <?php if(isset($tipos)): ?>
                <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route('equipos.index',$t->id)); ?>" class="item">
                        <img src="<?php echo e(url('assets/img/mc.svg')); ?>" alt="image" class="image">
                        <div class="in">
                            <div><?php echo e($t->display_name); ?></div>
                        
                        </div>
                    </a>
                </li>            
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif(isset($equipos)): ?>
                <?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="multi-level">
                        <a href="#" class="item">
                            <div class="imageWrapper">
                                <img src="<?php echo e(url('assets/img/mc2.png')); ?>" alt="image" class="imaged w64">
                            </div>
                            <div class="in">
                                <div><?php echo e($e->numero_parte); ?></div>
                            </div>
                        </a>
                        <!-- sub menu -->
                        <ul class="listview image-listview" style="display: none;">
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Photos
                                        <span class="badge badge-danger">10</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-secondary">
                                        <ion-icon name="videocam-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Videos</div>
                                        <span class="text-muted">None</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated" aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- * sub menu -->
                    </li>                   
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
         </ul> 
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/appgmp.entorno-virtual.com/resources/views/frontend/equipos.blade.php ENDPATH**/ ?>