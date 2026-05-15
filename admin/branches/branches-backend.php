<?php

require '../../config/function.php';

// Add Branch
if(isset($_POST['saveBranch']))
{
    $branch_name = validate($_POST['branch_name']);
    $address = validate($_POST['address']);
    $contact_no = validate($_POST['contact_no']);
    $status = validate($_POST['status']);

    if($branch_name != ''){

        // Check if branch name already exists
        $branchCheck = mysqli_query($conn, "SELECT * FROM branches WHERE branch_name='$branch_name'");
        if($branchCheck){
            if(mysqli_num_rows($branchCheck) > 0){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Branch name already exists!'
                ]);
                exit;
            }
        }

        $data = [
            'branch_name' => $branch_name,
            'address' => $address,
            'contact_no' => $contact_no,
            'status' => $status,
            'created_by' => $_SESSION['loggedInUser']['name'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = insert('branches', $data);

        if($result){
            echo json_encode([
                'status' => 'success',
                'message' => 'Branch created successfully!'
            ]);
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ]);
        }

    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill all required fields.'
        ]);
    }
    exit;
}

// Update Branch
if(isset($_POST['updateBranch']))
{
    $branchId = validate($_POST['branchId']);
    $branch_name = validate($_POST['branch_name']);
    $address = validate($_POST['address']);
    $contact_no = validate($_POST['contact_no']);
    $status = validate($_POST['status']);

    if($branch_name != ''){

        // Check if branch name already exists (excluding current branch)
        $branchCheck = mysqli_query($conn, "SELECT * FROM branches WHERE branch_name='$branch_name' AND id!='$branchId'");
        if($branchCheck){
            if(mysqli_num_rows($branchCheck) > 0){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Branch name already exists!'
                ]);
                exit;
            }
        }

        $data = [
            'branch_name' => $branch_name,
            'address' => $address,
            'contact_no' => $contact_no,
            'status' => $status
        ];

        $result = update('branches', $branchId, $data);

        if($result){
            echo json_encode([
                'status' => 'success',
                'message' => 'Branch updated successfully!'
            ]);
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ]);
        }

    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill all required fields.'
        ]);
    }
    exit;
}

// Delete Branch
if(isset($_POST['deleteBranch']))
{
    $branchId = validate($_POST['branchId']);

    $branchData = getById('branches', $branchId);
    if($branchData['status'] != 200){
        echo json_encode([
            'status' => 'error',
            'message' => 'Branch not found!'
        ]);
        exit;
    }

    $result = delete('branches', $branchId);

    if($result){
        echo json_encode([
            'status' => 'success',
            'message' => 'Branch deleted successfully!'
        ]);
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong!'
        ]);
    }
    exit;
}

?>