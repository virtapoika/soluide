
function register() {
  var email = $("#regemail").val();
  var password1 = $("#regpassword1").val();
  var password2 = $("#regpassword2").val();

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/auth.php',
    method: 'POST',
    data: {action: 'register', email: email, password1: password1, password2: password2 },
    success: function(data) {
        var obj= JSON.parse(data);
        console.log(obj);

        if(obj[0] == false)
        {
          alert(obj[1]);
        }
        else {
          localStorage.setItem("token", obj[1]);
          location.replace("editor.html");
        }
    }
  });
}
function logout()
{
  localStorage.setItem("token", "");
  $("#notlogged").show();
  $("#logged").hide();

}
function login() {
  var logemail = $("#logemail").val();
  var logpassword = $("#logpassword").val();


  $.ajax( {
    url: 'http://soluide.sovellus.design/api/auth.php',
    method: 'POST',
    data: {action: 'login', email: logemail, password: logpassword},
    success: function(data) {
        var obj= JSON.parse(data);
        console.log(obj);
        if(obj[0] == false)
        {
          alert(obj[1]);
          $("#notlogged").show();
          $("#logged").hide();

        }
        else {
          localStorage.setItem("token", obj[1]);

          location.replace("editor.html");

        }
    }
  });
}
function validateToken() {
  var token = localStorage.getItem("token");


  $.ajax( {
    url: 'http://soluide.sovellus.design/api/auth.php',
    method: 'POST',
    data: {action: 'validateToken', token: token},
    success: function(data) {
        var obj= JSON.parse(data);
        console.log(obj);
        if(obj[0] == false)
        {
          $("#notlogged").show();
          $("#logged").hide();
         }
        else {
          location.replace("editor.html");
          location.replace("editor.html");
          location.replace("editor.html");

        }
    }
  });
}


function openRegister() {
  $("#loginform").hide();
  $("#registerform").show();

}
function unopenRegister() {
  $("#loginform").show();
  $("#registerform").hide();
}


$(document).ready(function() {
  validateToken();
});
