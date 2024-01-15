class SwitchAddComponent extends Fronty.ModelComponent {
  constructor(switchModel, userModel, router) {
    super(Handlebars.templates.switchedit, switchModel);

    this.switchModel = switchModel; 
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.switchService = new SwitchService();

    this.addEventListener('click', '#savebutton', () => {
      var newSwitch= {};
      var switchName={};
      switchName = $('#title').val();
      if (this.validarNombreInterruptor(switchName)) {
        // El nombre del interruptor es válido, puedes proceder con su uso
        newSwitch.SwitchName = switchName;
    }
      newSwitch.DescriptionSwitch = $('#description').val();
      newSwitch.MaxTimePowerOn = $('#maxTime').val();
      newSwitch.AliasUser = this.userModel.currentUser;
      this.switchService.addSwitch(newSwitch)
        .then(() => {
          this.router.goToPage('switches');
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.switchModel.set(() => {
              this.switchModel.errors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });
    });
  }
  
  onStart() {
    this.switchModel.setSelectedSwitch(new SwitchModel());
  }

  validarNombreInterruptor(nombre) {
    // Verificar longitud máxima (20 caracteres)
    if (nombre.length > 20) {
        alert('El nombre del interruptor no puede tener más de 20 caracteres.');
        return false; // Devolver false indicando un error
    }

    // Verificar caracteres especiales utilizando una expresión regular
    var regex = /^[a-zA-Z0-9\s]+$/; // Permite letras, números y espacios

    if (!regex.test(nombre)) {
        alert('El nombre del interruptor no puede contener caracteres especiales.');
        return false; // Devolver false indicando un error
    }

    // El nombre del interruptor es válido
    return true;
}
}