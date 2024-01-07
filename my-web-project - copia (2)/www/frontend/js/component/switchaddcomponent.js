class SwitchsAddComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
      super(Handlebars.templates.switchedit, switchsModel);
      this.switchsModel = switchsModel; 
      
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
  
      this.addEventListener('click', '#savebutton', () => {
        var newSwitchs= {};
        newSwitchs.Descriptionswitchs = $('#description').val();
        // Crear UUIDs y demas
        newSwitchs.AliasUser = this.userModel.currentUser;
        this.switchsService.addSwitchs(newSwitchs)
          .then(() => {
            this.router.goToPage('switchs');
          })
          .fail((xhr, errorThrown, statusText) => {
            if (xhr.status == 400) {
              this.switchsModel.set(() => {
                this.switchsModel.errors = xhr.responseJSON;
              });
            } else {
              alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
            }
          });
      });
    }
    
    onStart() {
      this.switchsModel.setSelectedSwitchs(new SwitchsModel());
    }
  }