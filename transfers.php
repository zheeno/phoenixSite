<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Phoenix Express - Transfers</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../assets/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="../../assets/css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->
        <link href="../../assets/css/style.css" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/phoenixExpress.png" />
        </head>
    <body style="overflow-x:hidden">
    <?php
    require_once "capDB.php";
    //verify token
    if(isset($_REQUEST['verifyToken'] )|| isset($_REQUEST['token'])){
        $token = $_REQUEST['token'];
        $user_id = $_REQUEST['id'];
        $query = "SELECT * FROM accounts WHERE id = '$user_id' AND token = '$token' ";
        $exec = mysqli_query($con, $query);
        if(mysqli_num_rows($exec) == 0 || !isset($_COOKIE['acc_id'])){ ?>
            <script>
                window.location = "../../";
            </script>
        <?php
        }else{ 
            //get account data
            $acc = mysqli_fetch_assoc($exec);
            echo "<input type='hidden' id='global_usr_acc_id' value='".$user_id."' >";
            ?>
    <header class="container-fluid bg-white">
            <nav class="navbar-expand-lg bg-white">
                <div class="row bg-white">
                    <div class="col-xs-12 col-sm-12 col-md-3 mx-auto center-align">
                            <a class="navbar-brand" href="./">
                                <img src="../../assets/img/phoenixExpress.png" style="width:130px">
                            </a>
                        </div>
                    </div>
            </nav>
            <nav class="navbar navbar-expand-md bg-deep-blue-grey">
            <!-- Collapse button -->
            <button class="navbar-toggler btn-outline-grey" type="button" onClick='$("#navbarSupportedContent").collapse("toggle")' aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars white-text"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto navbar-list">
                <li class="nav-item dropdown">    
                <a href="../../account/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
                My Account
                </a></li>
                <li class="nav-item dropdown">
                <a href="../../transfers/<?php echo $user_id ?>/<?php echo $token ?>" class="disabled nav-link white-text capitalize transparent no-shadow">
                    Transfers
                </a></li>
                <li class="nav-item dropdown">
                <a href="../../history/<?php echo $user_id ?>/<?php echo $token ?>" class="nav-link white-text capitalize transparent no-shadow">
                    Account History
                </a></li>
                <li class="nav-item dropdown">
                <a class="sign-out-btn nav-link white-text capitalize transparent no-shadow">
                    Sign Out
                </a></li>
            </ul>
        </div>
    </nav>

        </header>
    <section class="container bg-white pad-top-50 pad-bot-100">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 inherit-h">
                <h2 class="black-text h2-responsive">Transfers</h2>
            </div>
            <div class="card col-xs-10 col-sm-5 col-md-5 mx-auto">
                <div class="card-body">
                    <h4 class="gray-text h4-responsive bold">Select Account</h4>
                    <p class="gray-text">Select an account from the dropdown below to carry out a transaction</p>
                    <select class="account-selector form-control">
                        <option>My Savings Account - <?php echo $acc['acc_no'] ?></option>
                    </select>
                </div>
            </div>
            <div class="card col-xs-10 col-sm-6 col-md-6 mx-auto no-shadow">
                <div class="card-body">
                    <h3 class="deep-blue-text h3-responsive bold">Select Destination</h3>
                    <a id="dest-1" class="dest-btn disabled btn btn-block btn-outline-black capitalize left-align deep-blue-text deep-blue-border">One of your Phoenix Express Accounts</a>
                    <a id="dest-2" class="dest-btn btn btn-block btn-outline-black capitalize left-align deep-blue-text deep-blue-border">Other Phoenix Express accounts</a>
                    <a id="dest-3" class="dest-btn btn btn-block btn-outline-black capitalize left-align deep-blue-text deep-blue-border">Other banks</a>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 card pad-0" style="margin-top:50px;overflow-x:hidden">
                <div class="card-header bg-light-blue-grey">
                    <b class="white-text">Wire Console</b>
                </div>
                <div id="dest-1-console" class="card-body collapse show">
                    <h2 class="h2-responsive">Wire funds to one of your Phoenix Express accounts</h2>
                    <div class="alert alert-info center-align">
                        <span class="fa fa-info-o fa-4x"></span>
                        <p class="h5-responsive">Sorry you have just one existing account with Phoenix Express at the moment</p>
                    </div>
                </div>
                <div id="dest-2-console" class="card-body collapse hide">
                    <h2 class="h2-responsive">Wire funds to another Phoenix Express account</h2>
                    <div id="lev-1" class="collapse show">
                    <?php if($acc['wire_permission'] == 700 || $acc['wire_permission'] == 701){ ?>
                        <form id="wire-form-2" class="container pad-top-50">
                            <div class="row">
                                <div class="md-form col-xs-12 col-sm-5 mx-auto center-align">
                                    <label>Account No.</label>
                                    <input id="acc_no" type="number" class="form-control" required>
                                </div>
                                <div class="md-form col-xs-12 col-sm-12 mx-auto center-align">
                                    <span id="info"></span><br>
                                    <button type="button" class="btn btn-outline-black deep-blue-border deep-blue-text btn-lg capitalize" data-toggle="modal" data-target="#beneficiaries">Select Beneficiary</button>
                                    <button type="submit" class="submit-btn btn bg-deep-blue-grey white-text btn-lg capitalize">Confirm Account</button>
                                </div>
                            </div>
                        </form>
                    <?php }else{
                            //error message
                            echo "<div class='alert alert-info center-align'>
                            Sorry, you can not perform this transaction at the moment. We are yet to receive some of the requested documents to activate this operation for this account.
                            </div>";
                    } ?>
                    </div>
                    <div id="lev-2" class="collapse hide">
                        <!-- transfer confirmation page is loaded asynchrounsly -->
                    </div>
                </div>
                <div id="dest-3-console" class="card-body collapse hide">
                <h2 class="h2-responsive">Wire funds to other bank accounts</h2>
                <div id="lev-1" class="collapse show">
                    <?php if($acc['wire_permission'] == 700 || $acc['wire_permission'] == 702){ ?>
                    <form id="confirm-wire-form-2" class="container pad-top-50">
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="active">Bank</label>
                                <input type="text" id="bank_name" class="form-control" required>
                            </div> 
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="active">Account No./IBAN</label>
                                <input type="hidden" id="acc_id" value="0" required>
                                <input type="text" id="acc_no" class="form-control" required>
                            </div>     
                        </div>
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="active">Account Holder's Name</label>
                                <input type="text" id="acc_name" class="form-control" required>
                            </div> 
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label>Amount ($)</label>
                                <input type="text" id="wire_amt" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label>SWIFTBIC</label>
                                <input type="text" id="swiftbic" class="form-control" required>
                            </div>
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label>Transaction Notes</label>
                                <textarea type="text" id="transact_notes" class="md-textarea" required></textarea>
                            </div>      
                        </div>
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-10 mx-auto center-align">
                            <button type="button" class="btn btn-outline-black deep-blue-border deep-blue-text btn-lg capitalize" data-toggle="modal" data-target="#other_beneficiaries">Select Beneficiary</button>
                            <button type="submit" class="wire-btn submit-btn btn bg-light-blue-grey btn-lg capitalize">Transfer Funds</button>
                            </div>
                        </div>
                    </form>
                <?php } 
                    else{
                        //error message
                        echo "<div class='alert alert-info center-align'>
                        Sorry, you can not perform this transaction at the moment. We are yet to receive some of the requested documents to activate this operation for this account.
                        </div>";
                    }
                ?>
                </div>
                <div id="lev-2" class="collapse hide">
                    <!-- transfer confirmation page is loaded asynchrounsly -->
                </div>
            </div>
            <div id="transferProgress"></div>
        </div>        </div><!--/row -->
    </section>
    <div class="modal fade" id="beneficiaries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="container modal-content pad-top-50 pad-bot-100">
                <h3 class="h3-responsive">Beneficiaries</h3>
                    <?php 
                        $que = "SELECT * FROM beneficiaries WHERE user_id = $user_id  ORDER BY acc_name ASC ";
                        $exe = mysqli_query($con, $que);
                        if(mysqli_num_rows($exe) == 0){
                            echo "<div class='alert alert-info center-align'>
                                        <span class='fa fa-info-o fa-3x'></span>
                                        <p>You have no beneficiaries associated with this account</p>
                                  </div>";
                        }else{
                            while($ben = mysqli_fetch_assoc($exe)){ 
                                ?>
                                <a onClick=wireTo("<?php echo $ben['acc_no'] ?>");$('#beneficiaries').modal('hide') class="btn btn-block transparent deep-blue-border capitalize align-left" style="min-height:50px">
                                    <span class="h5-responsive deep-blue-text" style="left:20px;position:absolute"><?php echo $ben['acc_name'] ?></span>
                                </a>
                        <?php
                            }
                        }
                    ?>
            </div>
        </div>
    </div>
    <?php include 'signoutModal.php' ?>
    <footer class="container-fluid gray-text bg-grey-light pad-bot-100" style="padding-top:10px">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 mx-auto">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 mx-auto right-aign">
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">PRODUCTS</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">ABOUT US</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius"><span class="black-text bold">CAREERS</span></a>
                    <a class="btn btn-sm transparent no-shadow no-radius" style="border-right:1px solid #DDD"><span class="black-text bold">LEGAL</span></a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 mx-auto">
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Contact Us</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Privacy</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Security</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Terms &amp; Conditions</span></a>
                    <a href="./" class="btn btn-sm transparent no-shadow no-radius"><span class="black-text">Accessibility</span></a>
                    <br>
                    <img src="../../assets/img/social_btns.png" style="right:30px;position:absolute">
                </div>
            </div>
        </div>
    </div>
    <div class="row pad-top-50">
        <div class="col-xs-6 col-sm-6 center-align">
            <img src="../../assets/img/phoenixExpress.png" style="width:100px;margin-right:20px">
            <small class="gray-text" style="font-size:12px">&copy;<?php echo Date("Y") ?> Phoenix Express</small>
        </div>
        <div class="col-xs-6 col-sm-6 right-align" style="padding-right:50px">
        <a style="text-decoration:bold;font-size:13.5px;font-weight:600;font-stretch:condensed;color:#333;text-shadow:1px 0px 0px #333">
            MEMBER FDIC
        </a>
        <br>
        <small class="gray-text pull-right">Equal Housing Lender&nbsp;<img src="../../assets/img/house.png"></small>
        </div>
    </div>
</footer>
</body>
    <?php }
    }
    ?>
<script type="text/javascript" src="../../assets/js/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="../../assets/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="../../assets/js/mdb.min.js"></script>
<script type="text/javascript" src="../../assets/js/vanilla.js"></script>
<script type="text/javascript" src="../../assets/js/timeago.js"></script>
</body>
</html>
</html>