// Vanilla JavaScript - Employee Management System (Fetch API)
document.addEventListener('DOMContentLoaded', () => {
    const API_URL = 'employee_api.php';

    // DOM Elements
    const employeeForm = document.getElementById('employeeForm');
    const employeeIdInput = document.getElementById('employeeId');
    const firstNameInput = document.getElementById('firstName');
    const middleInitialInput = document.getElementById('middleInitial');
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('email');
    const mobileNumberInput = document.getElementById('mobileNumber');
    const sexSelect = document.getElementById('sex');
    const jobTitleInput = document.getElementById('jobTitle');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const formTitle = document.getElementById('formTitle');
    const employeesList = document.getElementById('employeesList');
    const employeeCount = document.getElementById('employeeCount');
    const searchInput = document.getElementById('searchInput');
    const alertMessage = document.getElementById('alertMessage');

    let allEmployees = [];

    // Initialize: Load Employees
    fetchEmployees();

    // Event Listeners
    employeeForm.addEventListener('submit', handleFormSubmit);
    cancelBtn.addEventListener('click', resetForm);
    searchInput.addEventListener('input', handleSearch);

    // Fetch All Employees (GET)
    async function fetchEmployees() {
        try {
            const response = await fetch(API_URL);
            const result = await response.json();

            if (result.status === 'success') {
                allEmployees = result.data || [];
                renderTable(allEmployees);
            } else {
                showAlert(result.message || 'Failed to fetch employees.', 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showAlert('Could not connect to the API server.', 'error');
        }
    }

    // Render Employees Table
    function renderTable(employees) {
        employeeCount.textContent = employees.length;

        if (employees.length === 0) {
            employeesList.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No employee records found.</td>
                </tr>
            `;
            return;
        }

        employeesList.innerHTML = employees.map(emp => {
            const mi = emp.middle_initial ? `${emp.middle_initial}.` : '';
            const fullName = `${escapeHtml(emp.first_name)} ${escapeHtml(mi)} ${escapeHtml(emp.last_name)}`.trim();
            const sexBadgeClass = emp.sex === 'Male' ? 'badge-male' : 'badge-female';

            return `
                <tr data-id="${emp.id}">
                    <td><strong>#${emp.id}</strong></td>
                    <td>${fullName}</td>
                    <td>${escapeHtml(emp.email)}</td>
                    <td>${escapeHtml(emp.mobile_number)}</td>
                    <td><span class="badge ${sexBadgeClass}">${escapeHtml(emp.sex)}</span></td>
                    <td>${escapeHtml(emp.job_title)}</td>
                    <td>
                        <button class="btn btn-sm btn-edit" onclick="handleEditClick(${emp.id})">Edit</button>
                        <button class="btn btn-sm btn-delete" onclick="handleDeleteClick(${emp.id}, '${escapeHtml(fullName)}')">Delete</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Handle Form Submit (POST for Create, PUT for Update)
    async function handleFormSubmit(e) {
        e.preventDefault();

        const id = employeeIdInput.value.trim();
        const payload = {
            first_name: firstNameInput.value.trim(),
            middle_initial: middleInitialInput.value.trim(),
            last_name: lastNameInput.value.trim(),
            email: emailInput.value.trim(),
            mobile_number: mobileNumberInput.value.trim(),
            sex: sexSelect.value,
            job_title: jobTitleInput.value.trim()
        };

        const isUpdating = Boolean(id);
        const url = isUpdating ? `${API_URL}?id=${id}` : API_URL;
        const method = isUpdating ? 'PUT' : 'POST';

        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showAlert(result.message || 'Operation successful!', 'success');
                resetForm();
                fetchEmployees();
            } else {
                showAlert(result.message || 'An error occurred.', 'error');
            }
        } catch (error) {
            console.error('Submit error:', error);
            showAlert('Network request failed. Please check connection.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = isUpdating ? 'Update Employee' : 'Save Employee';
        }
    }

    // Set Form to Edit Mode
    window.handleEditClick = function(id) {
        const emp = allEmployees.find(e => Number(e.id) === Number(id));
        if (!emp) return;

        employeeIdInput.value = emp.id;
        firstNameInput.value = emp.first_name || '';
        middleInitialInput.value = emp.middle_initial || '';
        lastNameInput.value = emp.last_name || '';
        emailInput.value = emp.email || '';
        mobileNumberInput.value = emp.mobile_number || '';
        sexSelect.value = emp.sex || '';
        jobTitleInput.value = emp.job_title || '';

        formTitle.textContent = `Edit Employee #${emp.id}`;
        submitBtn.textContent = 'Update Employee';
        cancelBtn.classList.remove('hidden');

        // Scroll to form smoothly
        employeeForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // Handle Delete (DELETE)
    window.handleDeleteClick = async function(id, name) {
        if (!confirm(`Are you sure you want to delete employee "${name}" (#${id})?`)) {
            return;
        }

        try {
            const response = await fetch(`${API_URL}?id=${id}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showAlert(result.message || 'Employee deleted successfully.', 'success');
                // If currently editing the deleted employee, reset form
                if (employeeIdInput.value === String(id)) {
                    resetForm();
                }
                fetchEmployees();
            } else {
                showAlert(result.message || 'Failed to delete employee.', 'error');
            }
        } catch (error) {
            console.error('Delete error:', error);
            showAlert('Error deleting employee record.', 'error');
        }
    };

    // Reset Form to Add Mode
    function resetForm() {
        employeeForm.reset();
        employeeIdInput.value = '';
        formTitle.textContent = 'Add New Employee';
        submitBtn.textContent = 'Save Employee';
        cancelBtn.classList.add('hidden');
    }

    // Real-time Search / Filter
    function handleSearch(e) {
        const term = e.target.value.toLowerCase().trim();
        if (!term) {
            renderTable(allEmployees);
            return;
        }

        const filtered = allEmployees.filter(emp => {
            const fullName = `${emp.first_name} ${emp.middle_initial || ''} ${emp.last_name}`.toLowerCase();
            return fullName.includes(term) ||
                (emp.email && emp.email.toLowerCase().includes(term)) ||
                (emp.mobile_number && emp.mobile_number.includes(term)) ||
                (emp.job_title && emp.job_title.toLowerCase().includes(term)) ||
                (emp.sex && emp.sex.toLowerCase().includes(term)) ||
                String(emp.id).includes(term);
        });

        renderTable(filtered);
    }

    // Alert Banner Helper
    let alertTimeout;
    function showAlert(message, type = 'success') {
        clearTimeout(alertTimeout);
        alertMessage.textContent = message;
        alertMessage.className = `alert ${type === 'success' ? 'alert-success' : 'alert-error'}`;
        alertMessage.classList.remove('hidden');

        alertTimeout = setTimeout(() => {
            alertMessage.classList.add('hidden');
        }, 4000);
    }

    // XSS sanitization helper
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
