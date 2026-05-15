<?php

require '../../config/dbcon.php';

// Create backups directory if it doesn't exist
$backupDir = __DIR__ . '/../../backups/';
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// ============================================
// CREATE BACKUP
// ============================================
if(isset($_POST['createBackup']))
{
    $backup_name = isset($_POST['backup_name']) && !empty($_POST['backup_name']) 
        ? $_POST['backup_name'] 
        : 'backup_' . date('Y-m-d_H-i-s');
    
    // Ensure .sql extension
    if (substr($backup_name, -4) !== '.sql') {
        $backup_name .= '.sql';
    }

    $backupFile = $backupDir . $backup_name;

    try {
        // Get all tables
        $tables = [];
        $result = mysqli_query($conn, "SHOW TABLES");
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $sqlScript = "-- Database Backup\n";
        $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sqlScript .= "-- Database: " . DB_DATABASE . "\n\n";
        $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        // Loop through tables
        foreach ($tables as $table) {
            // Drop table statement
            $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";

            // Create table statement
            $createTableResult = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
            $createTableRow = mysqli_fetch_row($createTableResult);
            $sqlScript .= $createTableRow[1] . ";\n\n";

            // Insert data
            $rows = mysqli_query($conn, "SELECT * FROM `$table`");
            if (mysqli_num_rows($rows) > 0) {
                while ($row = mysqli_fetch_assoc($rows)) {
                    $sqlScript .= "INSERT INTO `$table` VALUES(";
                    $fieldCount = 0;
                    foreach ($row as $field) {
                        if ($fieldCount > 0) {
                            $sqlScript .= ", ";
                        }
                        $sqlScript .= $field === null ? "NULL" : "'" . mysqli_real_escape_string($conn, $field) . "'";
                        $fieldCount++;
                    }
                    $sqlScript .= ");\n";
                }
            }
            $sqlScript .= "\n";
        }

        $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Write to file
        if (file_put_contents($backupFile, $sqlScript)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Backup created successfully!',
                'filename' => $backup_name
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to write backup file!'
            ]);
        }

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Backup failed: ' . $e->getMessage()
        ]);
    }
    exit;
}

// ============================================
// RESTORE BACKUP
// ============================================
if(isset($_POST['restoreBackup']))
{
    if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please select a valid backup file!'
        ]);
        exit;
    }

    $tmpFile = $_FILES['backup_file']['tmp_name'];
    $sqlScript = file_get_contents($tmpFile);

    if (empty($sqlScript)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Backup file is empty!'
        ]);
        exit;
    }

    try {
        // Disable foreign key checks
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

        // Execute SQL script
        $queries = explode(';', $sqlScript);
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if (!mysqli_query($conn, $query)) {
                    throw new Exception(mysqli_error($conn));
                }
            }
        }

        // Re-enable foreign key checks
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

        echo json_encode([
            'status' => 'success',
            'message' => 'Database restored successfully!'
        ]);

    } catch (Exception $e) {
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
        echo json_encode([
            'status' => 'error',
            'message' => 'Restore failed: ' . $e->getMessage()
        ]);
    }
    exit;
}

// ============================================
// LIST BACKUP FILES
// ============================================
if(isset($_POST['listBackups']))
{
    $backupFiles = [];
    
    if (is_dir($backupDir)) {
        $files = scandir($backupDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                $filePath = $backupDir . $file;
                $backupFiles[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'date' => date('Y-m-d H:i:s', filemtime($filePath))
                ];
            }
        }
    }

    // Sort by date (newest first)
    usort($backupFiles, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    echo json_encode($backupFiles);
    exit;
}

// ============================================
// DOWNLOAD BACKUP FILE
// ============================================
if(isset($_GET['download']))
{
    $filename = basename($_GET['download']);
    $filePath = $backupDir . $filename;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die('File not found!');
    }
}

// ============================================
// DELETE BACKUP FILE
// ============================================
if(isset($_POST['deleteBackup']))
{
    $filename = basename($_POST['filename']);
    $filePath = $backupDir . $filename;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Backup deleted successfully!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete backup file!'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'File not found!'
        ]);
    }
    exit;
}

?>
