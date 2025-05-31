const notifyContainer = document.querySelector('#notifications-container');
const poll_interval = 4000; // 10 seconds

function setNotificationToRead(el) {
        let id = el.dataset.id
        if (el.dataset.read != 1) {
            let data = new FormData
            data.append('action', 'set_read')
            data.append('id', id)
            fetch("<?= url('actions/notifications.php') ?>", {
                    method: "POST",
                    body: data
                }).then(res => res.json())
                .then(js => {
                    el.dataset.read = 1
                })
            return true
        } else {
            return false
        }
    }

function pollNotifications($data) {
    fetch('http://localhost:8000/actions/notifications.php')
        .then(response => response.json())
        .then(data => {
            let redPin = data.filter(value => {
                return +value.read_state === 0
            })

            console.log('pin', redPin)

            if (redPin.length > 0) {
                document.querySelector('#redPin').classList.remove('hidden')
            }
            console.log(data)
            $data.notifications = data





        });
}
var m;
document.addEventListener('alpine:init', () => {
    Alpine.data('body', () => {
        return {
            notifications: [],
            get unreadNotifications() {
                return this.notifications?.filter(n => n.read_state != 1) ?? []
            },
            setRead: (el, data) => {
                if (setNotificationToRead(el)) {
                    data.notifications = data.notifications.map(v => {
                        if (v.id == el.dataset.id) {
                            v.read_state = 1
                        }

                        return v
                    })
                }

                let redPin = data.notifications.filter(value => {
                    return +value.read_state === 0
                })


                if (redPin.length <= 0) {
                    document.querySelector('#redPin').classList.add('hidden')
                }
            },
            init() {
                m = setInterval(() => pollNotifications(this), poll_interval);
            }
        }
    })
})