document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("form-modal");
    const openBtn = document.getElementById("open-form");
    const closeBtn = document.querySelector(".close");
    const steps = document.querySelectorAll(".step");
    const nextBtns = document.querySelectorAll(".next");
    const prevBtns = document.querySelectorAll(".prev");

    let currentStep = 0;

    // Ouvrir la modale
    openBtn.addEventListener("click", function() {
        modal.style.display = "flex";
    });

    // Fermer la modale
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Gérer les étapes du formulaire
    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle("active", index === stepIndex);
        });
    }

    nextBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.checkbox-label input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        // Ajouter la classe .checked si la case est déjà cochée au chargement
        if (checkbox.checked) {
            checkbox.closest('.checkbox-label').classList.add('checked');
        }

        // Écouter les changements sur la case à cocher
        checkbox.addEventListener('change', function () {
            const label = checkbox.closest('.checkbox-label');
            if (checkbox.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
    });
});
    // Afficher la première étape au chargement
    showStep(currentStep);
});

