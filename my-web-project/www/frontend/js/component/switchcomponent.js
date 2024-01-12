class SwitchComponent extends Fronty.ModelComponent {
    constructor(switchModel, userModel, router) {
      super(Handlebars.templates.switchestable, switchModel, null, null);
      
      this.switchModel = switchModel;
      this.userModel = userModel;
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchService = new SwitchService();
  
    }
  
    onStart() {
      this.updateSwitch();
    }
  
    async updateSwitch() {
      this.switchService.getSwitches(this.userModel.currentUser).then((data) => {
  
        this.switchModel.setSwitches(
          // create a Fronty.Model for each item retrieved from the backend
          data.map(
            (item) => new SwitchModel(item.SwitchName, item.Public_UUID, item.Private_UUID, item.AliasUser)
        ));
      })

      this.switchService.getSwitchesSuscribe(this.userModel.currentUser).then((data1) => {
  
        this.switchModel.setSwitchesSuscribe(
          // create a Fronty.Model for each item retrieved from the backend
          data1.map(
            (item) => SwitchModel(item.SwitchName, item.Public_UUID, item.Private_UUID, item.AliasUser)
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
      
      this.switchComponent = SwitchComponent;
      
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