/* Main mvcblog-front script */

//load external resources
// Carga archivos de texto desde una URL y devuelve una promesa.
// hace uso de jQuery para realizar la solicitud AJAX y carga
//    el recurso especificado.
//Promesa: objeto que representa el resultado eventual de una operación asíncrona.
//    en lugar de realizar callbacks estas proporcionan limpieza.

function loadTextFile(url) {
    return new Promise((resolve, reject) => {
      $.get({
        url: url,
        cache: true,
        beforeSend: function( xhr ) {
          xhr.overrideMimeType( "text/plain" );
        }
      }).then((source) => {
        resolve(source);
      }).fail(() => reject());
    });
  }
  
  
  // Configuration
  var AppConfig = {
    backendServer: 'http://localhost'
    //backendServer: '/mvcblog'
  }

  //El código en app.js hace uso de Handlebars para compilar 
  //    plantillas antes de que la aplicación comience.
  
  Handlebars.templates = {};
  //Promise.all para esperar que todas las promesas de carga de recursos
  //  y compilación de plantillas se resuelvan antes de continuar.
  
  Promise.all([
      I18n.initializeCurrentLanguage('js/i18n'),
      loadTextFile('templates/components/main.hbs').then((source) =>
        Handlebars.templates.main = Handlebars.compile(source)),
      loadTextFile('templates/components/language.hbs').then((source) =>
        Handlebars.templates.language = Handlebars.compile(source)),
      loadTextFile('templates/components/user.hbs').then((source) =>
        Handlebars.templates.user = Handlebars.compile(source)),
      loadTextFile('templates/components/login.hbs').then((source) =>
        Handlebars.templates.login = Handlebars.compile(source)),
      loadTextFile('templates/components/switch-table.hbs').then((source) =>
        Handlebars.templates.switchtable = Handlebars.compile(source)),
      loadTextFile('templates/components/switch-edit.hbs').then((source) =>
        Handlebars.templates.switchedit = Handlebars.compile(source)),
      loadTextFile('templates/components/switch-view.hbs').then((source) =>
        Handlebars.templates.switchview = Handlebars.compile(source)),
      loadTextFile('templates/components/switch-row.hbs').then((source) =>
        Handlebars.templates.switchrow = Handlebars.compile(source))
    ])
    //todas las promesas se cumplen entonces se ejecuta la función de inicio.
    .then(() => {
      $(() => {
        new MainComponent().start();//llamada al método start de MainComponent
      });
    }).catch((err) => {
      alert('FATAL: could not start app ' + err);
    });
  