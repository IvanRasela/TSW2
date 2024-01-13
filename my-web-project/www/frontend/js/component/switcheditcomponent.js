class SwitchEditComponent extends Fronty.ModelComponent {
  constructor(switchesModel, userModel, router) {
    super(Handlebars.templates.switchedit, switchesModel);
    this.switchesModel = switchesModel; // switches
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.switchService = new SwitchService();

    this.addEventListener('click', '#searchswitch', () => {
      this.switchesModel.selectedSwitch.Public_UUID = $('#uuid').val();
      this.switchesService.getSwitchesByPublic(this.switchesModel.selectedSwitch.Public_UUID)
        .then(() => {
          this.switchesModel.set((model) => {
            model.errors = []
          });
          this.router.goToPage('switches');
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.switchesModel.set((model) => {
              model.errors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });

    });
  }

  onStart() {
    var selectedId = this.router.getRouteQueryParam('id');
    if (selectedId != null) {
      this.switchesService.findSwitch(selectedId)
        .then((switch_r) => {
          this.switchModel.setSelectedSwitch(switch_r);
        });
    }
  }
}

