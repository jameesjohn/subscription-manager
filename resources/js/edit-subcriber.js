(function editSubscriber(){
    const editSection = document.querySelector(".edit-subscriber");

    if (!editSection) {
        return
    }
    const form = editSection.querySelector('form');
    form.addEventListener('submit', handleFormSubmit);

    /**
     *
     * @param event {Event}
     */
    function handleFormSubmit(event) {
        event.preventDefault();

        const subscriberId = form.getAttribute('x-subscriberId');
        const formBtn = form.querySelector('button');

        const formData = new FormData(form);
        const data =  {
            name: formData.get('name'),
            country: formData.get('country')
        }
        formBtn.textContent = 'Processing...'

        axios.put(`/api/subscribers/${subscriberId}`, data)
            .then(res => {
                alert("Subscriber Updated");
                location.href = "/subscribers";
            })
            .catch(({response}) => {
                alert(response.data.message);
            }).finally(() => {
                formBtn.textContent = 'Update'
        })

    }
})()
