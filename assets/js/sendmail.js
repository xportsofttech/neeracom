$(document).ready(function () {
    $("#sendMailBtn").click(function () {
         var recaptcha_demo = grecaptcha.getResponse(widgetId3);
         if (recaptcha_demo == '') 
         {
                     alertify.error("Please check the the captcha form");
                     $("#sendMailBtn").prop('disabled', false);
                     $("#sendMailBtn").html("Send Email");
          }
          else
          {
        $("#sendMailBtn").prop('disabled', true);
        $("#sendMailBtn").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;&nbsp; Sending Email...");

        var to = $("#to").val();
        var cc = $("#cc").val();
        var bcc = $("#bcc").val();
        var sub = $("#sub").val();
        var msg = $("#msg").val();
       
        var myData = {
            "to": to,
            "cc": cc,
            "bcc": bcc,
            "sub": sub,
            "msg": msg,
//            "recaptcha-demo": recaptcha_demo,
        };
        $.ajax({
            data: myData,
            type: "post",
            url: "insertmail.php",
             success: function (data) {         
                $("#to").val("");
                $("#cc").val("");
                $("#bcc").val("");
                $("#sendMailBtn").prop('disabled', false);
                $("#sendMailBtn").html("Send Email");
                $('#sendMail').modal('hide');
                $('#myModal').modal('show');
            }
        });
    }
});
});