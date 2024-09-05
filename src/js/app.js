// Example starter JavaScript for disabling form submissions if there are invalid fields
let reloadFormValidation = function(){
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
  .forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  });
}

// Component loader
function loadComponent( componentName, pageTitle ){
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log( this.responseText.length );
      if( this.responseText.length > 0 )
      {
        document.getElementById("app").innerHTML = this.responseText;
        window["pg_"+componentName]();
        document.title = pageTitle;
        window.history.pushState({"html":this.responseText,"pageTitle":pageTitle}, "");
      }
      else
      {
        loadComponent('loginform', 'Bundeling Project | Login');
      }
      reloadFormValidation();
    }
  };
  xhttp.open("GET", homeUrl + "/src/components/" + componentName + ".php", true);
  xhttp.send();
}

// Process Registration form
function processRegisterForm(){
  const form = document.querySelector("#register_form");
  const formData = JSON.stringify(Object.fromEntries(new FormData(form)));
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let data = JSON.parse(this.responseText);
      if( data.valid === false ) {
        Object.keys(data.errors).forEach(function(key) {
          document.getElementById(key).nextElementSibling.innerText = data.errors[key];
          console.log(document.getElementById(key).nextElementSibling);
        });
      } else {
        let $app = document.getElementById("app");
        $app.classList.add("align-items-center");
        $app.innerHTML = '<h3>Registration OK, transferring,..</h3>';
        setTimeout(function(){
          $app.classList.remove("align-items-center");
          loadComponent('dashboard', 'Bundeling Project | Dashboard');
        }, 3000);
      }
    }
  };
  xhttp.open("POST", homeUrl + "/src/php/ajax/register.php", true);
  xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
  xhttp.send(formData);
}

// Process Login form
function processLoginForm(){
  const form = document.querySelector("#login_form");
  const formData = JSON.stringify(Object.fromEntries(new FormData(form)));
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let data = JSON.parse(this.responseText);
      if( data.authenticated === false ) {
        Object.keys(data.errors).forEach(function(key) {
          document.getElementById(key).nextElementSibling.innerText = data.errors[key];
          console.log(document.getElementById(key).nextElementSibling);
        });
      } else {
        let $app = document.getElementById("app");
        $app.innerHTML = '<h3>Login OK, transferring,..</h3>';
        setTimeout(function(){
          $app.classList.remove("align-items-center");
          loadComponent('dashboard', 'Bundeling Project | Dashboard');
        }, 3000);
      }
    }
  };
  xhttp.open("POST", homeUrl + "/src/php/ajax/login.php", true);
  xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
  xhttp.send(formData);  
}

// Initialize Messages page
window.pg_messages = function(){
  headerEvents();
  searchEvent( 'message' );
}

// Initialize Users page
window.pg_users = function(){
  headerEvents();
  searchEvent( 'user' );
}

// Initialize Dashboard
window.pg_dashboard = function(){
  headerEvents();
}

// Initialize Login form
window.pg_loginform = function(){
  let $app = document.getElementById("app");
  $app.classList.add("align-items-center");
  $("#btn_register").on("click", function(){
    loadComponent('registerform', 'Bundeling Project | Register');
  });
  $("#login_form").off().on("submit", function(e){
      e.preventDefault();
      e.stopPropagation();
      processLoginForm();
  });  
}

// Initialize Register form
window.pg_registerform = function(){
  $("#btn_back").on("click", function(){
    loadComponent('loginform', 'Bundeling Project | Login');
  });
  $("#register_form").off().on("submit", function(e){
    e.preventDefault();
    e.stopPropagation();
    processRegisterForm();
  });
}

// Initialize Header Events
let headerEvents = function(){
  $("#btn_logout").off().on("click", function(){
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let $app = document.getElementById("app");
        $app.classList.add("align-items-center");
        $app.innerHTML = '<h3>Log out OK, transferring,..</h3>';
        setTimeout(function(){
          loadComponent('loginform', 'Bundeling Project | Login');
        }, 3000);
      }
    };
    xhttp.open("GET", homeUrl + "/src/php/ajax/logout.php", true);
    xhttp.send();
  });
  $(".btn_users").off().on("click", function(){
    loadComponent('users', 'Bundeling Project | Users');
  });
  $(".btn_messages").off().on("click", function(){
    loadComponent('messages', 'Bundeling Project | Messages');
  });
  $("#btn_dashboard").off().on("click", function(){
    loadComponent('dashboard', 'Bundeling Project | Dashboard');
  });
}

// Search event
function searchEvent( type ){
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {  
      document.getElementById('resultbox').innerHTML = this.responseText;
      let messageCount = document.getElementById('resultbox').children.length;
      document.getElementById('count_text').innerText = messageCount;
    }
  };
  $("#search_header input").on("keyup", function(){
    setTimeout(function(){
      let formData = new FormData();
      formData.append( "type", type );
      if( document.getElementById('search_id') && document.getElementById('search_id').value !== '' ) {
        formData.append( "id", document.getElementById('search_id').value );
      }
      if( document.getElementById('search_data').value !== '' ) {
        formData.append( "data", document.getElementById('search_data').value );
      }

      xhttp.open("POST", homeUrl + "/src/php/ajax/search.php", true);
      xhttp.send( formData ); 
    }, 500);
  })
}