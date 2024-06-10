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
};