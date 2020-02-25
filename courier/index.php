<html>
<head>
    <title>Phoenix Courier - Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="/assets/img/phoenixCourier.png" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="assets/css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-white">
    <div class="container-fluid">
        <nav class="row navbar navbar-expand-lg bg-white pad-0">
            <!-- Collapsible content -->
        <a class="mx-auto" href="./">
            <img src="assets/img/phoenixCourier.png" style="width:150px;margin:10px">
        </a>
            <div class="collapse navbar-collapse right-align pad-0" id="navbarSupportedContent">
                <ul class="navbar-nav pad-0">
                    <li class="btn btn-outline-blue btn-lg  no-shadow no-border nav-item">
                        <a class="nav-link" href="./">Home</a>
                    </li>
                    <li class="btn btn-outline-blue btn-lg no-shadow no-border nav-item">
                        <a href="./" class="nav-link">Our Services</a>
                    </li>
                    <li class="btn btn-outline-blue btn-lg no-shadow no-border nav-item">
                        <a href="./" class="nav-link">Contact Us</a>
                    </li>
                    <li onClick="$('#track-id').focus()" class="btn btn-blue btn-lg no-shadow no-border nav-item">
                        <a class="nav-link">Track a Parcel</a>
                    </li>
                </ul>
            </div>
        </nav>
        <section class="row h-40 bg-blue" style="background-image:url('https://s-media-cache-ak0.pinimg.com/originals/8a/21/ec/8a21ec9ba0bdeea17b66c338c00f98c9.jpg');background-size:cover;background-repeat:no-repeat;background-position:top;background-attachment:fixed">
            <div class="container-fluid pad-top-100 pad-bot-100" style="background-color:#007bff63">
                <div class="row">
                    <div class="col-10 mx-auto center-align">
                        <h1 class="h1-responsive white-text">Send a Parcel from any location</h1>
                    </div>
                </div>
                <div class="row" style="padding-top:50px">
                    <div class="col-xs-12 col-sm-8 mx-auto">
                        <div class="row center-align">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="white-text">Origin Postcode</label>
                                <input type="text" class="white-text form-control" id="postCode-1">
                            </div>
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="white-text">Destination Postcode</label>
                                <input type="text" class="white-text form-control" id="postCode-2">
                            </div>
                        </div>
                        <div class="row center-align">
                            <div class="md-form col-xs-12 col-sm-11 mx-auto drop-up btn-group">
                                <label class="white-text active">Parcel Weight</label>
                                <div class="col-12 mx-auto white-text left-align" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" style="padding:10px;font-size:16px;border-bottom:1px solid #FFF" id="weight">
                                Less than 2Kg
                                </div>
                                <div class=" weight-options dropdown-menu col-11 blue accent-4 mx-auto" style="box-shadow: 1px 3px 2px 0px #75757561;opacity:0.9">
                                    <a class="white-text btn-blue dropdown-item" href="#">Less than 2Kg</a>
                                    <a class="white-text btn-blue dropdown-item" href="#">2 - 5Kg</a>
                                    <a class="white-text btn-blue dropdown-item" href="#">5 - 10Kg</a>
                                    <a class="white-text btn-blue dropdown-item" href="#">10 - 15Kg</a>
                                </div>
                            </div>
                        </div>
                        <div class="row center-align">
                            <div class="col-12 mx-auto  ">
                                <button class="btn btn-blue btn-lg">Get a quote</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="row bg-white pad-top-25 pad-bot-25 divider">
            <div class="col-12 center-align">
                <h1 class="h1-responsive grey-text"><span class="blue-text">Choose</span> your Price</h1>
                <span class="grey-text">Low prices, Premium Service</span>
            </div>
            <div class="row input-group pad-top-25">
                <div class="card col-xs-10 col-sm-3 col-md-3 mx-auto no-radius pad-0 pad-top-25">
                    <div class="card-body center-align pad-0">
                        <h5 class="h5-responsive indigo-text">COURIER COLLECTION</h5>
                        <div class="bg-indigo white-text" style="padding-top:20px;padding-bottom:20px">
                            <span>FROM</span>
                            <h1 class="fa-3x">£3.29</h1>
                            <span>INCL VAT</span>
                        </div>
                        <div class="list-group pad-top-25 pad-bot-25 center-align grey-text">
                        <li class="list-group-item no-border">Between 0-2kg</li>
                        <li class="list-group-item no-border">£20 cover included</li>
                        <li class="list-group-item no-border">Door to door courier service</li>
                        </div>
                    </div>
                </div>
                
                <div class="card col-xs-10 col-sm-3 col-md-3 mx-auto no-radius pad-0 pad-top-25">
                    <div class="card-body center-align pad-0">
                        <h5 class="h5-responsive blue-text">PARCELSHOP DROP-OFF</h5>
                        <div class="bg-blue white-text" style="padding-top:20px;padding-bottom:20px">
                            <span>FROM</span>
                            <h1 class="fa-3x">£2.79</h1>
                            <span>INCL VAT</span>
                        </div>
                        <div class="list-group pad-top-25 pad-bot-25 center-align grey-text">
                        <li class="list-group-item no-border">Between 0-2kg</li>
                        <li class="list-group-item no-border">£20 cover included</li>
                        <li class="list-group-item no-border">Over 4,500 nationwide</li>
                        </div>
                    </div>
                </div>
                
                <div class="card col-xs-10 col-sm-3 col-md-3 mx-auto no-radius pad-0 pad-top-25">
                    <div class="card-body center-align pad-0">
                        <h5 class="h5-responsive black-text">INTERNATIONAL</h5>
                        <div class="rgba-black-strong white-text" style="padding-top:20px;padding-bottom:20px">
                            <span>FROM</span>
                            <h1 class="fa-3x">£9.12</h1>
                            <span>INCL VAT</span>
                        </div>
                        <div class="list-group pad-top-25 pad-bot-25 center-align grey-text">
                        <li class="list-group-item no-border">Between 0-2kg</li>
                        <li class="list-group-item no-border">Worldwide Delivery Service</li>
                        </div>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        </section>
        <section class="row flex-center bg-grey-light pad-top-25 pad-bot-25 divider">
            <div class="col-12 center-align" style="min-height:250px;background-image:url('assets/img/map.png');background-position:center;background-attacment:absolute;background-repeat:no-repeat">
                <h3 class="h3-responsive">Sending Parcels to friends &amp; family, and also business associates is so easy</h3>
                <span class="fa fa-map-marker fa-3x blue-text" style="margin-top:40px"></span>
                <span class="fa fa-map-marker fa-2x blue-text" style="margin-top:40px;margin-left:100px"></span>
            </div> 
            <div class="col-xs-10 col-sm-6 mx-auto center-align" style="top:-50px;position:relative">
                <h1 class="h1-responsive">Track your Parcel</h1>
                <form class="tracking-form inline-form">
                    <div class="md-form input-group">
                        <input type="text" class="form-control" id="track-id" placeholder="Tracking ID" required>
                        <button type="submit" class="btn btn-blue btn-lg no-radius no-shadow"><span class="fa fa-angle-right white-text"></span></button>
                    </div>
                </form>
            </div>
        </section>
        <footer class="row pad-top-25 pad-bot-25">
            <div class="col-12 center-align">
                <small class="grey-text ">Copyright &copy; Phoenix Express Courier Limited</small>
            </div>
        </footer>
    </div>
</body>
    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="assets/js/vanilla.js"></script>
</html>