class SwitchViewComponent extends Fronty.ModelComponent {
  constructor(switchModel, userModel, router) {
      super(Handlebars.templates.switchview, switchModel);

      this.switchModel = switchModel; // switches
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;

      
      this.switchesService = new SwitchService();
        //searchCont
      this.addEventListener('click', '#searchbutton', () => {
        var selectedId = this.router.getRouteQueryParam('id');//no entiendo de donde sale este id

      });
      
  }

  onStart() {
      var uuidSwitch = $('#searchCont').val();
      this.loadSwitch(uuidSwitch);
      if (uuidSwitch != null) {
        this.switchesService.getSwitchesByPublic(uuidSwitch)
          .then((data) => {
            this.switchModel.setSelectedSwitch(data);
          });
      }
  }

}