window.onsubmit = main;

function main() {
    var invalidMsgs = "";
    invalidMsgs += validate_input();
    
    if (invalidMsgs !== "") {
        alert(invalidMsgs);
        return false;
    }else {
        var confirm = "Do you want to submit the form data?";
        return window.confirm(confirm);
    }
}

function validate_input() {
    var msg = "";
    var login_user = document.getElementById('login_user').value.trim();
    var login_pw = document.getElementById('login_pw').value.trim();
    var reg_user = document.getElementById('reg_user').value.trim();
    var reg_pw = document.getElementById('reg_pw').value.trim();
     
    if (hasNumber(login_user) || hasNumber(reg_user)) {
       msg += "Your username has included numbers\n";
    }
    
    if (hasWhiteSpace(login_user) || hasWhiteSpace(reg_user)) {
       msg += "Your username has included whitespaces\n";
    }
    
    if (hasWhiteSpace(login_pw) || hasWhiteSpace(reg_pw)) {
          msg += "Your password has included whitespaces\n";
    }
    
    return msg;
}

function hasNumber(myString) {
  return /\d/.test(myString);
}

function hasWhiteSpace(s) {
  return /\s/g.test(s);
}