
$(document).ready(function() {
    $('#loader').css('display', 'none');
    function calRoi(){
        $('#loader').css('display', 'block');
        var amount = $("#amount").val();
        $('#month_roi_j').val(amount / 10);
        $.ajax({
            type: 'get',
            url: 'calRoi/'+amount,
            //data:{_token:_token, amount:amount},
            //dataType: 'json',
            success:function(data) {
                if ($.isEmptyObject(data.error)){
                    $('#month_pcent').val(data.monthly_pcent);
                    $('#month_roi').val(data.monthly_roi);
                    $('#amount_words').val(data.amountwords);
                }else{
                    $('amount_error').html(data.error);
                }
            }
        });

        $('#loader').css('display', 'none');
        //alert("Calculate amount");
    }
    calRoi();

    $('#amount').keyup(function(){
        //if($('#month_roi').val() === ""){
            $('#loader').css('display', 'block');
        //}
        calRoi();
        $('#loader').html("&nbsp;");
        //if($('#month_roi').val() != ""){
            $('#loader').css('display', 'none');
        //}
        $('#tSubBtn').removeAttr("disabled");
    });

    $('#fname').focusout(function(){
        var name = $('#fname').val();
        $('#acct_name').val(name);
        nameArr = name.split(' ');
        if( typeof nameArr[1] === 'undefined' || nameArr[1] == "" ){
            alert("Pls enter full name as it appears on your bank account");
            $('#fname').val("");
            $('#acct_name').val("");
        }
    });
    $('#cname').keyup(function(){
        var name = $('#cname').val();
        $('#acct_name').val(name);
    });
});
