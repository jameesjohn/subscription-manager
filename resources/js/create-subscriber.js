(function createSubscriber() {
    const createSection = document.querySelector(".create-subscriber");

    if (!createSection) {
        return
    }
    const form = createSection.querySelector('form');
    form.addEventListener('submit', handleFormSubmit);

    /**
     *
     * @param event {Event}
     */
    function handleFormSubmit(event) {
        event.preventDefault();

        const formBtn = form.querySelector('button');
        const formData = new FormData(form);
        const emailInput = form.querySelector("#email");
        const errorSpan = form.querySelector('span.errors')

        formBtn.textContent = 'Processing...';
        axios.post('/api/subscribers', formData)
            .then((res) => {
                alert('New Subscriber Added!');
                location.href = "/subscribers";
            })
            .catch(({response}) => {
                if (response.status === 422) {
                    emailInput.classList.add('has-error');
                    errorSpan.textContent = response.data.errors.email;
                } else {
                    alert(response.data.message);
                }
            })
            .finally(() => {
                formBtn.textContent = 'Submit';
            });
    }

})();
