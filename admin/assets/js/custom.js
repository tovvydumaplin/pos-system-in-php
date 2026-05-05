$(document).ready(function () {
    
    alertify.set('notifier','position', 'top-right');

    $(document).on('click', '.increment', function () {
        
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue)){
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    $(document).on('click', '.decrement', function () {
        
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue) && currentValue > 1){
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    function quantityIncDec(prodId, qty){

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productIncDec': true,
                'product_Id': prodId,
                'quantity': qty
            },
            success: function (response) {
                var res = JSON.parse(response);

                if(res.status == 200){
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                }else{
                    $('#productArea').load(' #productContent');
                    alertify.error(res.message);
                }
            }
        });
    }

    
    // proceed to place order button click
    $(document).on('click', '.proceedToPlace', function (e) {
        e.preventDefault();
        var cphone = $('#cphone').val().trim();
        var payment_mode = $('#payment_mode').val();

        if(payment_mode == ''){
            swal("Select Payment Mode","Select your payment mode","warning");
            return false;
        }

        if(cphone == '' || !$.isNumeric(cphone)){
            swal("Enter Phone Number","Enter Valid Phone Number","warning");
            return false;
        }

        var btn = $(this);
        btn.prop('disabled', true).text('processing...');

        var data = {
            'proceedToPlaceBtn': true,
            'cphone': cphone,
            'payment_mode': payment_mode,
        };

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            dataType: "json",
            success: function (res) {

                if(res.status == 200){
                    // window.location.href = "order-summary.php";
                    window.location.replace("order-summary.php");

                }else if(res.status == 404){

                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: {
                                text: "Add Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"
                        }
                    })
                    .then((value) => {
                        if(value === "catch"){
                            $('#cphone').val(cphone);
                            $('#addCustomerModal').modal('show');
                        }
                    });

                }else{
                    swal(res.message, res.message, res.status_type);
                }
            },
            complete: function(){
                btn.prop('disabled', false).text('Proceed to place order...');
            }
        });
        return false;

    });


    // Add Customer to customers table
    $(document).on('click', '.saveCustomer', function (e) {
        e.preventDefault();
        var c_name = $('#c_name').val();
        var c_phone = $('#c_phone').val();
        var c_email = $('#c_email').val();


        if(c_name != '' && c_phone != '')
        {
            if($.isNumeric(c_phone)){

                var data = {
                    'saveCustomerBtn': true,
                    'name': c_name,
                    'phone': c_phone,
                    'email': c_email,
                };

                $.ajax({
                    type: "POST",
                    url: "orders-code.php",
                    data: data,
                    success: function (response) {
                        var res = JSON.parse(response);

                        if(res.status == 200){
                            // add to dropdown
                            $('#cphone').append(
                                `<option value="${c_phone}" selected>
                                    ${c_phone} - ${c_name}
                                </option>`
                            ).trigger('change');
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModal').modal('hide');
                        }else if(res.status == 422){
                            swal(res.message, res.message, res.status_type);
                        }else{
                            swal(res.message, res.message, res.status_type);
                        }
                    }
                });

            }else{
                swal("Enter Valid Phone Number","","warning");
            }
        }
        else
        {
            swal("Please Fill required fields","","warning");
        }
        
    });


    /*
    |--------------------------------------------------------------------------
    | SAVING ORDER FUNCTION
    |--------------------------------------------------------------------------
    */
    let isProcessing = false; // prevent double click
    $('#saveOrder').click(function (){

        if (isProcessing) return; 
        isProcessing = true;

        let btn = $(this);
        btn.prop('disabled', true).text('processing...');

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'saveOrder': true
            },
            dataType: "json", // parse json
            success: function (res) {
                // var res = JSON.parse(response);

                if (res.status == 200) {
                    swal("Success", res.message, "success");

                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');

                } else {
                    swal("Warning", res.message, "warning");

                    // allow retry
                    isProcessing = false;
                    btn.prop('disabled', false).text('Save');
                }

            },
            error: function (xhr, status, error) {
                console.error(error);

                swal("Error", "Something went wrong. Please try again.", "error");

                // retry
                isProcessing = false;
                btn.prop('disabled', false).text('Save');
            }
        });

    });

});

function printMyBillingArea() {
        
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>POS System in PHP</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo){

    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html( elementHTML, {
        callback: function() {
            docPDF.save(invoiceNo+'.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });

}

