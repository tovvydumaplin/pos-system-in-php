$(document).ready(function(){

    $('#addItemForm').submit(function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "inventory-backend.php",
            method: "POST",
            data: formData + "&saveItem=1",
            success: function(response){

                let res = JSON.parse(response);

                if(res.status == 200){

                    // close modal
                    let modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
                    modal.hide();

                    // reset form
                    $('#addItemForm')[0].reset();

                    // reload table
                   loadInventoryTable();

                }else{
                    alert(res.message);
                }

            }
        });
    });

    function loadInventoryTable(){
        $('#inventoryTable').load('inventory-table.php');
    }
    loadInventoryTable();

});