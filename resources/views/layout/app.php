<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title?></title>
</head>
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/chocolat/dist/css/chocolat.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<body>
<div id="app">
    <div class="main-wrapper container">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="nav-collapse">
            <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
            <i class="fas fa-ellipsis-v"></i>
            </a>
            <ul class="navbar-nav">
            <li class="nav-item active"><a href="<?= url('home')?>" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="<?= url('decoder')?>" class="nav-link">Decoder</a></li>
            </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= asset('stisla-1-2.2.0/dist/assets/img/avatar/avatar-1.png')?>" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, <?= \Helpers\Session::user()->nama?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-divider"></div>
              <a href="<?= url('logout')?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
    </nav>
    <br><br>
    <?= $content?>
</div>
 <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/popper.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/tooltip.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/moment.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/stisla.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/prism/prism.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/page/bootstrap-modal.js') ?>"></script>

    <!-- Template JS File -->
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/scripts.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/custom.js') ?>"></script>
</body>
</html>