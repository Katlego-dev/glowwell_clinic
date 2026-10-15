//Client-side Form Validation
document.addEventListener('DOMContentLoaded', function () {
    const bookingForm = document.getElementById('bookingForm');
    const dateInput = document.getElementById('appointment_date');

// Prevent selecting past dates in the date picker
if (dateInput) {
    const today = new Date().toISOString.split('T')[0];
    dateInput.setAttribute('min, today');

}

// Form submission validation
if (bookingForm) {
    bookingForm.addEventListener('submit', function (e) {
        const service = document.getElementById('service_id').value;
        const staff = document.getElementById('staff_id').value;
        const selectedDate = dateInput.value;
        const selectedTime = document.getElementById('appointment_time').value;

        //Check empty fields 
        if (!service || !staff || !selectionDate || !selectedTime) {
            e.preventDefault();
            alert('Please fill in all required fields before submmiting.');
            return false;
        }

        //Verify selected date is not in the past
        const todayDate = new Date();
        todayDate.setHours(0, 0, 0, 0);
        const chosenDate = new Date(selectedDate);

        if (chosenDate < todayDate) {
            e.preventDefault();
            alert('You cannot book an appointment for a past date.');
            return false;
        }
    });
}
});