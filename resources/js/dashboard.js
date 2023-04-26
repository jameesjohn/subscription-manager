(function () {
    const dashboardBody = document.querySelector('.dashboard');
    if (!dashboardBody) {
        return;
    }
    const tableElement = dashboardBody.querySelector('table');
    tableElement.addEventListener('click', (evt) => {
        console.log({target: evt.target});
        console.log({currentTarget: evt.currentTarget});
        const target = evt.target

        const deleteBtnClicked = target.nodeName === "BUTTON" && target.classList.contains('delete');
        if (!deleteBtnClicked) {
            console.log('something else got clicked');
            return;
        }

        handleDeleteBtnClick(target);

    });

    const processedTable = loadDataTable();
    let paginationNext = '';
    let paginationPrev = '';


    function loadDataTable() {
        const table = $('.subscribers').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/subscribers',
                data: function (data, props) {
                    if (props.json) {
                        const oldData = props.json;
                        const newStart = Number(data.start);
                        const oldStart = Number(oldData.start);

                        const isNext = (
                            Number(data.length) === Number(oldData.length) &&
                            newStart > oldStart &&
                            newStart !== 0
                        );
                        const isPrev = (
                            Number(data.length) === Number(oldData.length) &&
                            oldStart > newStart &&
                            newStart !== 0
                        );

                        return $.extend({}, data, {
                            previous: props.json.pagination.prev,
                            next: props.json.pagination.next,
                            isNext: Number(isNext),
                            isPrev: Number(isPrev)
                        })
                    }

                    return $.extend({}, data, {
                        isNext: 0,
                        isPrev: 0
                    });
                },
            },
            pagingType: 'simple',
            columns: [
                {
                    data: 'email'
                },
                {
                    data: 'name'
                },
                {
                    data: 'country'
                },
                {
                    data: 'date_subscribed'
                },
                {
                    data: 'time_subscribed'
                },
                {
                    data: null,
                    sortable: false,
                    render: function (data, type, full) {
                        return `
                        <button data-subscriber="${data.id}" class="delete">Delete</button>
                        <a href="/subscribers/${data.id}/edit" class="btn edit">Edit</a>
                    `;
                    }
                }
            ]
        });
        return table;
    }

    /**
     *
     * @param btn {HTMLButtonElement}
     */
    function handleDeleteBtnClick(btn) {
        const subscriberId = btn.getAttribute('data-subscriber');
        btn.textContent = "..."
        btn.disabled = true;
        axios.delete(`/api/subscribers/${subscriberId}`)
            .then(res => {
                alert('Subscriber deleted')
                processedTable.ajax.reload();
            })
            .catch(({response}) => {
                alert(response.data.message);
            })
            .finally(() => {
                btn.textContent = "Delete"
            })
    }
})();

