<?php include('../includes/header.php'); ?>

<style>
    .page-hero {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-hero h2 {
        color: #2d3250;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }
    .page-hero p {
        color: #9ba3b8;
        font-size: 0.85rem;
        margin: 0.2rem 0 0;
    }

    .form-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
    }
    .form-panel-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
    }
    .panel-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.8rem;
    }
    .panel-icon.blue  { background: #eef2ff; color: #4361ee; }
    .panel-icon.amber { background: #fff8ec; color: #e67e22; }
    .panel-icon.slate { background: #f4f6fb; color: #6b7189; }
    .form-panel-header .title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2d3250;
    }
    .form-panel-body {
        padding: 1.25rem;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5072;
        margin-bottom: 0.35rem;
    }
    .form-control {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #2d3250;
    }
    .form-control:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }

    .hint-text {
        font-size: 0.78rem;
        color: #9ba3b8;
        margin-top: 0.3rem;
    }

    .warning-box {
        background: #fff8ec;
        border: 1px solid #fde8c0;
        border-radius: 9px;
        padding: 0.7rem 1rem;
        margin-bottom: 1rem;
        font-size: 0.82rem;
        color: #a05c00;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .warning-box i { margin-top: 0.1rem; flex-shrink: 0; }

    .btn-action {
        width: 100%;
        border: none;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 0.65rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: background 0.2s;
        cursor: pointer;
    }
    .btn-action.primary { background: #2d3250; color: #fff; }
    .btn-action.primary:hover { background: #424870; }
    .btn-action.amber  { background: #e67e22; color: #fff; }
    .btn-action.amber:hover  { background: #cf6d17; }

    /* Backups Table */
    .backups-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
        margin-top: 1.5rem;
    }
    .backups-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .backups-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .backups-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.8rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .backups-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .backups-table tbody tr:last-child { border-bottom: none; }
    .backups-table tbody tr:hover { background: #f8f9fc; }
    .backups-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .filename-text {
        font-family: monospace;
        font-size: 0.82rem;
        color: #2d3250;
        font-weight: 600;
    }
    .filesize-text { color: #6b7189; font-size: 0.82rem; }
    .filedate-text { color: #6b7189; font-size: 0.82rem; }

    .btn-dl {
        background: #eef2ff;
        color: #4361ee;
        border: none;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-dl:hover { background: #dce4ff; color: #4361ee; }

    .btn-restore-file {
        background: #fff8ec;
        color: #e67e22;
        border: none;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-restore-file:hover { background: #fde8c0; }

    .btn-del-file {
        background: #fdecea;
        color: #d63031;
        border: none;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-del-file:hover { background: #fbc8c8; }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-database me-2" style="opacity:0.75;"></i>Backup &amp; Restore</h2>
            <p>Manage your database backups and restore points</p>
        </div>
    </div>

    <!-- BACKUP + RESTORE PANELS -->
    <div class="row g-4 mb-2">

        <!-- CREATE BACKUP -->
        <div class="col-md-6">
            <div class="form-panel">
                <div class="form-panel-header">
                    <span class="panel-icon blue"><i class="fas fa-download"></i></span>
                    <span class="title">Create Backup</span>
                </div>
                <div class="form-panel-body">
                    <p class="hint-text mb-3">Generate an SQL file containing all your current data.</p>
                    <form id="backupForm">
                        <div class="mb-3">
                            <label class="form-label">Backup Name <span class="hint-text">(optional)</span></label>
                            <input type="text" name="backup_name" class="form-control"
                                   placeholder="Leave empty for auto-generated name">
                            <p class="hint-text">Default: backup_YYYY-MM-DD_HH-MM-SS.sql</p>
                        </div>
                        <button type="submit" class="btn-action primary">
                            <i class="fas fa-download"></i> Create Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RESTORE BACKUP -->
        <div class="col-md-6">
            <div class="form-panel">
                <div class="form-panel-header">
                    <span class="panel-icon amber"><i class="fas fa-upload"></i></span>
                    <span class="title">Restore Backup</span>
                </div>
                <div class="form-panel-body">
                    <p class="hint-text mb-3">Restore database from a previously created backup file.</p>
                    <div class="warning-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><strong>Warning:</strong> This will permanently replace all current data with the backup contents.</span>
                    </div>
                    <form id="restoreForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select Backup File <span style="color:#d63031;">(.sql)</span></label>
                            <input type="file" name="backup_file" class="form-control" accept=".sql" required>
                        </div>
                        <button type="submit" class="btn-action amber">
                            <i class="fas fa-upload"></i> Restore Database
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- AVAILABLE BACKUPS -->
    <div class="backups-table-wrap">
        <div class="form-panel-header" style="border-bottom:2px solid #e6e9f0;">
            <span class="panel-icon slate"><i class="fas fa-list"></i></span>
            <span class="title">Available Backups</span>
        </div>
        <div class="table-responsive">
            <table class="table backups-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>Created Date</th>
                        <th width="22%">Actions</th>
                    </tr>
                </thead>
                <tbody id="backupFilesList">
                    <tr>
                        <td colspan="5" class="text-center" style="color:#9ba3b8;padding:2.5rem;">
                            <i class="fas fa-spinner fa-spin me-2"></i> Loading backups...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include('../includes/footer.php'); ?>

<script src="../assets/js/backup.js"></script>
