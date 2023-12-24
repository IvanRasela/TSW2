
class SwitchComponent extends Fronty.ModelComponent {
    constructor(switchModel, userModel, router) {
      super(Handlebars.templates.switchtable, switchModel, null, null);
      
      alert("switchcomponent");
      this.switchModel = switchModel;
      this.userModel = userModel;
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchService = new SwitchService();
  
    }
  
    onStart() {
      this.updateSwitch();
    }
  
    updateSwitch() {
      this.switchService.getSwitch().then((data) => {
  
        this.switchModel.setSwitch(
          // create a Fronty.Model for each item retrieved from the backend
          data.map(
            (item) => new SwitchModel(item.switchName, item.Public_UUID, item.AliasUser, item.Descriptionswitch)
        ));
      });
    }
  
    // Override
    createChildModelComponent(className, element, id, modelItem) {
      return new SwitchRowComponent(modelItem, this.userModel, this.router, this);
    }
  }
  
  class SwitchRowComponent extends Fronty.ModelComponent {
    constructor(switchModel, userModel, router, SwitchComponent) {
      super(Handlebars.templates.switchrow, switchModel, null, null);
      
      this.switchComponent = switchComponent;
      
      this.userModel = userModel;
      this.addModel('user', userModel); // a secondary model
      
      this.router = router;
  
      this.addEventListener('click', '.remove-button', (event) => {
        if (confirm(I18n.translate('Are you sure?'))) {
          var switchId = event.target.getAttribute('item');
          this.switchComponent.switchService.deleteSwitch(switchId)
            .fail(() => {
              alert('switch cannot be deleted')
            })
            .always(() => {
              this.switchComponent.updateSwitch();
            });
        }
      });
      // On-Off
      this.addEventListener('click', '.edit-button', (event) => {
        var switchId = event.target.getAttribute('item');
        this.router.goToPage('edit-switch?id=' + switchId);
      });
    }
  
  }