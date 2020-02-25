<html>
<head>
    <title>Phoenix Courier - Console</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="../assets/img/phoenixCourier.png" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="./assets/css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="./assets/css/style.css" rel="stylesheet">
    <?php require_once "./phoenixCourierDB.php" ?>
</head>
<body class="bg-white">
    <div class="container-fluid">
        <nav class="row navbar navbar-expand-lg bg-white pad-0">
            <!-- Collapsible content -->
        <a href="./">
            <img src="./assets/img/phoenixCourier.png" style="width:80px;margin:10px">
        </a>
            <div class="collapse navbar-collapse right-align pad-0" id="navbarSupportedContent">
                <ul class="navbar-nav pad-0 center-align">
                    <li class="all-parcels btn btn-blue btn-sm no-shadow no-border nav-item">
                        <a class="nav-link">All Parcels</a>
                    </li>
                    <li class="send-parcel btn btn-blue btn-sm no-shadow no-border nav-item">
                        <a class="nav-link">Send Parcel</a>
                    </li>
                    <li class="find-parcel btn btn-blue btn-sm no-shadow no-border nav-item">
                        <a class="nav-link">Find Parcel</a>
                    </li>
                    <li class="btn btn-blue btn-sm no-shadow no-border nav-item">
                        <a class="nav-link">Log Out</a>
                    </li>
                </ul>
            </div>
        </nav>
        <section class="row bg-white pad-top-25 pad-bot-25 divider">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 left-align">
                        <h1 class="h1-responsive pad-top-25">Console</h1>
                        <form class="find-parcel-form input-group">
                            <input type="text" class="form-control" placeholder="Tracking ID / Parcel Name" id="keyword">
                            <button type="submit" class="btn btn-blue fa fa-search" ></button>
                        </form>
                    </div>
                    <div class="console-screen col-12"></div>
                </div>
            </div>
        </section>
        <footer class="row pad-top-25 pad-bot-25">
            <div class="col-12 center-align">
                <small class="grey-text ">Copyright &copy; Phoenix Courier Courier Limited</small>
            </div>
        </footer>
    </div>
</body>
    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="./assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="./assets/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="./assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="./assets/js/vanilla.js"></script>
</html>