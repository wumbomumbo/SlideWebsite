let notificationpage = 1;
const notificationsPerPage = 10;
let isLoading = false;

function fetchNotifications() {
    isLoading = true;
    fetch(`/api/notifications?page=${notificationpage}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }
            return response.json();
        })
        .then(data => {
            const notificationList = document.getElementById('notificationList');

            if (data.notifications.length > 0 || data.new_notification_count > 0) {
                data.notifications.forEach(notification => {
                    const listItem = document.createElement('a');
                    listItem.href = notification.link;
                    listItem.classList.add('list-group-item');

                    const mediaDiv = document.createElement('div');
                    mediaDiv.classList.add('media');

                    const profileDiv = document.createElement('div');
                    profileDiv.classList.add('media-left', 'notification-profile');

                    const profileImg = document.createElement('img');
                    profileImg.src = notification.profileImg;
                    profileImg.alt = 'User Profile';
                    profileImg.classList.add('profile-picture');

                    profileDiv.appendChild(profileImg);

                    const bodyDiv = document.createElement('div');
                    bodyDiv.classList.add('media-body');

                    const sender = document.createElement('a');
                    sender.classList.add('strong', 'highlightonlya');
                    sender.href = notification.senderLink;
                    sender.textContent = notification.sender;

                    const text = document.createElement('p');
                    text.textContent = notification.message;
                    text.classList.add('notification-page-text');

                    const postDate = new Date(notification.created_at * 1000);
                    const formattedDate = formatPostDate(postDate);

                    const date = document.createElement('small'); 
                    date.textContent = formattedDate;

                    bodyDiv.appendChild(sender);
                    bodyDiv.appendChild(document.createTextNode(notification.action));
                    bodyDiv.appendChild(text);
                    bodyDiv.appendChild(date);

                    mediaDiv.appendChild(profileDiv);
                    mediaDiv.appendChild(bodyDiv);

                    listItem.appendChild(mediaDiv);
                    notificationList.appendChild(listItem);
                });

                notificationpage++;
            }

            isLoading = false;
        })
        .catch(error => {
            showErrorBanner('something went wrong while fetching your notifications.');
            isLoading = false;
        });
}

function checkForNewNotifications() {
    setInterval(() => {
        if (!isLoading) {
            fetchNotifications();
        }
    }, 5000);
}

function handleScroll() {
    if (isLoading) return; // Avoid multiple simultaneous requests
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    if (scrollTop + windowHeight >= documentHeight - 50) {
        fetchNotifications();
    }
}

// Add scroll event listener
window.addEventListener('scroll', handleScroll);

// Initial fetch of notifications
fetchNotifications();

// Start periodic check for new notifications
checkForNewNotifications();