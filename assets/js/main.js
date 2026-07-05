/**
 * SupTech - Scripts principaux
 */
document.addEventListener('DOMContentLoaded', function () {

    // Toggle sidebar mobile
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('show');
            }
        });
    }

    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Calcul automatique des moyennes (page notes)
    document.querySelectorAll('.notes-table tbody tr').forEach(function (row) {
        const inputs = row.querySelectorAll('.note-input');
        const moyenneCell = row.querySelector('.moyenne-cell');

        if (inputs.length >= 3 && moyenneCell) {
            inputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    calculerMoyenne(row, inputs, moyenneCell);
                });
            });
        }
    });

    function calculerMoyenne(row, inputs, moyenneCell) {
        const cc = parseFloat(inputs[0].value) || 0;
        const tp = parseFloat(inputs[1].value) || 0;
        const exam = parseFloat(inputs[2].value);

        if (inputs[2].value === '' || isNaN(exam)) {
            moyenneCell.textContent = '--';
            moyenneCell.classList.remove('text-primary', 'text-success');
            row.classList.add('row-incomplete');
            return;
        }

        const moyenne = (cc * 0.3) + (tp * 0.2) + (exam * 0.5);
        moyenneCell.textContent = moyenne.toFixed(2);
        moyenneCell.classList.add('text-primary');
        row.classList.remove('row-incomplete');

        const mentionCell = row.querySelector('.mention-cell span');
        if (mentionCell) {
            if (moyenne >= 16) {
                mentionCell.textContent = 'Très Bien';
                mentionCell.className = 'badge-mention tres-bien';
            } else if (moyenne >= 14) {
                mentionCell.textContent = 'Bien';
                mentionCell.className = 'badge-mention bien';
            } else if (moyenne >= 12) {
                mentionCell.textContent = 'Assez Bien';
                mentionCell.className = 'badge-mention bien';
            } else if (moyenne >= 10) {
                mentionCell.textContent = 'Passable';
                mentionCell.className = 'badge-mention passable';
            } else {
                mentionCell.textContent = 'Insuffisant';
                mentionCell.className = 'badge-mention incomplet';
            }
        }
    }

    // Validation des notes (0-20)
    document.querySelectorAll('.note-input').forEach(function (input) {
        input.addEventListener('change', function () {
            let val = parseFloat(this.value);
            if (!isNaN(val)) {
                if (val < 0) this.value = 0;
                if (val > 20) this.value = 20;
            }
        });
    });

    // Toggle mot de passe (page login)
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('.material-symbols-outlined').textContent =
                type === 'password' ? 'visibility' : 'visibility_off';
        });
    }

    // Gestion du thème sombre (Dark Mode)
    const themeToggleBtn = document.getElementById('themeToggle');
    if (themeToggleBtn) {
        const iconLight = themeToggleBtn.querySelector('.theme-icon-light');
        const iconDark = themeToggleBtn.querySelector('.theme-icon-dark');

        // Mettre à jour l'icône selon le thème actuel
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        if (currentTheme === 'dark') {
            iconDark.classList.add('d-none');
            iconLight.classList.remove('d-none');
        } else {
            iconLight.classList.add('d-none');
            iconDark.classList.remove('d-none');
        }

        themeToggleBtn.addEventListener('click', function () {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            if (newTheme === 'dark') {
                iconDark.classList.add('d-none');
                iconLight.classList.remove('d-none');
            } else {
                iconLight.classList.add('d-none');
                iconDark.classList.remove('d-none');
            }
        });
    }
});
