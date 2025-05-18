<?php
include 'Settings/db.php';

// (Optional) You can fetch any common data here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/tables.css">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            background-color: #007BFF;
            color: #fff;
            border: none;
            outline: none;
            border-radius: 4px;
        }
        .tab-btn.active {
            background-color: #0056b3;
        }
        #section-container {
            margin: 0 auto;
            max-width: 90%;
            text-align: left;
        }
    </style>
    <script>
        // Function to load a section from a separate file via AJAX
        function loadSection(sectionName) {
            // Remove active class from all tab buttons
            var buttons = document.getElementsByClassName("tab-btn");
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }
            // Add active class to the clicked button
            document.getElementById("btn-" + sectionName).classList.add("active");

            // Create an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Sections/' + sectionName + '_dashboard.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('section-container').innerHTML = xhr.responseText;
                    // Only initialize if user section is loaded
                    if (sectionName === "user") {
                        initUserDashboardModals();
                    }
                } else {
                    document.getElementById('section-container').innerHTML = '<p>Error loading section: ' + xhr.status + '</p>';
                }
            };
            xhr.send();
        }

        window.onload = function() {
            loadSection("user"); // Load default section on page load
        }

        function initUserDashboardModals() {
            // EDIT - open modal
            document.querySelectorAll('.edit-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const row = btn.closest('tr');
                    console.log('Edit button clicked for ID:', btn.dataset.id);
                    if (!row) {
                        console.log('No row found for button!');
                        return;
                    }
                    document.getElementById('edit-id').value = btn.dataset.id;
                    document.getElementById('edit-username').value = row.children[1].textContent;
                    document.getElementById('edit-email').value = row.children[2].textContent;
                    document.getElementById('edit-role').value = row.children[3].textContent;
                    document.getElementById('edit-type').value = row.children[4].textContent;
                    document.getElementById('editModal').style.display = 'flex';
                    console.log('Modal should now be visible');
                });
            });

            // Cancel button
            var cancelBtn = document.getElementById('cancelEdit');
            if (cancelBtn) {
                cancelBtn.onclick = function() {
                    console.log('Cancel button clicked');
                    document.getElementById('editModal').style.display = 'none';
                };
            }

            // Hide modal on background click
            var editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        console.log('Clicked outside modal, hiding modal');
                        this.style.display = 'none';
                    }
                });
            }

            // Submit edit form
            var editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.onsubmit = function(e) {
                    e.preventDefault();
                    const id = document.getElementById('edit-id').value;
                    const username = document.getElementById('edit-username').value;
                    const email = document.getElementById('edit-email').value;
                    const role_id = document.getElementById('edit-role').value;
                    const type_id = document.getElementById('edit-type').value;

                    console.log('Submitting edit:', {id, username, email, role_id, type_id});

                    fetch('../api/microservice_user/crud/edit.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            id: id,
                            username: username,
                            email: email,
                            role_id: role_id,
                            type_id: type_id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        document.getElementById('editModal').style.display = 'none';
                        location.reload();
                    });
                };
            }
        }
    </script>
</head>
<body>
    <h1>Welcome to the StockRoute Dashboard</h1>

    <div class="tabs">
        <button id="btn-user" class="tab-btn" onclick="loadSection('user')">Microservice Users</button>
        <button id="btn-supplier_products" class="tab-btn" onclick="loadSection('supplier_products')">Supplier Products</button>
        <button id="btn-delivery_orders" class="tab-btn" onclick="loadSection('delivery_orders')">Delivery Orders</button>
    </div>

    <div id="section-container">
        <!-- Loaded section content will appear here -->
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    initUserDashboardModals();
});
</script>
</body>
</html>
