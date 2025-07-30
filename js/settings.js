$(document).ready(function () {
    $("#saveProfileSettings").click(function () {
        var displayName = $("#firstName").val().trim();
        var userTag = $("#userTag").val().trim();

        if (!displayName || !userTag) {
            showErrorBanner("display name and user tag are required.");
            return;
        }

        if (displayName.length > 30 || userTag.length > 15) {
            showErrorBanner("display name cant exceed 30 charcters and user tag cannot exceed 15 characters.");
            return;
        }

        $.ajax({
            url: '/api/profilesettings',
            type: 'POST',
            data: {
                displayName: displayName,
                userTag: userTag
            },
            success: function (response) {
                showSuccessBanner("profile settings saved successfully.");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                showErrorBanner("error saving profile settings.");
            }
        });
    });

    $("#savePasswordEmail").click(function () {
        var email = $("#email").val().trim();
        var password = $("#password").val().trim();

        if (!email || !password) {
            showErrorBanner("email and password are required.");
            return;
        }

        if (password.length < 8) {
            showErrorBanner("password must be at least 8 characters long.");
            return;
        }

        $.ajax({
            url: '/api/passwordemail',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                showSuccessBanner("password and email saved successfully.");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                showErrorBanner("error saving password and email.");
            }
        });
    });

    $("#saveTheme").click(function () {
        var selectedTheme = $("input[name='theme']:checked").val();

        $.ajax({
            url: '/api/themeselect',
            type: 'POST',
            data: {
                theme: selectedTheme
            },
            success: function (response) {
                showSuccessBanner("theme saved successfully.");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                showErrorBanner("error saving theme.");
            }
        });
    });

    $("#confirmDeleteBtn").click(function () {
        // WIP
    });
});