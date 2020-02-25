<?php
    require_once "./phoenixCourierDB.php";
    if(isset($_REQUEST['allParcels'])){
        if(!isset($_REQUEST['keyword'])){
            $query = mysqli_query($con, "SELECT * FROM parcels ORDER BY id DESC ");
        }else{
            $keyword = $_REQUEST['keyword'];
            $query = mysqli_query($con, "SELECT * FROM parcels WHERE tracking_id LIKE '%$keyword%' OR item_name LIKE '%$keyword%' ORDER BY id DESC ");
        }
        if(!$query){
            echo "<div class='alert alert-info h5-responsive center-align'>There are no parcels</div>";
        }else{
            echo "<table class='table table-striped'>
                <thead><tr>
                    <th>Tracking ID #</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr></thead>
                <tbody>
            ";
            while($row = mysqli_fetch_assoc($query)){
                echo "<tr id='parcel-row-".$row['id']."'>
                    <td>".$row['tracking_id']."</td>
                    <td>".nl2br($row['item_name'])."</td>
                    <td>".nl2br($row['item_desc'])."</td>
                    <td>".$row['transit_status']."</td>
                    <td class='dropup input-group'>
                    <a class='btn btn-sm btn-blue fa fa-ellipsis-v' data-toggle='dropdown'></a>
                    <div class='dropdown-menu pad-0'>
                        <ul class='list-group'>
                        <li class='list-group-item no-border manage-parcel' data-id='".$row['id']."'><a><span class='fa fa-gears'></span>&nbsp;Manage</a></li>
                        <li class='list-group-item no-border delete-parcel' data-id='".$row['id']."'><a><span class='fa fa-trash'></span>&nbsp;Delete Parcel</a></li>
                        </ul>
                    </div>
                    </td>
                    </tr>";
            }
            echo "</tbody>
                </table>";
        }
    }
    if(isset($_REQUEST['addParcel'])){
        $track_id = "WSD-".ceil(rand(1000,4000))."-".ceil(rand(4000,9000))."-".ceil(rand(10,90));
        echo "<h3 class='h3-responsive'>Send Parcel</h3>";
        echo "<form id='send-parcel-form' class='container pad-top-25'>
            <div class='row'>
                <div class='col-12 bg-grey-light bold grey-text'>Parcel's Information</div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Tracking ID</label>
                    <input type='hidden' id='id' class='form-control'>
                    <input type='text' id='track-id' value='".$track_id."' class='form-control disabled' disabled>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Item Name</label>
                    <textarea type='text' id='item-name' class='md-textarea'></textarea>
                </div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-11 mx-auto'>
                    <label class='active'>Item Description</label>
                    <textarea type='text' class='md-textarea' id='item-desc'></textarea>
                </div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Quantity</label>
                    <input type='number' id='no-items' class='form-control' value='1'>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Courier Charges</label>
                    <input type='text' id='courier-fee' class='form-control'>
                </div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-11 mx-auto'>
                    <label class='active'>Transit Status</label>
                    <select class='form-control no-border' style='border-bottom:1px solid #D7D7D7 !important;' id='transit-stat'>"; ?>
                        <option value='PENDING' >PENDING</option>
                        <option value='IN TRANSIT' >IN TRANSIT</option>
                        <option value='DELIVERED' >DELIVERED</option>
            <?php
            echo "</select>
                </div>
            </div>
            <div class='row'>
                <div class='col-12 bg-grey-light bold grey-text'>Sender's Information</div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Sender</label>
                    <input type='text' id='sender-name' class='form-control'>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Details</label>
                    <textarea type='text' id='sender-details' class='md-textarea'></textarea>
                </div>
            </div>
            <div class='row'>
                <div class='col-12 bg-grey-light bold grey-text'>Receiver's Information</div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Receiver</label>
                    <input type='text' id='receiver-name' class='form-control'>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Details</label>
                    <textarea type='text' id='receiver-details' class='md-textarea'></textarea>
                </div>
            </div>
            <div class='row'>
                <div class='col-12 bg-grey-light bold grey-text'>Routing Information</div>
            </div>
            <div class='row pad-top-25'>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Origin</label>
                    <input type='text' id='origin' class='form-control'>
                </div>
                <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                    <label class='active'>Destination</label>
                    <input type='text' id='destination' class='form-control'>
                </div>
                <div class='md-form col-12 mx-auto center-align'>
                    <span id='info'></span><br>
                    <button type='submit' class='btn btn-blue btn-lg'>Add Parcel</button>
                </div>
            </div>
        </form>";
    }

    if(isset($_REQUEST['manageParcel'])){
        echo "<h3 class='h3-responsive'>Manage Parcel</h3>";
        $id = $_REQUEST['id'];
        $sql = mysqli_query($con, "SELECT * FROM parcels WHERE id = $id ");
        if(mysqli_num_rows($sql) == 0){
            echo "<div class='alert alert-info center-align h5-responsive'><span class='fa fa-info-circle fa-4x'></span><br>We could not find the selected parcel.</div>";
        }
        $row = mysqli_fetch_assoc($sql);
        echo "<form id='manage-parcel-form' class='container pad-top-25'>
                <div class='row'>
                    <div class='col-12 bg-grey-light bold grey-text'>Parcel's Information</div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Tracking ID</label>
                        <input type='hidden' value='".$row['id']."' id='id' class='form-control'>
                        <input type='text' value='".$row['tracking_id']."' id='track-id' class='form-control'>
                    </div>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Item Name</label>
                        <textarea type='text' id='item-name' class='md-textarea'>".$row['item_name']."</textarea>
                    </div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-11 mx-auto'>
                        <label class='active'>Item Description</label>
                        <textarea type='text' class='md-textarea' id='item-desc'>".$row['item_desc']."</textarea>
                    </div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Quantity</label>
                        <input type='number' value='".$row['no_items']."' id='no-items' class='form-control'>
                    </div>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Courier Charges</label>
                        <input type='text' value='".$row['courier_fee']."' id='courier-fee' class='form-control'>
                    </div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-11 mx-auto'>
                        <label class='active'>Transit Status</label>
                        <select class='form-control no-border' style='border-bottom:1px solid #D7D7D7 !important;' id='transit-stat'>"; ?>
                            <option value='PENDING' <?php if($row['transit_status'] == "PENDING"){ echo "selected"; } ?> >PENDING</option>
                            <option value='IN TRANSIT' <?php if($row['transit_status'] == "IN TRANSIT"){ echo "selected"; } ?> >IN TRANSIT</option>
                            <option value='DELIVERED' <?php if($row['transit_status'] == "DELIVERED"){ echo "selected"; } ?> >DELIVERED</option>
                <?php
                echo "</select>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-12 bg-grey-light bold grey-text'>Sender's Information</div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Sender</label>
                        <input type='text' value='".$row['sender_name']."' id='sender-name' class='form-control'>
                    </div>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Details</label>
                        <textarea type='text' id='sender-details' class='md-textarea'>".$row['sender_details']."</textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-12 bg-grey-light bold grey-text'>Receiver's Information</div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Receiver</label>
                        <input type='text' value='".$row['receiver_name']."' id='receiver-name' class='form-control'>
                    </div>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Details</label>
                        <textarea type='text' id='receiver-details' class='md-textarea'>".$row['receiver_details']."</textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-12 bg-grey-light bold grey-text'>Routing Information</div>
                </div>
                <div class='row pad-top-25'>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Origin</label>
                        <input type='text' value='".$row['origin']."' id='origin' class='form-control'>
                    </div>
                    <div class='md-form col-xs-12 col-sm-5 mx-auto'>
                        <label class='active'>Destination</label>
                        <input type='text' value='".$row['destination']."' id='destination' class='form-control'>
                    </div>
                    <div class='md-form col-12 mx-auto center-align'>
                        <span id='info'></span><br>
                        <button type='submit' class='btn btn-blue btn-lg'>Save Changes</button>
                    </div>
                </div>
            </form>";
    }

    if(isset($_REQUEST['addNewParcel'])){
        $track_id = $_REQUEST["track_id"];
        $item_name = $_REQUEST["item_name"];
        $item_desc = $_REQUEST["item_desc"];
        $no_items = $_REQUEST["no_items"];
        $courier_fee = $_REQUEST["courier_fee"];
        $transit_stat = $_REQUEST["transit_stat"];
        $sender_name = $_REQUEST["sender_name"];
        $sender_details = $_REQUEST["sender_details"];
        $receiver_name = $_REQUEST["receiver_name"];
        $receiver_details = $_REQUEST["receiver_details"];
        $destination = $_REQUEST["destination"];
        $origin = $_REQUEST["origin"];
           
        if(mysqli_query($con, "INSERT INTO parcels 
        -- ('tracking_id', 'item_name', 'item_desc', 'no_items', 'transit_status', 'courier_fee', 'sender_name', 'sender_details', 'receiver_name', 'receiver_details', 'destination', 'origin')
        VALUES ('', '$track_id', '$item_name', '$item_desc', '$no_items', '$courier_fee', '$transit_stat', '$sender_name', '$sender_details', '$receiver_name', '$receiver_details', '$origin', '$destination')") or die(mysqli_error($con))){
            echo "<span class='green-text'>Parcel added successfully</span>";
        }else{
            echo "<span class='red-text'>Error encountered while adding parcel</span>";
        }
    }

    if(isset($_REQUEST['saveParcelChanges'])){
        $id = $_REQUEST["id"];
        $track_id = $_REQUEST["track_id"];
        $item_name = $_REQUEST["item_name"];
        $item_desc = $_REQUEST["item_desc"];
        $no_items = $_REQUEST["no_items"];
        $courier_fee = $_REQUEST["courier_fee"];
        $transit_stat = $_REQUEST["transit_stat"];
        $sender_name = $_REQUEST["sender_name"];
        $sender_details = $_REQUEST["sender_details"];
        $receiver_name = $_REQUEST["receiver_name"];
        $receiver_details = $_REQUEST["receiver_details"];
        $destination = $_REQUEST["destination"];
        $origin = $_REQUEST["origin"];
        
        if(mysqli_query($con, "UPDATE parcels SET tracking_id = '$track_id', item_name = '$item_name', item_desc = '$item_desc', no_items = '$no_items', courier_fee = '$courier_fee', transit_status = '$transit_stat', sender_name = '$sender_name', sender_details = '$sender_details', receiver_name = '$receiver_name', receiver_details = '$receiver_details', origin = '$origin', destination = '$destination' WHERE id = $id ")){
            echo "<span class='green-text'>Changes saved successfully</span>";
        }else{
            echo "<span class='red-text'>Error encountered while saving changes</span>";
        }
    }
    if(isset($_REQUEST['deleteParcel'])){
        $id = $_REQUEST['id'];
        mysqli_query($con, "DELETE FROM parcels WHERE id = $id ");
    }
?>
<script>
$(".list-group .manage-parcel").on("click", function(e){
    var id = $(this).attr("data-id");
    var url = host+"/parcel-manager.php?id="+id+"&manageParcel";
    $(".console-screen").append(spinner).load(url, function(){
        //...
    });
})


$('.list-group .delete-parcel').on('click',function(e){
    var id = $(this).attr("data-id");
    var url = host+"/parcel-manager.php?id="+id+"&deleteParcel";
    $("#parcel-row-"+id).append(spinner).load(url, function(){
        $("#parcel-row-"+id).remove();
    })
})

//function to send new parcel
$("#send-parcel-form").on("submit", function(e){
    e.preventDefault();
    var track_id = escape($("#send-parcel-form #track-id").val());
    var item_name = escape($("#send-parcel-form #item-name").val());
    var item_desc = escape($("#send-parcel-form #item-desc").val());
    var no_items = escape($("#send-parcel-form #no-items").val());
    var courier_fee = escape($("#send-parcel-form #courier-fee").val());
    var transit_stat = escape($("#send-parcel-form #transit-stat").val());
    var sender_name = escape($("#send-parcel-form #sender-name").val());
    var sender_details = escape($("#send-parcel-form #sender-details").val());
    var receiver_name = escape($("#send-parcel-form #receiver-name").val());
    var receiver_details = escape($("#send-parcel-form #receiver-details").val());
    var destination = escape($("#send-parcel-form #destination").val());
    var origin = escape($("#send-parcel-form #origin").val());
    
    var url = host+"/parcel-manager.php?track_id="+track_id+"&item_name="+item_name+"&item_desc="+item_desc+"&no_items="+no_items+"&courier_fee="+courier_fee+"&transit_stat="+transit_stat+"&sender_name="+sender_name+"&sender_details="+sender_details+"&receiver_name="+receiver_name+"&receiver_details="+receiver_details+"&destination="+destination+"&origin="+origin+"&addNewParcel";
    $("#send-parcel-form button").html("ADDING... <span class='fa fa-spinner fa-spin'></span>")
    $("#send-parcel-form #info").empty().load(url, function(){
        $("#send-parcel-form button").html("ADD PARCEL");
    })
})

//save edited parcel
$("#manage-parcel-form").on("submit", function(e){
    e.preventDefault();
    var id = escape($("#manage-parcel-form #id").val());
    var track_id = escape($("#manage-parcel-form #track-id").val());
    var item_name = escape($("#manage-parcel-form #item-name").val());
    var item_desc = escape($("#manage-parcel-form #item-desc").val());
    var no_items = escape($("#manage-parcel-form #no-items").val());
    var courier_fee = escape($("#manage-parcel-form #courier-fee").val());
    var transit_stat = escape($("#manage-parcel-form #transit-stat").val());
    var sender_name = escape($("#manage-parcel-form #sender-name").val());
    var sender_details = escape($("#manage-parcel-form #sender-details").val());
    var receiver_name = escape($("#manage-parcel-form #receiver-name").val());
    var receiver_details = escape($("#manage-parcel-form #receiver-details").val());
    var destination = escape($("#manage-parcel-form #destination").val());
    var origin = escape($("#manage-parcel-form #origin").val());
    
    var url = host+"/parcel-manager.php?id="+id+"&track_id="+track_id+"&item_name="+item_name+"&item_desc="+item_desc+"&no_items="+no_items+"&courier_fee="+courier_fee+"&transit_stat="+transit_stat+"&sender_name="+sender_name+"&sender_details="+sender_details+"&receiver_name="+receiver_name+"&receiver_details="+receiver_details+"&destination="+destination+"&origin="+origin+"&saveParcelChanges";
    $("#manage-parcel-form button").html("UPDATING... <span class='fa fa-spinner fa-spin'></span>")
    $("#manage-parcel-form #info").empty().load(url, function(){
        $("#manage-parcel-form button").html("SAVE CHANGES");
    })
})
</script>