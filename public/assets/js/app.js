import './bootstrap';

// Add form validation feedback and accessibility enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add form validation feedback like in the original JS
    document.addEventListener('input', function(e) {
        if (e.target.hasAttribute('required') && e.target.value.trim()) {
            e.target.style.borderColor = '#10b981'; // green
            e.target.style.backgroundColor = '#f0fdf4'; // light green
        } else if (e.target.hasAttribute('required')) {
            e.target.style.borderColor = '';
            e.target.style.backgroundColor = '';
        }
    });

    // Add focus enhancements for accessibility (from original JS)
    document.querySelectorAll('input, textarea, select').forEach(function(field) {
        field.addEventListener('focus', function() {
            field.style.transform = 'scale(1.02)';
            field.style.boxShadow = '0 0 0 4px rgba(59, 130, 246, 0.3)';
        });

        field.addEventListener('blur', function() {
            field.style.transform = '';
            field.style.boxShadow = '';
        });
    });

    // Listen for Livewire events
    document.addEventListener('livewire:initialized', function() {
        // Handle submission events
        Livewire.on('submissionStarted', function() {
            console.log('Form submission started...');
        });

        Livewire.on('submissionCompleted', function(data) {
            console.log('Form submitted successfully! Reference ID:', data.referenceId);
            
            // Auto-hide success message after 5 seconds like in original JS
            setTimeout(function() {
                Livewire.dispatch('hideSuccess');
            }, 5000);
        });

        Livewire.on('submissionError', function() {
            console.log('Form submission error occurred');
        });
    });
});
