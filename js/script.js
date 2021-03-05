$(document).ready(function(){
        var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);
        var data = JSON.parse(e.data); // convert the data javascript object
        let row = '';
        let bg_class = '';
        var msg_style = 'word-break:break-all;font-size:14px;padding:2px 2px;';
        // check if the client is also the receiver
        if (data.from == "me") {
            bg_class = 'text-dark alert-light';
            row ='row justify-content-end';
        }
        else{
            row = 'row justify-content-start';
            bg_class = 'text-dark alert-success';
        }
        // set up the bootstrap and html
        var html_data = "<div class='"+row+"'><div class='my-1 col-sm-10'><div style='"+msg_style+"' class=' shadow-sm alert "+bg_class+
        "'><b>"+data.from+' . '+"</b>"+data.msg+"<br/><div class='text-right'><small><i>"+data.time+"</i></small></div></div></div></div>";
        $('#message_area').append(html_data);
        $('#message').val("");

    };

    $('#chat-form').parsley(); // parsley validation error
    $('#message_area').scrollTop($('#message_area')[0].scrollHeight);
    $('#chat-form').on('submit',function(event){
        event.preventDefault();
        // if the parsley validation is valid
        if($('#chat-form').parsley().isValid()){
            // get user id
            var user_id = $('#user_id').val();
            // get the message value
            var message = $('#message').val();
            // get the username
            var username = $('#username').val();
            // pass the data to an object
            var data = {
                userId:user_id,
                msg:message,
                name:username
            };
            conn.send(JSON.stringify(data)); // send the data amd convert in to json string
            $('#message_area').scrollTop($('#message_area')[0].scrollHeight);
        }
    })
    // status section
    $('#update').on('click',function(event){
        event.preventDefault();
        var status = $('#status').html();
        var user_id = $('#user_id').val();
        $.ajax({
            url:'../controllers/save_status.php',
            data:{'status':status,'user_id':user_id},
            type:'POST',
            function(data){
                console.log(data);
            }
        })

        $(function(){
            // setInterval(function(){
                $.ajax({
                    url:'../controllers/show_status.php',
                    success:function(res){
                        $('#status').html(res);
                    }
                })
            // })
        })
    })
    
})