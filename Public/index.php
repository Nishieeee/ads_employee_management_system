<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="app-header">
        <div class="container header-container">
            <h1>Employee Management System</h1>
            <a href="test_connection.php" target="_blank" class="badge-link">Test DB Connection</a>
        </div>
    </header>

    <main class="container">
        <!-- Notification Message Banner -->
        <div id="alertMessage" class="alert hidden" role="alert"></div>

        <div class="content-grid">
            <!-- Form Section -->
            <section class="card form-card">
                <div class="card-header">
                    <h2 id="formTitle">Add New Employee</h2>
                </div>
                <div class="card-body">
                    <form id="employeeForm">
                        <input type="hidden" id="employeeId" value="">

                        <div class="form-row">
                            <div class="form-group flex-2">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="first_name" required placeholder="e.g. John">
                            </div>

                            <div class="form-group flex-1">
                                <label for="middleInitial">M.I.</label>
                                <input type="text" id="middleInitial" name="middle_initial" maxlength="1" placeholder="A">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="last_name" required placeholder="e.g. Smith">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required placeholder="john.smith@example.com">
                        </div>

                        <div class="form-group">
                            <label for="mobileNumber">Mobile Number <span class="required">*</span></label>
                            <input type="text" id="mobileNumber" name="mobile_number" required placeholder="09123456789">
                        </div>

                        <div class="form-row">
                            <div class="form-group flex-1">
                                <label for="sex">Sex <span class="required">*</span></label>
                                <select id="sex" name="sex" required>
                                    <option value="">Select...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="form-group flex-2">
                                <label for="jobTitle">Job Title <span class="required">*</span></label>
                                <input type="text" id="jobTitle" name="job_title" required placeholder="e.g. Web Developer">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="submitBtn" class="btn btn-primary">Save Employee</button>
                            <button type="button" id="cancelBtn" class="btn btn-secondary hidden">Cancel</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Table Section -->
            <section class="card table-card">
                <div class="card-header table-header-flex">
                    <h2>Employee Records (<span id="employeeCount">0</span>)</h2>
                    <input type="text" id="searchInput" placeholder="Search employees..." class="search-input">
                </div>
                <div class="card-body table-responsive">
                    <table class="data-table" id="employeesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Sex</th>
                                <th>Job Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesList">
                            <tr>
                                <td colspan="7" class="text-center">Loading employees...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
    <script src="js/main.js"></script>
</body>
</html>
