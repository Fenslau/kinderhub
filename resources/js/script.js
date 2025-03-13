
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    const containers = document.querySelectorAll('.card-body');

    containers.forEach(container => {
        const textBlock = container.querySelector('.card-text');

        if (textBlock) {
            const containerHeight = container.clientHeight;
            const textBlockHeight = textBlock.scrollHeight;

            if (textBlockHeight > containerHeight) {
                textBlock.classList.add('text-overflow-container');
            }
        }
    });
});