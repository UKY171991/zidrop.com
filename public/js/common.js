// Phone Field Validation

// $(document).ready(function () {
//     $('#phonenumber, #phone, #phoneNumber, #sender_mobile, #recivier_mobile').on('input', function () {
//         let value = this.value.replace(/\D/g, ''); // Remove non-numeric characters

//         // Ensure the first digit is '0'
//         if (value.length > 0 && value.charAt(0) !== '0') {
//             $(this).val(''); // Clear input
//             return;
//         }

//         // Limit length to 11 digits (max format: xxxx xxx xxxx)
//         if (value.length > 11) {
//             value = value.substring(0, 11);
//         }

//         // Apply formatting: xxxx xxx xxxx
//         if (value.length > 4) {
//             value = value.substring(0, 4) + ' ' + value.substring(4);
//         }
//         if (value.length > 8) {
//             value = value.substring(0, 8) + ' ' + value.substring(8, 12);
//         }

//         $(this).val(value);
//     });
// });




$(document).ready(function () {
    $('#phonenumber, #phone, #phoneNumber, #sender_mobile, #recivier_mobile').on('input', function () {
        let value = this.value.replace(/\D/g, ''); // Remove non-numeric characters

        // Ensure the first digit is '0'
        if (value.length > 0 && value.charAt(0) !== '0') {
            $(this).val(''); // Clear input if it doesn't start with '0'
            return;
        }

        // Limit length strictly to 11 digits
        if (value.length > 11) {
            value = value.substring(0, 11);
        }

        // Apply formatting: xxxx xxx xxxx
        if (value.length > 4) {
            value = value.substring(0, 4) + ' ' + value.substring(4);
        }
        if (value.length > 8) {
            value = value.substring(0, 8) + ' ' + value.substring(8, 12);
        }

        $(this).val(value);

        // Remove existing error message
        $(this).closest('.form-group').find('.error-message').remove();

        // Show error if number is not exactly 11 digits
        if (value.length !== 13) {
            $(this).after('<p class="error-message" style="color: red; font-size: 12px;"></p>');
            // $(this).after('<p class="error-message" style="color: red; font-size: 12px;">Phone number must be exactly 11 digits.</p>');
        }
    });
});