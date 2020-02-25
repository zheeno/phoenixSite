<html>
<head>
    <title>Phoenix Courier - Parcel Tracking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="../../assets/img/phoenixCourier.png" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="../../assets/css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="../../assets/css/style.css" rel="stylesheet">
    <?php require_once "./phoenixCourierDB.php" ?>
</head>
<body class="bg-white">
    <div class="container-fluid">
        <nav class="row navbar navbar-expand-lg bg-white pad-0">
            <!-- Collapsible content -->
        <a>
            <img src="../../assets/img/phoenixCourier.png" style="width:130px;margin:10px">
        </a>
            <div class="collapse navbar-collapse right-align pad-0" id="navbarSupportedContent">
                <ul class="navbar-nav pad-0">
                        <li class="btn btn-outline-blue btn-lg  no-shadow no-border nav-item">
                        <a class="nav-link" href="../../">Home</a>
                    </li>
                    <li class="btn btn-outline-blue btn-lg no-shadow no-border nav-item">
                        <a href="./" class="nav-link">Our Services</a>
                    </li>
                    <li class="btn btn-outline-blue btn-lg no-shadow no-border nav-item">
                        <a  href="./" class="nav-link">Contact Us</a>
                    </li>
                    <li onClick="$('#track-id').focus()" class="btn btn-blue btn-lg no-shadow no-border nav-item">
                        <a class="nav-link">Track a Parcel</a>
                    </li>
                </ul>
            </div>
        </nav>
        <section class="row bg-white pad-top-25 pad-bot-25 divider">
            <div class="col-12 center-align flex-center">
                <div class="row">
                    <div class="col-12">
                        <img src="../../assets/img/outfordelivery.svg">
                        <h1 class="h1-responsive pad-top-25">Track your Parcel</h1>
                    </div>
                    <form class="tracking-form pad-top-25 col-xs-10 col-sm-6 mx-auto md-form input-group">
                        <input type="text" class="form-control" placeholder="Tracking Id" id="track-id" required>
                        <button type="submit" class="btn btn-blue btn-lg no-radius no-shadow"><span class="fa fa-angle-right white-text"></span></button>
                    </form>
                    <?php if($_REQUEST['action'] == "trackParcel"){
                        $id = $_REQUEST['trackingId'];
                        $query = mysqli_query($con, "SELECT * FROM parcels WHERE tracking_id = '$id' ORDER BY id DESC");
                        if(!$query){
                            echo "<div class='col-12 pad-top-25'>
                                    <h1 class='h1-responsive' >Sorry, the tracking id provided is not associated with any parcel</h1>
                                    </div>";
                        }else{
                            echo "<div class='col-12 pad-top-25'>";
                                 //echo "<h2 class='h2-responsive'>Tracking Parcel (".$id.")</h2>";
                                 echo "<table class='table '>
                                            <thead>
                                                <tr><th class='h5-responsive'>Tracking Id #</th>
                                                <th class='h5-responsive'>Item</th>
                                                <th class='h5-responsive'>Description</th>
                                                <th class='h5-responsive'>Quantity</th>
                                                <th class='h5-responsive'>Status</th>
                                                </tr></thead>
                                            <tbody>";
                                            while($parcel = mysqli_fetch_assoc($query)){
                                                echo "<tr>
                                                        <td class='h5-responsive'>".$parcel['tracking_id']."</td>
                                                        <td class='h5-responsive'>".nl2br($parcel['item_name'])."</td>
                                                        <td class='h5-responsive'>".nl2br($parcel['item_desc'])."</td>
                                                        <td class='h5-responsive'>".$parcel['no_items']."</td>
                                                        <td class='h5-responsive'>".$parcel['transit_status']."</td>
                                                </tr>";
                                            }
                                            echo "</tbody>
                                        </table>";
                            echo "</div>";
                        }
                    }?>
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
    <script type="text/javascript" src="../../assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="../../assets/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="../../assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="../../assets/js/vanilla.js"></script>
</html>