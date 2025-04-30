document.addEventListener('DOMContentLoaded', function() {
    // Toggle category options based on transaction type for Add form
    document.querySelectorAll('input[name="transaction_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            toggleCategoryOptions();
        });
    });

    // Initial toggle for add form
    toggleCategoryOptions();

    // Edit transaction
    document.querySelectorAll('.edit-transaction').forEach(function(button) {
        button.addEventListener('click', function() {
            const transaction = JSON.parse(this.getAttribute('data-transaction'));
            document.getElementById('edit_transaction_id').value = transaction.id;

            if (transaction.category_type === 'revenu') {
                document.getElementById('edit_typeRevenu').checked = true;
            } else {
                document.getElementById('edit_typeDepense').checked = true;
            }

            document.getElementById('edit_category_id').value = transaction.category_id;
            document.getElementById('edit_montant').value = transaction.montant;
            document.getElementById('edit_description').value = transaction.description;
            document.getElementById('edit_date_transaction').value = transaction.date_transaction;

            toggleEditCategoryOptions();
        });
    });

    // Toggle category options in edit form
    document.querySelectorAll('input[name="edit_transaction_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            toggleEditCategoryOptions();
        });
    });

    // Delete transaction
    document.querySelectorAll('.delete-transaction').forEach(function(button) {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-id');
            document.getElementById('delete_transaction_id').value = transactionId;
        });
    });

    // Function to toggle category options based on transaction type
    function toggleCategoryOptions() {
        const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
        const categorySelect = document.getElementById('category_id');

        for (let i = 0; i < categorySelect.options.length; i++) {
            const option = categorySelect.options[i];
            const optgroup = option.parentNode;

            if (optgroup.tagName === 'OPTGROUP') {
                if ((transactionType === 'revenu' && optgroup.label === 'Revenus') || 
                    (transactionType === 'depense' && optgroup.label === 'Dépenses')) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        }

        // Set first visible option as selected
        for (let i = 0; i < categorySelect.options.length; i++) {
            if (categorySelect.options[i].style.display !== 'none') {
                categorySelect.selectedIndex = i;
                break;
            }
        }
    }

    // Function to toggle category options in edit form
    function toggleEditCategoryOptions() {
        const transactionType = document.querySelector('input[name="edit_transaction_type"]:checked').value;
        const categorySelect = document.getElementById('edit_category_id');

        for (let i = 0; i < categorySelect.options.length; i++) {
            const option = categorySelect.options[i];
            const optgroup = option.parentNode;

            if (optgroup.tagName === 'OPTGROUP') {
                if ((transactionType === 'revenu' && optgroup.label === 'Revenus') || 
                    (transactionType === 'depense' && optgroup.label === 'Dépenses')) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        }

        // Check if current selection is valid
        const currentOption = categorySelect.options[categorySelect.selectedIndex];
        const currentOptgroup = currentOption.parentNode;

        if ((transactionType === 'revenu' && currentOptgroup.label !== 'Revenus') || 
            (transactionType === 'depense' && currentOptgroup.label !== 'Dépenses')) {
            // Set first visible option as selected
            for (let i = 0; i < categorySelect.options.length; i++) {
                if (categorySelect.options[i].style.display !== 'none') {
                    categorySelect.selectedIndex = i;
                    break;
                }
            }
        }
    }
});