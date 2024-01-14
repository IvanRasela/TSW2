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
      newSwitch.SwitchName = $('#title').val();
      newSwitch.DescriptionSwitch = $('#description').val();
      newSwitch.MaxTimePowerOn = $('#maxTime').val();
      newSwitch.AliasUser = this.userModel.currentUser;
      this.switchService.addSwitch(newSwitch)
        .then(() => {
          this.router.goToPage('switch');
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
}