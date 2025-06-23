document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll("#tabs-tab button");
    const tabContents = document.querySelectorAll("#tabs-tabContent > div");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            // Remove active classes from all tabs
            tabs.forEach((t) => {
                t.classList.remove(
                    "border-primary-600",
                    "text-primary-600",
                    "dark:border-primary-500",
                    "dark:text-primary-500"
                );
                t.classList.add(
                    "border-transparent",
                    "hover:text-gray-600",
                    "hover:border-gray-300",
                    "dark:hover:text-gray-300"
                );
                t.setAttribute("aria-selected", "false");
            });

            // Hide all tab contents
            tabContents.forEach((content) => {
                content.classList.add("hidden");
            });

            // Activate clicked tab
            tab.classList.add(
                "border-primary-600",
                "text-primary-600",
                "dark:border-primary-500",
                "dark:text-primary-500"
            );
            tab.setAttribute("aria-selected", "true");

            // Show corresponding tab content
            const target = tab.getAttribute("data-tabs-target");
            const targetContent = document.querySelector(target);
            if (targetContent) {
                targetContent.classList.remove("hidden");
            }
        });
    });
});
