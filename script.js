document.addEventListener('DOMContentLoaded', function() {
    // Function to open the popup and set the URLs
    function openPopup(postUrl) {
        var sharePopup = document.querySelector('.share-hit-popup');
        if (sharePopup) {
            var buttons = sharePopup.querySelectorAll('.share-hit-button');
            buttons.forEach(function(button) {
                var shareUrl = postUrl;
                if (button.classList.contains('share-facebook')) {
                    button.href = 'https://facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl);
                } else if (button.classList.contains('share-twitter')) {
                    button.href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(shareUrl);
                } else if (button.classList.contains('share-email')) {
                    button.href = 'mailto:?subject=Check this out&body=' + encodeURIComponent(shareUrl);
                }
                button.setAttribute('target', '_blank'); // Ensure the link opens in a new tab
                button.setAttribute('rel', 'noopener noreferrer'); // Add security attributes
            });
            sharePopup.style.display = 'block';
        }
    }

    // Function to close the popup
    function closePopup() {
        var sharePopup = document.querySelector('.share-hit-popup');
        if (sharePopup) {
            sharePopup.style.display = 'none';
        }
    }

    // Find all share buttons and set their data-post-url attribute
    function setShareButtonUrls() {
        document.querySelectorAll('.share-blog-hit').forEach(function(button) {
            var parentItem = button.closest('li'); // Assumes the permalink is in a sibling or parent element
            if (parentItem) {
                var permalink = parentItem.querySelector('a[href]').getAttribute('href'); // Get the permalink
                button.setAttribute('data-post-url', permalink); // Set data-post-url
            }
        });
    }

    // Initial setup
    setShareButtonUrls();

    // Open the popup when an element with class `share-blog-hit` is clicked
    document.addEventListener('click', function(event) {
        var target = event.target.closest('.share-blog-hit');
        if (target) {
            var postUrl = target.getAttribute('data-post-url') || window.location.href; // Use data-post-url if present
            openPopup(postUrl);
        }
    });

    // Hide the popup when the close button is clicked
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('share-hit-popup-close')) {
            closePopup();
        }
    });

    // Hide the popup when clicking outside of the popup content
    window.addEventListener('click', function(event) {
        var sharePopup = document.querySelector('.share-hit-popup');
        if (sharePopup && event.target === sharePopup) {
            closePopup();
        }
    });
});
