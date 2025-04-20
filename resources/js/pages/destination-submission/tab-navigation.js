export default function initTabNavigation() {
    // Tab navigation
    const tabs = document.querySelectorAll('.tab-content');
    const steps = document.querySelectorAll('.step');
    const nextButtons = document.querySelectorAll('.next-tab');
    const prevButtons = document.querySelectorAll('.prev-tab');

    // Tab switching function
    function switchTab(fromTab, toTab) {
        // Validate current tab before proceeding
        if (fromTab < toTab) {
            if (fromTab === 1 && !window.validateBasicInfo()) return;
            if (fromTab === 2 && !window.validateLocation()) return;
        }

        // Hide all tabs and remove active class from steps
        tabs.forEach(tab => tab.classList.remove('active'));
        steps.forEach(step => step.classList.remove('active'));

        // Show selected tab and mark step as active
        document.getElementById(`tab-${toTab}`).classList.add('active');
        steps.forEach(step => {
            if (parseInt(step.getAttribute('data-step')) === toTab) {
                step.classList.add('active');
            }
            if (parseInt(step.getAttribute('data-step')) < toTab) {
                step.classList.add('completed');
            } else {
                step.classList.remove('completed');
            }
        });

        // Scroll to top of form
        document.querySelector('.step-progress').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Next tab buttons
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const nextTab = parseInt(this.getAttribute('data-next'));
            const currentTab = nextTab - 1;
            switchTab(currentTab, nextTab);
        });
    });

    // Previous tab buttons
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const prevTab = parseInt(this.getAttribute('data-prev'));
            const currentTab = prevTab + 1;
            switchTab(currentTab, prevTab);
        });
    });

    // Handle existing errors - automatically switch to tab with error
    const errors = document.querySelectorAll('.text-red-500.text-xs.mt-1');
    if (errors.length > 0) {
        // Determine which tab to open based on error fields
        const basicInfoFields = ['place_name', 'category_id', 'time_minutes', 'best_visit_time', 'description'];
        const locationFields = ['latitude', 'longitude', 'administrative_area', 'province'];
        const photoFields = ['images'];

        let tabToOpen = 1;

        errors.forEach(error => {
            const fieldName = error.previousElementSibling?.getAttribute('name');
            if (fieldName) {
                if (locationFields.some(field => fieldName.includes(field))) {
                    tabToOpen = 2;
                } else if (photoFields.some(field => fieldName.includes(field))) {
                    tabToOpen = 3;
                }
            }
        });

        switchTab(1, tabToOpen);
    }

    // Expose switchTab function globally
    window.switchTab = switchTab;
}
