function getdata(){
    var email = Utils.get_from_localstorage('user').email;
    $.ajax({
        url: "rest/v1/getdata",
        method: "POST",
        dataType: "json",
        data:{
            email: email
        },
        success: function(data) {
            $('#profile-registration').html(data.registration);


        }
    });
}

function getactivities(){
    var email = Utils.get_from_localstorage('user').email;
    $.ajax({
        url: "rest/v1/getactivities",
        method: "POST",
        dataType: "json",
        data: {
            email: email
        },
        success: function(activities) {
            var txt = "";
            for (x in activities) {
                
                txt += '<li><div class="col1" style="width:auto"><div class="cont"><div class="cont-col1">';
                    if(activities[x].action=='0'){
                        txt += '<div class="label label-sm label-success" style="background-color: lawngreen"><i class="fa fa-hand-o-right" style="color: black;"></i></div></div><div class="cont-col2"><div class="desc"> You have entered the gate'
                    }else{
                        txt += '<div class="label label-sm label-success" style="background-color: red"><i class="fa fa-hand-o-left" style="color: white;"></i></div></div><div class="cont-col2"><div class="desc"> You have exited the gate'
                    }
                    txt += '</div></div></div></div><div class="col2" style="float: right;width:  auto;"><div class="date"> '+activities[x].datetime+' </div></div></li>';
                
            }
            $('.feeds').html(txt);
        }});
    }
    function getactivitiesall(){
        var email = Utils.get_from_localstorage('user').email;
        $.ajax({
            url: "rest/v1/getactivitiesall",
            method: "POST",
            dataType: "json",
            data: {
                email: email
            },
            success: function(activities) {
                var txt = "";
                for (x in activities) {
                    
                    txt += '<li><div class="col1" style="width:auto"><div class="cont"><div class="cont-col1">';
                        if(activities[x].action=='0'){
                            txt += '<div class="label label-sm label-success" style="background-color: lawngreen"><i class="fa fa-hand-o-right" style="color: black;"></i></div></div><div class="cont-col2"><div class="desc"> You have entered the gate'
                        }else{
                            txt += '<div class="label label-sm label-success" style="background-color: red"><i class="fa fa-hand-o-left" style="color: white;"></i></div></div><div class="cont-col2"><div class="desc"> You have exited the gate'
                        }
                        txt += '</div></div></div></div><div class="col2" style="float: right;width:  auto;"><div class="date"> '+activities[x].datetime+' </div></div></li>';
                    
                }
                $('.allfeeds').html(txt);
            }});
        }
    
function getactivitiesfull(){
    var email = Utils.get_from_localstorage('user').email;
    $.ajax({
        url: "rest/v1/getactivitiesfull",
        method: "POST",
        dataType: "json",
        data: {
            email: email
        },
        success: function(activities) {
            var txt = "";
            for (x in activities) {
                
                txt += activities[x].datetime;
                
            }
            $('#dejt').html(txt);
        }});
    }