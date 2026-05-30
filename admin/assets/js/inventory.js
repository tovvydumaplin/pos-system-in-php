    function loadInventoryTable(page, search) {
        const urlParams = new URLSearchParams(window.location.search);
        const p = page  !== undefined ? page  : (urlParams.get('page')   || 1);
        const s = search !== undefined ? search : (urlParams.get('search') || '');
        let url = 'inventory-table.php?page=' + p;
        if (s) url += '&search=' + encodeURIComponent(s);
        $('#inventoryTable').load(url);
    }

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

$(document).on('click','.deleteItemBtn',function(e){

    e.preventDefault();

    $('#delete_item_id')
        .val($(this).data('id'));

    $('#delete_item_name')
        .text($(this).data('name'));

    $('#deleteItemModal').modal('show');

});


$(document).on('click','.deleteItemBtn',function(e){

    e.preventDefault();

    let itemId = $(this).data('id');

    if(confirm('Are you sure you want to delete this item?'))
    {
        $.ajax({

            url:'inventory-backend.php',

            method:'POST',

            data:{
                deleteItem:1,
                item_id:itemId
            },

            success:function(response){

                console.log(response);

                let res = JSON.parse(response);

                if(res.status == 200){

                    alert(res.message);



                }else{

                    alert(res.message);

                }

            }

        });
    }
    loadInventoryTable();

});
});