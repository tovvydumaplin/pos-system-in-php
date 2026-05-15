<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h4 class="mb-0">
            <i class="fas fa-database text-primary"></i> Backup & Restore Database
        </h4>
    </div>

    <div class="row">

        <!-- CREATE BACKUP CARD -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-download text-primary"></i> Create Backup
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Create a backup of your database. This will generate an SQL file containing all your data.
                    </p>

                    <form id="backupForm">
                        <div class="mb-3">
                            <label class="form-label">Backup Name <small class="text-muted">(Optional)</small></label>
                            <input 
                                type="text" 
                                name="backup_name" 
                                class="form-control" 
                                placeholder="Leave empty for auto-generated name"
                            >
                            <small class="text-muted">
                                Default: backup_YYYY-MM-DD_HH-MM-SS.sql
                            </small>
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-primary w-100"
                        >
                            <i class="fas fa-download"></i> Create Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RESTORE BACKUP CARD -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-upload text-warning"></i> Restore Backup
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Restore database from a backup file.
                    </p>
                    <div class="alert alert-warning border-warning py-2 mb-3" role="alert">
                        <small class="mb-0">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Warning:</strong> This will replace all current data!
                        </small>
                    </div>

                    <form id="restoreForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select Backup File (.sql)</label>
                            <input 
                                type="file" 
                                name="backup_file" 
                                class="form-control" 
                                accept=".sql"
                                required
                            >
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-warning w-100"
                        >
                            <i class="fas fa-upload"></i> Restore Database
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- BACKUP FILES LIST -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Available Backups
            </h5>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>File Name</th>
                            <th>File Size</th>
                            <th>Created Date</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="backupFilesList">
                        <!-- Loaded via AJAX -->
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Loading backups...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php include('../includes/footer.php'); ?>

<script src="../assets/js/backup.js"></script>
