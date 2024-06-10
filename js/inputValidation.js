window.onload = function() {
    var textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.oninput = function() {
            if (this.value.length > 255) {
                this.setCustomValidity('Input must be less than 255 characters');
            } else {
                this.setCustomValidity('');
            }
        };
    });
    // also check for input length on form submit
    var inputs = document.querySelectorAll('input');
    inputs.forEach(function(input) {
        input.oninput = function() {
            if (this.value.length > 50) {
                this.setCustomValidity('Input must be less than 50 characters');
            } else {
                this.setCustomValidity('');
            }
        };
    });
};