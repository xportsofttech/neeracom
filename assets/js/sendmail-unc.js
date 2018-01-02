$(document).ready(function () {
    $("#sendMailBtn").click(function () {
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
                $.toast({
                    heading: 'E-Mail Status',
                    showHideTransition: 'fade',
                    loader: true,
                    loaderBg: 'black',
                    hideAfter: 10000,
                    bgColor: 'green',
                    textColor: '#eee',
                    position: 'mid-center',
                    text: data
                });
            }
        });
    });
});