@extends('layouts.master')

@section("content")
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg rounded" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 0; font-size: 1.4rem; font-weight: 700; padding: 20px; background: linear-gradient(135deg, #68f5d1, #8e89ec); border-bottom: none;">
                    <h3><i class="fas fa-file-import"></i> Importer des données</h3>
                </div>
                <div class="card-body" style="padding: 30px; background-color: #f9fafb;">
                    <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data" class="p-3 border rounded bg-light" style="border-radius: 8px; padding: 12px 15px; border: 1px solid #e2e8f0;">
                        @csrf
                        <div class="form-group">
                            <label for="file" class="h5" style="font-weight: 600; color: #212529; margin-bottom: 8px;"><i class="fas fa-file-csv"></i> Fichier CSV 1</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required style="border-radius: 8px; padding: 12px 15px; border: 1px solid #e2e8f0;">
                            
                            <label for="file2" class="h5 mt-3" style="font-weight: 600; color: #212529; margin-bottom: 8px;"><i class="fas fa-file-csv"></i> Fichier CSV 2</label>
                            <input type="file" class="form-control @error('file2') is-invalid @enderror" id="file2" name="file2" required style="border-radius: 8px; padding: 12px 15px; border: 1px solid #e2e8f0;">
                            
                            <label for="file3" class="h5 mt-3" style="font-weight: 600; color: #212529; margin-bottom: 8px;"><i class="fas fa-file-csv"></i> Fichier CSV 3</label>
                            <input type="file" class="form-control @error('file3') is-invalid @enderror" id="file3" name="file3" required style="border-radius: 8px; padding: 12px 15px; border: 1px solid #e2e8f0;">

                            @error('file')
                                <div class="invalid-feedback" style="color: #e74c3c;">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" style="background-color: #68f5d1; border: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); padding: 12px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; font-size: 0.9rem; border-radius: 8px;">
                            <i class="fas fa-upload"></i> Importer
                        </button>
                    </form>

                    @if(session('success') || session('error'))
                        <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} mt-4" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 20px;">
                            @if(session('success'))
                                <h5 class="font-weight-bold text-center" style="font-weight: 700; font-size: 1.2rem;">
                                    <i class="fas fa-check-circle"></i> Importation réussie
                                </h5>
                            @else
                                <h5 class="font-weight-bold text-center" style="font-weight: 700; font-size: 1.2rem;">
                                    <i class="fas fa-exclamation-circle"></i> Erreur lors de l'importation
                                </h5>
                            @endif
                            
                            <div class="text-center mt-3">
                                <p><strong>Fichiers importés :</strong></p>
                                <p>{{ session('file_name') }}</p>
                                <p>{{ session('file_name2') }}</p>
                                <p>{{ session('file_name3') }}</p>
                            </div>
                            
                            @if(session('success'))
                                <div class="text-center mt-3">
                                    @if(session('imported_projects_rows'))
                                        <span class="badge badge-success mr-2" style="background-color: #33fb4e; color: white; padding: 5px 10px; border-radius: 8px;">
                                            Projets: {{ session('imported_projects_rows') }} lignes
                                        </span>
                                    @endif
                                    @if(session('imported_project_tasks_rows'))
                                        <span class="badge badge-success mr-2" style="background-color: #33fb4e; color: white; padding: 5px 10px; border-radius: 8px;">
                                            Tâches: {{ session('imported_project_tasks_rows') }} lignes
                                        </span>
                                    @endif
                                    @if(session('imported_offers_rows'))
                                        <span class="badge badge-success" style="background-color: #33fb4e; color: white; padding: 5px 10px; border-radius: 8px;">
                                            Offres: {{ session('imported_offers_rows') }} lignes
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            @if(session('skipped_rows'))
                                <div class="text-center mt-3">
                                    <span class="badge badge-danger" style="background-color: #f72533; color: white; padding: 5px 10px; border-radius: 8px;">
                                        Lignes en erreur: {{ session('skipped_rows') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(session('import_errors'))
                        <div class="mt-4">
                            <h4 class="text-danger text-center" style="font-size: 1.4rem;">
                                <i class="fas fa-exclamation-triangle"></i> Erreurs d'import
                            </h4>
                            <div class="table-responsive" style="margin-bottom: 30px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                                <table class="table table-bordered table-hover" style="width: 100%; border-collapse: separate; border-spacing: 0; background-color: white; font-size: 0.9rem;">
                                    <thead class="thead-dark" style="background-color: #212529; color: white;">
                                        <tr>
                                            <th>Fichier</th>
                                            <th>Ligne</th>
                                            <th>Champ</th>
                                            <th>Erreur</th>
                                            <th>Valeur incorrecte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('import_errors') as $error)
                                            <tr class="table-danger" style="background-color: #f8fafc;">
                                                <td>{{ $error['source_file'] ?? 'N/A' }}</td>
                                                <td>{{ $error['row']-1 }}</td>
                                                <td>{{ $error['attribute'] }}</td>
                                                <td>
                                                    <ul class="mb-0">
                                                        @foreach($error['errors'] as $message)
                                                            <li>{{ $message }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>{{ $error['values'][$error['attribute']] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if(session('projects'))
                        <div class="mt-4">
                            <h4 class="text-success text-center" style="font-size: 1.4rem;">
                                <i class="fas fa-check-circle"></i> Projets importés
                            </h4>
                            <div class="table-responsive" style="margin-bottom: 30px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                                <table class="table table-bordered table-hover" style="width: 100%; border-collapse: separate; border-spacing: 0; background-color: white; font-size: 0.9rem;">
                                    <thead class="thead-light" style="background-color: #f1f1f1;">
                                        <tr>
                                            <th>Nom du projet</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('projects') as $project)
                                            <tr>
                                                <td>{{ $project['name'] }}</td>
                                                <td>{{ $project['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    function setupPagination(tableId, paginationId, rowsPerPage = 10) {
        let table = document.getElementById(tableId);
        if (!table) return;
        
        let pagination = document.getElementById(paginationId);
        if (!pagination) return;
        
        let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        let totalRows = rows.length;
        let totalPages = Math.ceil(totalRows / rowsPerPage);
        let currentPage = 1;

        function showPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            for (let i = 0; i < totalRows; i++) {
                rows[i].style.display = (i >= start && i < end) ? "table-row" : "none";
            }
        }

        function renderPagination() {
            pagination.innerHTML = "";
            let ul = document.createElement("ul");
            ul.classList.add("pagination-list");

            // Previous button
            if (totalPages > 1) {
                let prevLi = document.createElement("li");
                prevLi.textContent = "«";
                prevLi.classList.add("page-item");
                if (currentPage === 1) {
                    prevLi.classList.add("disabled");
                }
                prevLi.addEventListener("click", function () {
                    if (currentPage > 1) {
                        currentPage--;
                        showPage(currentPage);
                        updateActivePage();
                    }
                });
                ul.appendChild(prevLi);
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                let li = document.createElement("li");
                li.textContent = i;
                li.classList.add("page-item");
                if (i === currentPage) {
                    li.classList.add("active");
                }
                li.addEventListener("click", function () {
                    currentPage = i;
                    showPage(currentPage);
                    updateActivePage();
                });
                ul.appendChild(li);
            }

            // Next button
            if (totalPages > 1) {
                let nextLi = document.createElement("li");
                nextLi.textContent = "»";
                nextLi.classList.add("page-item");
                if (currentPage === totalPages) {
                    nextLi.classList.add("disabled");
                }
                nextLi.addEventListener("click", function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                        updateActivePage();
                    }
                });
                ul.appendChild(nextLi);
            }

            pagination.appendChild(ul);
        }

        function updateActivePage() {
            let items = pagination.querySelectorAll(".page-item");
            items.forEach((item, index) => {
                if (item.textContent === "«" || item.textContent === "»") return;
                item.classList.toggle("active", parseInt(item.textContent) === currentPage);
            });
        }

        showPage(currentPage);
        renderPagination();
    }

    // Initialize pagination for all tables
    setupPagination("tableProjects", "paginationProjects");
    setupPagination("tableProjectTasks", "paginationProjectTasks");
    setupPagination("tableOffers", "paginationOffers");
});
</script>
<style>
<style>
    /* Couleurs de base */
    :root {
        --primary-color: #68f5d1;
        --secondary-color: #8e89ec;
        --success-color: #33fb4e;
        --danger-color: #f72533;
        --light-color: #f8f9fa;
        --dark-color: #212529;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination-list {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 5px;
    }

    .page-item {
        padding: 8px 14px;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: white;
        color: var(--primary-color);
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-item:hover:not(.disabled),
    .page-item.active {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .page-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f5f5f5;
    }

    /* Tables */
    .table-responsive {
        margin-bottom: 30px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: white;
        font-size: 0.9rem;
    }

    .table th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        position: sticky;
        top: 0;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .table tr:hover {
        background-color: #f1f5f9;
    }

    /* Card */
    .card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-header {
        border-radius: 0;
        font-size: 1.4rem;
        font-weight: 700;
        padding: 20px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-bottom: none;
    }

    .card-body {
        padding: 30px;
        background-color: #f9fafb;
    }

    /* Boutons */
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.9rem;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Formulaires */
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
    }

    /* Alertes */
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    /* Badges */
    .badge {
        padding: 8px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-success {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Icônes */
    .fas {
        margin-right: 8px;
    }

    /* En-têtes */
    h3, h4, h5 {
        font-weight: 700;
        color: var(--dark-color);
    }

    /* Effets globaux */
    body {
        background-color: #f1f5f9;
    }

    .rounded {
        border-radius: 12px !important;
    }

    /* Animation de chargement */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card, .table-responsive, .alert {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
</style>
@endsection