// ── Task 4: Client-side validation ──────────────────────────

// Validate register form
function validateRegister() {
    var name  = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var pass  = document.getElementById('password').value;
    var pass2 = document.getElementById('confirm_password').value;

    if (name === '') { alert('Name is required.'); return false; }
    if (email === '') { alert('Email is required.'); return false; }
    if (pass.length < 6) { alert('Password must be at least 6 characters.'); return false; }
    if (pass !== pass2) { alert('Passwords do not match.'); return false; }
    return true;
}

// Validate login form
function validateLogin() {
    var email = document.getElementById('email').value.trim();
    var pass  = document.getElementById('password').value;
    if (email === '') { alert('Email is required.'); return false; }
    if (pass === '') { alert('Password is required.'); return false; }
    return true;
}

// Validate service form + file type/size
function validateService() {
    var title = document.getElementById('title').value.trim();
    var desc  = document.getElementById('description').value.trim();
    var price = document.getElementById('price').value;
    var file  = document.getElementById('image').files[0];

    if (title === '') { alert('Title is required.'); return false; }
    if (desc === '') { alert('Description is required.'); return false; }
    if (price === '' || isNaN(price) || price < 0) { alert('Enter a valid price.'); return false; }

    if (file) {
        var allowed = ['image/jpeg', 'image/png'];
        if (!allowed.includes(file.type)) { alert('Only JPG or PNG images allowed.'); return false; }
        if (file.size > 2 * 1024 * 1024) { alert('Image must be under 2MB.'); return false; }
    }
    return true;
}

// Image preview
function previewImage(input) {
    var preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}


// ── Task 5: AJAX Live Search ─────────────────────────────────

function liveSearch() {
    var query = document.getElementById('searchInput').value.trim();
    var resultsDiv = document.getElementById('searchResults');

    if (query.length < 2) {
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
        return;
    }

    // fetch() AJAX call
    fetch('/campus_hub/ajax_search.php?q=' + encodeURIComponent(query))
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.length === 0) {
                resultsDiv.innerHTML = '<a href="#">No results found</a>';
            } else {
                var html = '';
                data.forEach(function(item) {
                    html += '<a href="/campus_hub/services/view.php?id=' + item.id + '">'
                          + item.title + ' — <strong>RM ' + item.price + '</strong>'
                          + ' <small>by ' + item.owner + '</small></a>';
                });
                resultsDiv.innerHTML = html;
            }
            resultsDiv.style.display = 'block';
        });
}

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    var box = document.getElementById('searchResults');
    if (box && !box.contains(e.target) && e.target.id !== 'searchInput') {
        box.style.display = 'none';
    }
});
