var project;
var pathi;

    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/javascript");
    editor.session.setOption("useWorker", false);
    editor.setOptions({
    enableBasicAutocompletion: false,

});
editor.resize();

function showFiles()
{
  $("#projectsDiv").width( 0 )
  $("#filesWrap").width( $("#left").width() )

}

function showProjects() {
  $("#filesWrap").width( 0 )
  $("#projectsDiv").width( $("#left").width() )
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
          console.log(obj[1]);
          listProjects();
          openProject(name);

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

          location.replace("index.html");
          location.replace("index.html");
          location.replace("index.html");

        }
        else {
          console.log(obj);
          var x = 0;
          $("#projects").empty();
          while(x < objects.length)
          {
            var name = objects[x].name;
            $("#projects").append('<a style="color: #fff !important;cursor: pointer;"  onClick="openProject(\'' + name + '\')">'+name+'</a><br/>');



            x++;
          }

        }
    }
  });
}
function listFiles()
{
  var token = localStorage.getItem("token");
  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'listFiles', token: token, name: project},
    success: function(data) {
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {
          console.log(obj);


        }
        else {
          console.log(obj);
          var x = 0;
          $("#files").empty();
          $("#files").append('<br/> <strong>Files: </strong><br/>');
          var x = 0;
          var value = 1;
          while(x < objects.length)
          {
            var name = objects[x];
            console.log(name);
            $("#files").append('<a style="display: inline; padding: 0px; color:#fff;" href="javascript:getFile(\'' + name + '\')" >'+ name + '</a> <a  style="background: transparent; color: #fff; margin: 0; padding: 0; margin-right: 10px; display: inline; float: right;"  href="javascript:deleteFile(\'' + name + '\')" >X</a><br/>');
            x++;
          }

        }
    }
  });
}
function getFile(path)
{
  pathi = path;
  var token = localStorage.getItem("token");
  //Var project is a global variable

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'getFile', token: token, name: project, path: path},
    success: function(data) {
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {



        }
        else {
          console.log(obj[1])
          editor.getSession().setValue("");
          editor.getSession().setValue(objects);
          //replace()
        }
    }
  });
}
function updateFile()
{
  //Var pathi = global var
  var token = localStorage.getItem("token");
  //Var project is a global variable

  var data = editor.getSession().getDocument().getValue();

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'updateFile', token: token, name: project, path: pathi, data: data},
    success: function(data) {
      console.log(data);
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {


        }
        else {
          listFiles();
          $("#openaddfilebutton").show();
          $("#newfilename").hide();
          $("#newfilenameok").hide();
        }
    }
  });
}
function addFile()
{
  //Var pathi = global var
  var token = localStorage.getItem("token");
  //Var project is a global variable
  pathi = $("#newfilename").val();

  var data = "";

  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'updateFile', token: token, name: project, path: pathi, data: data},
    success: function(data) {
      console.log(data);
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {
          $("#notlogged").show();
          $("#logged").hide();
        }
        else {
          listFiles();
          editor.getSession().setValue("");
          $("#openaddfilebutton").show();
          $("#newfilename").hide();
          $("#newfilenameok").hide();
        }
    }
  });
}
function deleteFile(path)
{
  //Var pathi = global var
  var token = localStorage.getItem("token");
  //Var project is a global variable



  $.ajax( {
    url: 'http://soluide.sovellus.design/api/files.php',
    method: 'POST',
    data: {action: 'deleteFile', token: token, name: project, path: path},
    success: function(data) {
      console.log(data);
        var obj= JSON.parse(data);
        var objects = obj[1];
        if(obj[0] == false)
        {
        }
        else {
          listFiles();
        }
    }
  });
}
function openAddFile()
{
    $("#openaddfilebutton").hide();
    $("#newfilename").show();
    $("#newfilenameok").show();


}
function newFileInit()
{

  updateFile();
}
function back()
{

}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    console.log("WTF: " + vars.project);
    return vars;
}
function runCode()
{
  var data = editor.getSession().getDocument().getValue();

  //löydä script src ja link rel
  //var popup = window.open("http://soluide.sovellus.design/api/application/projects/"+project+"/index.html");
  //popup.document.write(data);
  window.open(
  "http://soluide.sovellus.design/api/application/projects/"+project+"/index.html",
  '_blank' // <- This is what makes it open in a new window.
);
}

document.addEventListener('DOMContentLoaded', function () {

  var ebin = getUrlVars();
  project = ebin.project;
  listFiles();
  getFile('index.html');
});
$(window).keypress(function(event) {
    if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
    console.log("Saving the file...");
    event.preventDefault();
    return false;
});
function openProject(name) {
  editor.getSession().setValue("");
  project = name;
  showFiles();
  listFiles();
  $("#projectname").append(name);

}

$(document).ready(function() {
  listProjects();
});
