// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.header-search-input, .msearch__input');
    
    searchInputs.forEach(input => {
        // Enter key to submit
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });

        // Clear button
        if (input.value) {
            addClearButton(input);
        }

        input.addEventListener('input', function() {
            if (this.value) {
                addClearButton(this);
            } else {
                removeClearButton(this);
            }
        });
    });

    function addClearButton(input) {
        if (input.parentElement.querySelector('.search-clear-btn')) return;
        
        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'search-clear-btn';
        clearBtn.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
        clearBtn.addEventListener('click', function() {
            input.value = '';
            input.focus();
            removeClearButton(input);
        });
        
        // Ensure parent has relative position
        input.parentElement.style.position = 'relative';
        
        input.parentElement.appendChild(clearBtn);
    }

    function removeClearButton(input) {
        const clearBtn = input.parentElement.querySelector('.search-clear-btn');
        if (clearBtn) {
            clearBtn.remove();
        }
    }
});
