$(document).ready(function() {

    // Load backup files list
    function loadBackupFiles() {
        $.ajax({
            url: 'backup-backend.php',
            type: 'POST',
            data: { listBackups: true },
            dataType: 'json',
            success: function(files) {
                var html = '';
                
                if(files.length > 0) {
                    $.each(files, function(index, file) {
                        var fileSize = formatBytes(file.size);
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td><i class="fas fa-file-code text-secondary"></i> ' + file.name + '</td>';
                        html += '<td><small class="text-muted">' + fileSize + '</small></td>';
                        html += '<td><small class="text-muted">' + file.date + '</small></td>';
                        html += '<td>';
                        html += '<a href="backup-backend.php?download=' + file.name + '" class="btn btn-sm btn-outline-primary me-1" title="Download">';
                        html += '<i class="fas fa-download"></i>';
                        html += '</a>';
                        html += '<button class="btn btn-sm btn-outline-danger deleteBackupBtn" data-filename="' + file.name + '" title="Delete">';
                        html += '<i class="fas fa-trash"></i>';
                        html += '</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-folder-open"></i> No backup files found</td></tr>';
                }
                
                $('#backupFilesList').html(html);
            },
            error: function() {
                $('#backupFilesList').html('<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-exclamation-circle"></i> Failed to load backups</td></tr>');
            }
        });
    }

    // Format bytes to human readable
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Initial load
    loadBackupFiles();

    // Create Backup
    $('#backupForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('createBackup', true);

        var btn = $(this).find('button[type="submit"]');
        var btnText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');

        $.ajax({
            url: 'backup-backend.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#backupForm')[0].reset();
                    loadBackupFiles();
                } else {
                    alert(response.message);
                }
                btn.prop('disabled', false).html(btnText);
            },
            error: function() {
                alert('An error occurred while creating backup.');
                btn.prop('disabled', false).html(btnText);
            }
        });
    });

    // Restore Backup
    $('#restoreForm').on('submit', function(e) {
        e.preventDefault();

        if(!confirm('Are you sure you want to restore this backup? This will REPLACE ALL current data!')) {
            return;
        }

        var formData = new FormData(this);
        formData.append('restoreBackup', true);

        var btn = $(this).find('button[type="submit"]');
        var btnText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Restoring...');

        $.ajax({
            url: 'backup-backend.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message + '\n\nYou will be logged out. Please login again.');
                    window.location.href = '../../logout.php';
                } else {
                    alert(response.message);
                    btn.prop('disabled', false).html(btnText);
                }
            },
            error: function() {
                alert('An error occurred while restoring backup.');
                btn.prop('disabled', false).html(btnText);
            }
        });
    });

    // Delete Backup
    $(document).on('click', '.deleteBackupBtn', function() {
        var filename = $(this).data('filename');

        if(confirm('Are you sure you want to delete "' + filename + '"?')) {
            $.ajax({
                url: 'backup-backend.php',
                type: 'POST',
                data: {
                    deleteBackup: true,
                    filename: filename
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        alert(response.message);
                        loadBackupFiles();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting backup.');
                }
            });
        }
    });

});
