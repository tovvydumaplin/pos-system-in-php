$(document).ready(function() {

    // Add Branch Form Submit
    $('#addBranchForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('saveBranch', true);

        $.ajax({
            url: 'branches-backend.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#addBranchModal').modal('hide');
                    $('#addBranchForm')[0].reset();
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Edit Branch Form Submit
    $('#editBranchForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('updateBranch', true);

        $.ajax({
            url: 'branches-backend.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#editBranchModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Delete Branch
    $(document).on('click', '.deleteBranchBtn', function() {
        var branchId = $(this).data('id');
        var branchName = $(this).data('name');

        if(confirm('Are you sure you want to delete "' + branchName + '"?')) {
            $.ajax({
                url: 'branches-backend.php',
                type: 'POST',
                data: {
                    deleteBranch: true,
                    branchId: branchId
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    // Load Branch Data for Edit
    $(document).on('click', '.editBranchBtn', function() {
        var branchId = $(this).data('id');
        var branchName = $(this).data('name');
        var address = $(this).data('address');
        var contactNumber = $(this).data('contact');
        var status = $(this).data('status');

        $('#editBranchId').val(branchId);
        $('#editBranchName').val(branchName);
        $('#editAddress').val(address);
        $('#editContactNumber').val(contactNumber);
        $('#editStatus').val(status);

        $('#editBranchModal').modal('show');
    });

});
