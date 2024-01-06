

//login--alias
//pass--passwd
class UserService {
  constructor() {

  }

  //este método compruba si hay datos de sesión almacenados localmente
  //  intenta realizar un inicio de sesión automáticamente utilizando esos datos. 
  loginWithSessionData() {
    var self = this;
    return new Promise((resolve, reject) => {
      if (window.sessionStorage.getItem('alias') &&
        window.sessionStorage.getItem('passwd')) {
        self.login(window.sessionStorage.getItem('alias'), window.sessionStorage.getItem('passwd'))
          .then(() => {
            resolve(window.sessionStorage.getItem('alias'));//resultado exitoso de la promesa
          })
          .catch(() => {
            reject();//resultado fallido de la promesa
          });
      } else {
        resolve(null);//resultado exitoso de la promesa
      }
    });
  }

  login(login, pass) {
    return new Promise((resolve, reject) => {

      $.get({
          url: AppConfig.backendServer+'/rest/user/' + login,
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
          }
        })
        .then(() => {
          //keep this authentication forever
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);
          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve();
        })
        .fail((error) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          $.ajaxSetup({
            beforeSend: (xhr) => {}
          });
          reject(error);
        });
    });
  }

  logout() {
    window.sessionStorage.removeItem('alias');
    window.sessionStorage.removeItem('passwd');
    $.ajaxSetup({
      beforeSend: (xhr) => {}
    });
  }

  register(user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user',
      method: 'POST',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }
}
