$(document).ready(function(){
        var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);
        var data = JSON.parse(e.data); // convert the data javascript object
        var row = '';
        var bg_class = '';
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
        var html_data = "<div class='"+row+"'><div class='my-1 col-sm-10'><div style='"+msg_style+"' class=' shadow-sm alert-light "+bg_class+
        "'><b>"+data.from+' . '+"</b>"+data.msg+"<br/><div class='text-right'><small><i>"+data.time+"</i></small></div></div></div></div>";
        $('#message_area').append(html_data);
        $('#message').val("");

    };

    $('#chat-form').parsley(); // parsley validation error
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
        }
    })
})