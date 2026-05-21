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

    $(document).on('click', '.adjustStockBtn', function(e){

        e.preventDefault();

        let id = $(this).data('id');
        let name = $(this).data('name');
        let qty = $(this).data('qty');
        let branchId = $(this).data('branch');
        let branchName = $(this).data('branch-name');

        $('#adjust_item_id').val(id);
        $('#adjust_item_name').val(name);
        $('#current_stock').val(qty);

        $('#adjust_branch_id').val(branchId);
        $('#adjust_branch_name').val(branchName);

        $('#adjustStockModal').modal('show');

    });

    $('#adjustStockForm').submit(function(e){

        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "inventory-backend.php",
            method: "POST",
            data: formData + "&adjustStock=1",

            success: function(response){

                console.log(response);

                let res = JSON.parse(response);

                if(res.status == 200){

                    let modal = bootstrap.Modal.getInstance(
                        document.getElementById('adjustStockModal')
                    );

                    modal.hide();

                    $('#adjustStockForm')[0].reset();

                    loadInventoryTable();

                }else{
                    alert(res.message);
                }

            }
        });

    });
$(document).on('click','.editItemBtn',function(e){

    e.preventDefault();

    $('#edit_item_id')
        .val($(this).data('id'));

    $('#edit_item_name')
        .val($(this).data('name'));

    $('#edit_price')
        .val($(this).data('price'));

    $('#edit_branch_name')
        .val($(this).data('branch'));

    $('#editItemModal').modal('show');

});


$('#editItemForm').submit(function(e){

    e.preventDefault();

    let formData=$(this).serialize();

    $.ajax({

        url:'inventory-backend.php',

        method:'POST',

        data:formData+'&updateItem=1',

        success:function(response){

            let res=JSON.parse(response);

            if(res.status==200){

                bootstrap.Modal
                .getInstance(
                    document.getElementById(
                        'editItemModal'
                    )
                )
                .hide();

                loadInventoryTable();

            }else{

                alert(res.message);

            }

        }

    });

});
});