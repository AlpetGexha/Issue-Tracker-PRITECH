{{-- Toast Notifications Container --}}
<div id="toast-container" 
     class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-sm"
     style="pointer-events: none;">
</div>

{{-- Toast Notification Template (hidden) --}}
<template id="toast-template">
    <div class="toast-notification flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 transform transition-all duration-300 ease-in-out opacity-0 translate-x-full"
         style="pointer-events: auto;">
        {{-- Icon Container --}}
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
            {{-- Success Icon --}}
            <svg class="toast-icon toast-icon-success w-5 h-5 text-green-500 dark:text-green-200 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            
            {{-- Error Icon --}}
            <svg class="toast-icon toast-icon-error w-5 h-5 text-red-500 dark:text-red-200 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
            
            {{-- Warning Icon --}}
            <svg class="toast-icon toast-icon-warning w-5 h-5 text-orange-500 dark:text-orange-200 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm0-4a1 1 0 0 1-1-1V6a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1Z"/>
            </svg>
            
            {{-- Info Icon --}}
            <svg class="toast-icon toast-icon-info w-5 h-5 text-blue-500 dark:text-blue-200 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
        </div>
        
        {{-- Message --}}
        <div class="ms-3 text-sm font-normal toast-message"></div>
        
        {{-- Close Button --}}
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700 toast-close" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</template>

<script>
document.addEventListener('livewire:init', function () {
    // Listen for notify events from Livewire
    Livewire.on('notify', (data) => {
        showToast(data.message, data.type);
    });
});

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const template = document.getElementById('toast-template');
    
    if (!container || !template) return;
    
    // Clone the template
    const toast = template.content.cloneNode(true);
    const toastElement = toast.querySelector('.toast-notification');
    
    // Set the message
    toast.querySelector('.toast-message').textContent = message;
    
    // Hide all icons first
    toast.querySelectorAll('.toast-icon').forEach(icon => icon.classList.add('hidden'));
    
    // Show the correct icon based on type
    const iconClass = `toast-icon-${type}`;
    const icon = toast.querySelector(`.${iconClass}`);
    if (icon) {
        icon.classList.remove('hidden');
    }
    
    // Add type-specific styling
    switch(type) {
        case 'success':
            toastElement.classList.add('border-l-4', 'border-green-500');
            break;
        case 'error':
            toastElement.classList.add('border-l-4', 'border-red-500');
            break;
        case 'warning':
            toastElement.classList.add('border-l-4', 'border-orange-500');
            break;
        case 'info':
            toastElement.classList.add('border-l-4', 'border-blue-500');
            break;
    }
    
    // Add close button functionality
    const closeButton = toast.querySelector('.toast-close');
    closeButton.addEventListener('click', () => {
        hideToast(toastElement);
    });
    
    // Append to container
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toastElement.classList.remove('opacity-0', 'translate-x-full');
        toastElement.classList.add('opacity-100', 'translate-x-0');
    }, 10);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (toastElement.parentNode) {
            hideToast(toastElement);
        }
    }, 5000);
}

function hideToast(toastElement) {
    toastElement.classList.remove('opacity-100', 'translate-x-0');
    toastElement.classList.add('opacity-0', 'translate-x-full');
    
    setTimeout(() => {
        if (toastElement.parentNode) {
            toastElement.parentNode.removeChild(toastElement);
        }
    }, 300);
}
</script>
