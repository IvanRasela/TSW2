
//Se encarga de la navegación. 
//Los componentes definidos gestionan la lógica de la aplicación
//  y, a menudo, también manipulan el DOM para mostrar las vistas.
//  MainComponent está configurado para manejar las rutas y cargar diferentes componentes
class MainComponent extends Fronty.RouterComponent {
  constructor() {
    super('frontyapp', Handlebars.templates.main, 'maincontent');

    // models instantiation
    // we can instantiate models at any place
    this.userModel = new UserModel();
    this.switchesModel = new SwitchesModel();
    this.userService = new UserService();
    this.switchService = new SwitchService();

    super.setRouterConfig({
      switches: {
        component: new SwitchComponent(this.switchesModel, this.userModel, this),
        title: 'Switches'
      },
      'view-switch': {
        component: new SwitchViewComponent(this.switchesModel, this.userModel, this),
        title: 'View Switch'
      },
      'add-switch': {
        component: new SwitchAddComponent(this.switchesModel, this.userModel, this),
        title: 'Add Switch'
      },
      login: {
        component: new LoginComponent(this.userModel, this),
        title: 'Login'
      },
      defaultRoute: 'switches'
    });

    Handlebars.registerHelper('currentPage', () => {
          return super.getCurrentPage();
    });

    this.addChildComponent(this._createUserBarComponent());
    this.addChildComponent(this._createLanguageComponent());

  }

  start() {
    // override the start() function in order to first check if there is a logged user
    // in sessionStorage, so we try to do a relogin and start the main component
    // only when login is checked
    this.userService.loginWithSessionData()
      .then((logged) => {
        if (logged != null) {
          this.userModel.setLoggeduser(logged);
        }
        super.start(); // now we can call start
      });
      this.addEventListener('click', '.search-button', (event) => {
        
        //document.getElementById('searchCont').value
        alert(document.getElementById('searchCont').value);
        this.switchService.search(document.getElementById('searchCont').value)

      });
  }

  _createUserBarComponent() {
    var userbar = new Fronty.ModelComponent(Handlebars.templates.user, this.userModel, 'userbar');

    userbar.addEventListener('click', '#logoutbutton', () => {
      this.userModel.logout();
      this.userService.logout();
    });

    return userbar;
  }

  _createLanguageComponent() {
    var languageComponent = new Fronty.ModelComponent(Handlebars.templates.language, this.routerModel, 'languagecontrol');
    // language change links
    languageComponent.addEventListener('click', '#englishlink', () => {
      I18n.changeLanguage('default');
      document.location.reload();
    });

    languageComponent.addEventListener('click', '#spanishlink', () => {
      I18n.changeLanguage('es');
      document.location.reload();
    });

    return languageComponent;
  }

  search(uuid) {
    this.switchService.search(uuid).then((data) => {

      this.switchModel.setSelectedSwitch(
        // create a Fronty.Model for each item retrieved from the backend
        data.map(
          (item) => new SwitchModel(item.SwitchName, item.Public_UUID, item.Private_UUID, item.AliasUser)
      ));
      
      this.router.goToPage('view-switch' + { id: uuid });
    });
  }

  
}