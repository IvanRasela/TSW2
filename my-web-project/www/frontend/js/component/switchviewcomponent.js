class SwitchViewComponent extends Fronty.ModelComponent {
  constructor(switchModel, userModel, router) {
      super(Handlebars.templates.switchedit, switchModel);

      this.switchModel = switchModel; // switches
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;

      
      this.switchesService = new SwitchService();
      
  }

  onStart() {
      alert("dentro de switchView");
  }

}