function initDashboardModals(section) {
    const container = document.getElementById('section-container');
    if (!container) return;

    // Edit buttons
    container.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.onclick = function() {
            const row = btn.closest('tr');
            if (!row) return;
            // Find the modal in the loaded section
            const modal = container.querySelector('#editModal');
            if (!modal) return;

            // Fill modal fields based on section
            if (section === 'user') {
                modal.querySelector('#edit-id').value = btn.dataset.id;
                modal.querySelector('#edit-username').value = row.children[1].textContent;
                modal.querySelector('#edit-email').value = row.children[2].textContent;
                modal.querySelector('#edit-role').value = row.children[3].textContent;
                modal.querySelector('#edit-type').value = row.children[4].textContent;
            } else if (section === 'supplier_products') {
                modal.querySelector('#edit-product-id').value = btn.dataset.id;
                modal.querySelector('#edit-product-name').value = row.children[1].textContent;
                modal.querySelector('#edit-supplier-name').value = row.children[2].textContent;
                modal.querySelector('#edit-price').value = row.children[3].textContent.replace(/[^\d.]/g, '');
                modal.querySelector('#edit-stock').value = row.children[4].textContent;
                modal.querySelector('#edit-category').value = row.children[5].textContent;
                modal.querySelector('#edit-description').value = row.children[6].textContent;
            } else if (section === 'delivery_orders') {
                modal.querySelector('#edit-order-id').value = btn.dataset.id;
                modal.querySelector('#edit-order-status').value = row.children[4].textContent;
                // Add more fields if you want to edit more than status
            }

            modal.style.display = 'flex';
        };
    });

    // Cancel button
    const cancelBtn = container.querySelector('#cancelEdit');
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            const modal = container.querySelector('#editModal');
            if (modal) modal.style.display = 'none';
        };
    }

    // Hide modal on background click
    const editModal = container.querySelector('#editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    }

    // Submit edit form
    const editForm = container.querySelector('#editForm');
    if (editForm) {
        editForm.onsubmit = function(e) {
            e.preventDefault();
            // Gather form data based on section
            let payload = {};
            let endpoint = '';
            if (section === 'user') {
                payload = {
                    id: editForm.querySelector('#edit-id').value,
                    username: editForm.querySelector('#edit-username').value,
                    email: editForm.querySelector('#edit-email').value,
                    role_id: editForm.querySelector('#edit-role').value,
                    type_id: editForm.querySelector('#edit-type').value
                };
                endpoint = 'api/microservice_user/crud/edit.php';
            } else if (section === 'supplier_products') {
                payload = {
                    product_id: editForm.querySelector('#edit-product-id').value,
                    name: editForm.querySelector('#edit-product-name').value,
                    price: editForm.querySelector('#edit-price').value,
                    stock: editForm.querySelector('#edit-stock').value,
                    category: editForm.querySelector('#edit-category').value,
                    description: editForm.querySelector('#edit-description').value,
                };
                endpoint = 'api/microservice_supplier_products/crud/edit.php';
            } else if (section === 'delivery_orders') {
                payload = {
                    order_id: editForm.querySelector('#edit-order-id').value,
                    order_status: editForm.querySelector('#edit-order-status').value
                    // Add more fields if needed
                };
                endpoint = 'api/microservice_delivery_orders/crud/edit.php';
            }

            fetch(endpoint, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                editModal.style.display = 'none';
                location.reload();
            });
        };
    }

    // Use event delegation for delete buttons
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
            const btn = e.target;
            let itemType = '';
            if (section === 'user') itemType = 'user';
            else if (section === 'supplier_products') itemType = 'product';
            else if (section === 'delivery_orders') itemType = 'order';

            const confirmed = window.confirm(`Are you sure you want to delete this ${itemType}? This action cannot be undone.`);
            if (!confirmed) {
                alert('Deletion cancelled.');
                return;
            }

            let payload = {};
            let endpoint = '';
            if (section === 'user') {
                payload = { id: btn.dataset.id };
                endpoint = 'api/microservice_user/crud/delete.php';
            } else if (section === 'supplier_products') {
                payload = { product_id: btn.dataset.id };
                endpoint = 'api/microservice_supplier_products/crud/delete.php';
            } else if (section === 'delivery_orders') {
                payload = { order_id: btn.dataset.id };
                endpoint = 'api/microservice_delivery_orders/crud/delete.php';
            }

            fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                location.reload();
            });
        }
    });
}