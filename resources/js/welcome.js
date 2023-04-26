(function welcomePage() {
    const welcomeBody = document.querySelector('body.welcome');
    if (!welcomeBody) {
        return;
    }

    const form = welcomeBody.querySelector('form');
    form.addEventListener('submit', handleFormSubmitted);


    /**
     *
     * @param event {Event}
     */
    function handleFormSubmitted(event) {
        event.preventDefault();
        const inputField = form.querySelector('input#key');
        const submitButton = form.querySelector('button.submit');
        const errorSpan = form.querySelector('span.errors')

        const key = inputField.value;
        if (!key.trim()) {
            alert("API Key is required")
        }

        inputField.classList.remove('has-error');
        submitButton.disabled = true;
        submitButton.textContent = "Processing...";

        axios.post('/api/keys', {key})
            .then((response) => {
                location.href = "/";
            }).catch((err) => {
            if (err.response.status === 422) {
                //     Validation Error
                errorSpan.textContent = err.response.data.errors.key;
                inputField.classList.add('has-error');
            }
        }).finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = "Continue";
        })
    }
})()
