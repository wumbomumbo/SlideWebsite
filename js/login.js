function login() {
    const username = document.getElementById("inputUsername").value;
    const password = document.getElementById("inputPassword").value;

    const apiUrl = "/api/login";

    fetch(apiUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username, password }),
    })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log("Login response:", data);
        if (data.success) {
          // Successful login, redirect to the home page
          window.location.href = "/home/";
        } else {
          // Display error message
          showLoginError(data.message);
        }
      })
      .catch(error => {
        console.error("Error during login:", error.message);
        showErrorBanner("error while trying to login. please try again.");
      });
  }

  function showLoginError(message) {
    const errorBanner = document.getElementById("errorBanner");
    errorBanner.innerHTML = message;
  }