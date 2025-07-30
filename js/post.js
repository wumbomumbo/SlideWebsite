document.querySelector('.post-text-area').addEventListener('input', function () {
    var charCount = document.getElementById('charCount');
    charCount.innerText = 255 - this.value.length;
    charCount.classList.toggle('text-danger', this.value.length > 235);
});

async function submitPost() {
    let postContent = document.getElementById('post_content').value;
    let postImage = document.getElementById('post_image').files[0];
    let postVideo = document.getElementById('post_video').files[0];

    if (!postContent.trim()) {
        showErrorBanner('please enter some text before posting.');
        return;
    }

    try {
        showLoadingSymbol();

        let formData = new FormData();
        formData.append('content', postContent);
        formData.append('image', postImage);
        formData.append('video', postVideo);

        let response = await fetch('/api/post', {
            method: 'POST',
            body: formData,
        });

        if (response.ok) {
            let json = await response.json();

            if (json.success) {
                showSuccessBanner('your post was posted successfully!');
                setTimeout(function(){
                    window.location.href = '/posts/?id=' + json.postid;
                }, 2000);
            } else {
                showErrorBanner('sorry, looks like something went wrong on our end, please try again.');
            }
        } else {
            showErrorBanner('sorry, looks like something went wrong on our end, please try again.');
        }
    } catch (error) {
        showErrorBanner('sorry, an error occurred while posting your post, please try again.');
    } finally {
        hideLoadingSymbol();
    }
}

function showLoadingSymbol() {
    document.querySelector('.loader').style.display = 'block';
}

function hideLoadingSymbol() {
    document.querySelector('.loader').style.display = 'none';
}