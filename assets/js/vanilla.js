//login form submitted
$(".login-form").on("submit", function(evt){
    evt.preventDefault();
    var id = escape($(".login-form #login-id").val());
    var pwd = escape($(".login-form #login-pwd").val());
    if(id.length > 0 && pwd.length > 0)
    $(".login-form #login-btn").html("Please Wait... <span class='fa fa-spinner fa-spin'></span>");
    var url = "login_verification.php?id="+id+"&pwd="+pwd+"&login";    
    $(".login-form #login-info").empty().load(url, function(){
        var response = Number($("#login-info #login-res").val());
        var token = $(".login-form #login-info #login-token").val();
        var user_id = $(".login-form #login-info #login-id").val();
        if(response == 1){
           window.location = "account/"+user_id+"/"+token;
        }else{
            $(".login-form #login-btn").text("Sign In");
        }
    })
});
//sign out
$(".sign-out-btn").on("click", function(){
    $("#signoutModal").modal("show");
})
$(".sign-out-now-btn").on("click", function(){
    my_id = $("#global_usr_acc_id").val();
    window.location = "../../signout/"+my_id;
})
//bank statement request
$("#statement-form").on("submit", function(evt){
    evt.preventDefault();
    var date_1 = escape($("#statement-form .date-selector-1").val());
    var date_2 = escape($("#statement-form .date-selector-2").val());
    if(date_1.length > 0 && date_2.length > 0)
    var url = "../../bankStatement.php";
    var bars = progress("#transferProgress");
    $("#statement-form #statement-info").empty().load(url, function(){
        setTimeout(function(){
            $("#statement-form #statement-info").html("<span class='green-text'>Your Bank Statement has been sent to your E-mail Address</span>");
            clearTimeout(bars);
            $("#transferProgress").empty();
        },5000)
    })
    
});
//select transfer destination
var cur_dest = "dest-1";
$(".dest-btn").on("click", function(){
    var new_dest = this.id;
    if(cur_dest != new_dest)
    $("#"+cur_dest).removeClass("disabled");
    $("#"+new_dest).addClass("disabled");
    $("#"+cur_dest+"-console").collapse("hide");
    $("#"+new_dest+"-console").collapse("show");
    cur_dest = new_dest;
    cancelSmBankTransact();
});
//dropdow list selection
$("#accList a").on("click", function(evt){
    $("#list-selector").text($("#"+this.id).text());
    $('#accList').collapse('hide');
    $(".wire-btn").removeClass("disabled");
})
globalBars = null;
//wire to capital one bank
$("#wire-form-2").on("submit", function(evt){
    evt.preventDefault();
    var acc_no = escape($("#wire-form-2 #acc_no").val());
    wireTo(acc_no);
    $(".wire-btn").removeClass("disabled");
})
//wire to other banks
$(".dest-btn #dest-3").on("click", function(){
    wireTo("OVERWRITE");
    $(".wire-btn").removeClass("disabled");
})
function wireTo(acc_no){
    if(acc_no.length >= 9){
        $("#wire-form-2 #info").empty();
        globalBars = progress("#transferProgress");
        my_id = $("#global_usr_acc_id").val();
        var url ="../../bankStatement.php?acc_no="+acc_no+"&my_id="+my_id+"&verifySameBank";
        $("#wire-form-2 .submit-btn").addClass("disabled");
        $('#dest-2-console #lev-2').load(url, function(){
            $("#dest-2-console .submit-btn").removeClass("disabled");
            clearTimeout(globalBars);
            $("#transferProgress").empty();
            //handle response from server
            var accStats = Number($('#dest-2-console #lev-2 #accStatus').val());
            if(accStats == 0){
                $("#wire-form-2 #info").html("<div class='red-text center-align'>Invalid account. Please try again</div>");
            }else{
                //display response
                var acc_name = $('#dest-2-console #lev-2 #accName').val();
                var acc_id = $('#dest-2-console #lev-2 #accId').val();
                $("#dest-2-console #lev-2").empty().html(`
                    <form id="confirm-wire-form-1" class="container pad-top-50" onSubmit="wireConfirmation(event)">
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="active">Account No.</label>
                                <input type="hidden" id="acc_id" value="`+acc_id+`" required>
                                <input type="text" id="acc_no" class="disabled form-control" value="`+acc_no+`" required>
                            </div>
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label class="active">Account Name</label>
                                <input type="text" id="acc_name" class="disabled form-control" value="`+acc_name+`" required>
                            </div>      
                        </div>
                        <div class="row">
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label>Amount ($)</label>
                                <input type="text" id="wire_amt" class="form-control" required>
                            </div>
                            <div class="md-form col-xs-12 col-sm-5 mx-auto">
                                <label>Transaction Notes</label>
                                <input type="text" id="transact_notes" class="form-control" required>
                            </div>      
                        </div>
                        <div class="row">
                        <div class="md-form col-xs-12 col-sm-10 mx-auto center-align">
                                <button type="button" onClick="cancelSmBankTransact();" class="wire-btn cancel-btn btn btn-outline-black deep-blue-border deep-blue-text btn-lg capitalize">Cancel Transaction</button>
                                <button type="submit" class="wire-btn submit-btn btn bg-light-blue-grey btn-lg capitalize">Transfer Funds</button>
                            </div>
                        </div>
                    </form>
                `)
                $("#dest-2-console #lev-1").collapse("hide");
                $("#dest-2-console #lev-2").collapse("show");
            }
        });
    }
}

$("#confirm-wire-form-2").on("submit",function(evt){
    evt.preventDefault();
    amt = $("#confirm-wire-form-2 #wire_amt").val();
    my_id = $("#global_usr_acc_id").val();
    dest_id = $("#confirm-wire-form-2 #acc_id").val();
    rec_acc_name = escape($("#confirm-wire-form-2 #acc_name").val());
    bank_name = escape($("#confirm-wire-form-2 #bank_name").val());
    acc_no = escape($("#confirm-wire-form-2 #acc_no").val());
    notes = escape($("#confirm-wire-form-2 #transact_notes").val());
    swiftbic = escape($("#confirm-wire-form-2 #swiftbic").val());
    if(Number(amt) > 0){
        $(".wire-btn").addClass("disabled");
        globalBars = progress("#transferProgress");
        var url = "../../bankStatement.php?from="+my_id+"&bank_name="+bank_name+"&acc_name="+rec_acc_name+"&acc_no="+acc_no+"&to="+dest_id+"&amt="+amt+"&notes="+notes+"&swiftbic="+swiftbic+"&checkWireToOtherBanks";
        setTimeout(function(){
            $("#dest-3-console #lev-2").load(url, function(){
                //console.log($("#dest-3-console #lev-2").html())
                $("#dest-3-console #lev-1").collapse("hide");
                $("#dest-3-console #lev-2").collapse("show");
                $(".wire-btn").removeClass("disabled");
                clearTimeout(globalBars);
                $("#transferProgress").empty();
                })
        },1000);
    }
})
function wireConfirmation(evt){
    evt.preventDefault();
    amt = $("#confirm-wire-form-1 #wire_amt").val();
    my_id = $("#global_usr_acc_id").val();
    dest_id = $("#confirm-wire-form-1 #acc_id").val();
    notes = escape($("#confirm-wire-form-1 #transact_notes").val());
    if(Number(amt) > 0){
        $(".wire-btn").addClass("disabled");
        globalBars = progress("#transferProgress");
        var url = "../../bankStatement.php?from="+my_id+"&to="+dest_id+"&amt="+amt+"&notes="+notes+"&checkWire";
        setTimeout(function(){
            $("#dest-2-console #lev-2").load(url, function(){
                $(".wire-btn").removeClass("disabled");
                clearTimeout(globalBars);
                $("#transferProgress").empty();
                })
        },5000);
    }
}
function confirmWire(v){
    if(Number(amt) > 0){
        var ele = "#dest-2-console #lev-2";
        var url = "../../bankStatement.php?from="+my_id+"&to="+dest_id+"&amt="+amt+"&notes="+notes+"&wireNow";
        if(v == 1){
            ele = "#dest-3-console #lev-2";
            url = "../../bankStatement.php?from="+my_id+"&to=0&acc_name="+rec_acc_name+"&acc_no="+acc_no+"&swiftbic="+swiftbic+"&bank_name="+bank_name+"&amt="+amt+"&notes="+notes+"&wireNowOtherBanks";
        }
        $(".wire-btn").addClass("disabled");
        globalBars = progress("#transferProgress");
        setTimeout(function(){
            $(ele).load(url, function(){
                clearTimeout(globalBars);
                $("#transferProgress").empty();
                })
        },5000);
    }
}
function cancelSmBankTransact(){
    $("#dest-2-console #lev-1").collapse("show");
    $("#dest-2-console #lev-2").collapse("hide").empty();
    clearTimeout(globalBars);
    $("#transferProgress").empty();
}
function cancelSmBankTransact_2(){
    $(".wire-btn").removeClass("disabled");
    $("#dest-3-console #lev-1 .form-control").val("");
    $("#dest-3-console #lev-1 .md-textarea").val("");
    $("#dest-3-console #lev-1").collapse("show");
    $("#dest-3-console #lev-2").collapse("hide").empty();
    clearTimeout(globalBars);
    $("#transferProgress").empty();
}
function progress(d){
    var bars = `
        <div id="bars" class="input-group animated slideInRight">
            <div class="bg-yellow" style="height:5px;width:30%"></div>
            <div class="bg-deep-blue-grey" style="height:5px;width:60%"></div>
            <div class="bg-red" style="height:5px;width:10%"></div>
        </div>`;
        $(d).html(bars);
        var barLoading = setInterval(function(){
            $("#bars").removeClass("slideOutLeft").addClass("animated slideInRight");
            setTimeout(function(){
                $("#bars").removeClass("animated slideInRight");
                setTimeout(function(){
                    $("#bars").addClass("animated slideOutLeft");
                },80)
            },300);
        },1200);
        return barLoading;
}


$(".dropdown-toggle").hover(function() {
        //handles mouseEnter
        var target = $(this).attr("data-tag");
        $(target).addClass("show");
        setTimeout(function(){
            $(target+" .collapse").collapse("show");
        },80);
    },
    function(){
        //handles mouseLeave
        var target = $(this).attr("data-tag");
        timeout = setTimeout(function(){
            $(target).removeClass("show");
            $(target+" .collapse").collapse("hide");
            clearTimeout(timeout);
        },200);
    }   
)
$(".dropdown-menu").hover(function() {
        //handles mouseEnter
        clearTimeout(timeout);
        $(this).addClass("show");
        $("#"+this.id+" .collapse").collapse("show");
    },
    function(){
    //handles mouseLeave
    $(this).removeClass("show");
    $("#"+this.id+" .collapse").collapse("hide");
})
//////////////-------ADMIN LOGIC-------///////////////
var spinner = "<div style='top:0px;left:0px;z-index:10;position:fixed' class='w-100 h-100 flex-center'><span class='fa fa-spinner fa-spin fa-4x deep-blue-text'></span></div>";
//show all accounts
$("#admin-console-screen").ready(function(){
    showAllAccounts();
});
$('.show-acc-btn').on('click', showAllAccounts);
function showAllAccounts(){
    var url = "./admin_logic.php?showAllAccounts";
    $("#admin-console-screen").append(spinner).load(url, function(){

    })
}
$('.add-acc-btn').on('click', function(){
    var url = "./admin_logic.php?addAccount";
    $(".modal-body").append(spinner).load(url, function(){
        $("#infoModal").modal("show");
    })
})












   