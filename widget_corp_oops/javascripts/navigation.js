document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".subject-toggle");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", function () {
            const targetId = this.getAttribute("data-target");
            const targetList = document.getElementById(targetId);

            if (!targetList) return;

            // Collapse other open lists
            document.querySelectorAll(".pages.show").forEach(el => {
                if (el !== targetList) {
                    el.classList.remove("show");
                    const btn = document.querySelector(`[data-target="${el.id}"]`);
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                    if (btn?.parentElement) btn.parentElement.classList.remove('active');
                }
            });

            // Toggle current list
            const willShow = !targetList.classList.contains("show");
            targetList.classList.toggle("show", willShow);
            this.setAttribute('aria-expanded', willShow ? 'true' : 'false');

            const subjectItem = this.parentElement;
            if (subjectItem) {
                if (willShow) subjectItem.classList.add('active');
                else subjectItem.classList.remove('active');
            }
        });
    });
});
