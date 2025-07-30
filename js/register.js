function register() {
  const displayname = document.getElementById("inputDisplayName").value;
  const username = document.getElementById("inputUsername").value;
  const email = document.getElementById("inputEmail").value;
  const password = document.getElementById("inputPassword").value;
  const confirmPassword = document.getElementById("inputConfirmPassword").value;

  const apiUrl = "/api/register";

  if (password !== confirmPassword) {
      showRegisterError("passwords do not match.");
      return;
  }

  fetch(apiUrl, {
      method: "POST",
      headers: {
          "Content-Type": "application/json",
      },
      body: JSON.stringify({ displayname, username, email, password }),
  })
      .then(response => {
          if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
      })
      .then(data => {
          console.log("Registration response:", data);
          if (data.success) {
              window.location.href = "/login/"; 
          } else {
              showRegisterError(data.message);
          }
      })
      .catch(error => {
          console.error("Error during registration:", error.message);
          showErrorBanner("error while trying to register. please try again.");
      });
}

function showRegisterError(message) {
  const errorBanner = document.getElementById("errorBanner");
  errorBanner.innerHTML = message;
}
