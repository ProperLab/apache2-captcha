var refreshButton = document.getElementById("refresh-captcha");
var captchaImage = document.getElementById("image-captcha");

refreshButton.onclick = function (event) {
  event.preventDefault();
  captchaImage.src = "captcha/image.php?" + Date.now();
};

function verify() {
  document.getElementById("message").innerText = "Loading...";

  var params = "submit=&token=" + document.getElementById("token").value;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "", true);

  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (xhr.responseText == "true") {
        document.getElementById("message").innerText = "Redirecting...";
        location = document.getElementById("target").value;
      } else {
        document.getElementById("message").innerText = xhr.responseText;
      }
    }
  };

  xhr.send(params);
}
