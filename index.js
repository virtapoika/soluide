
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
          $("#regSuc").show();
          localStorage.setItem("token", obj[1]);
          $("#loginform").hide();
          $("#registerform").hide();
          $("#logged").show();



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
          $("#notlogged").hide();
          $("#loginform").hide();

          $("#logged").show();
          listProjects();

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
          $("#notlogged").hide();
          $("#logged").show();



        }
    }
  });
}
function createProject() {
  var token = localStorage.getItem("token");
  var name = $("#projectname").val();

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'createProject', token: token, name: name},
    success: function(data) {
        var obj= JSON.parse(data);
        console.log(obj);
        if(obj[0] == false)
        {
          console.log(obj[1]);
        }
        else {
          alert("it works");
          console.log(obj[1]);

        }
    }
  });
}

function listProjects() {
  var token = localStorage.getItem("token");
  var name = $("#projectname").val();

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'listProjects', token: token},
    success: function(data) {
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {
          $("#notlogged").show();
          $("#logged").hide();
        }
        else {
          console.log(obj);
          var x = 0;
          $("#projects").empty();
          while(x < objects.length)
          {
            var name = objects[x].name;
            $("#projects").append('<a style="color: #fff !important;cursor: pointer;"  onClick="javascript:openProject(\'' + name + '\')">'+name+'<a/><br/>');



            x++;
          }

        }
    }
  });
}


function openProject(name)
{
  location.replace("editor.html?project=" +name);


}
function openRegister()
{
  $("#loginform").hide();
  $("#registerform").show();

}
function unopenRegister()
{
  $("#loginform").show();
  $("#registerform").hide();
}


$(document).ready(function() {
  //postItem();
  validateToken();
  listProjects();
});
