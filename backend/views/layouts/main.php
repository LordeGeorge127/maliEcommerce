<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body id="page-top">
    <?php $this->beginBody() ?>


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo \yii\helpers\Url::to(['/product/index']) ?>">
                <!--            <div class="sidebar-brand-icon rotate-n-15">-->
                <!--                <i class="fas fa-laugh-wink"></i>-->
                <!--            </div>-->
                <div class="sidebar-brand-text mx-3"><?php echo Yii::$app->name?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo \yii\helpers\Url::to(['/product/index']) ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">
<!--            href="--><?php //echo \yii\helpers\Url::to(['/site/logout']) ?><!--">Logout</a>-->


            <!-- Nav Item - -->

            <li class="nav-item">
                <a class="nav-link" href="<?= \yii\helpers\Url::to(['/product/create']) ?>">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Add Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo \yii\helpers\Url::to(['/product/index'])?>">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Products</span>
                </a>
            </li>  <li class="nav-item">
                <a class="nav-link" href="<?php echo \yii\helpers\Url::to(['/order/index'])?>">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Orders</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                 aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                               placeholder="Search for..." aria-label="Search"
                                               aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hello
                                <?php echo Yii::$app->user->identity->getDisplayName(); ?>
                            </span><img class="img-profile rounded-circle"
                                        src="/img/undraw_profile.svg">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a data-method="post" class="dropdown-item"
                                       href="<?php echo \yii\helpers\Url::to(['/site/logout'])?>">
                                        Logout
                                    </a>
                                </li>
<!--                                <li>-->
<!--                                    <form id="logout-form" action="--><?php //echo \yii\helpers\Url::to(['/site/logout']) ?><!--" method="post" style="display: none;">-->
<!--                                        <input type="hidden" name="_csrf" value="--><?php //=Yii::$app->request->getCsrfToken()?><!--">-->
<!--                                    </form>-->
<!--                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>-->
<!--                                </li>-->
                                <li><hr class="dropdown-divider" /></li>
<!--                                <li><a class="dropdown-item" href="#">popular</a></li>-->

                            </ul>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <div class="p-4">
                    <?php echo $content ?>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <?php echo Yii::$app->name ?>  <?php echo date('Y') ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true"> 
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a data-method="post"
                       class="btn btn-primary"
                       href="<?php echo \yii\helpers\Url::to(['/site/logout']) ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
    <?php echo $this->blocks['bodyEndScript'] ?? ''?>
    </body>
    </html>
<?php $this->endPage() ?>