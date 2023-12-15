class SwitchsComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
      super(Handlebars.templates.switchstable, switchsModel, null, null);
      
      
      this.switchsModel = switchsModel;
      this.userModel = userModel;
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
  
    }
  
    onStart() {
      this.updateSwitchs();
    }
  
    updateSwitchs() {
      this.switchsService.getSwitchs().then((data) => {
  
        this.switchsModel.setSwitchs(
          // create a Fronty.Model for each item retrieved from the backend
          data.map(
            (item) => new SwitchsModel(item.switchsName, item.Public_UUID, item.AliasUser, item.Descriptionswitchs)
        ));
      });
    }
  
    // Override
    createChildModelComponent(className, element, id, modelItem) {
      return new SwitchsRowComponent(modelItem, this.userModel, this.router, this);
    }
  }
  
  class SwitchsRowComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router, SwitchsComponent) {
      super(Handlebars.templates.switchsrow, switchsModel, null, null);
      
      this.switchsComponent = switchsComponent;
      
      this.userModel = userModel;
      this.addModel('user', userModel); // a secondary model
      
      this.router = router;
  
      this.addEventListener('click', '.remove-button', (event) => {
        if (confirm(I18n.translate('Are you sure?'))) {
          var switchsId = event.target.getAttribute('item');
          this.switchsComponent.switchsService.deleteSwitch(switchsId)
            .fail(() => {
              alert('post cannot be deleted')
            })
            .always(() => {
              this.switchsComponent.updateSwitchs();
            });
        }
      });
      // On-Off
      this.addEventListener('click', '.edit-button', (event) => {
        var postId = event.target.getAttribute('item');
        this.router.goToPage('edit-post?id=' + postId);
      });
    }
  
  }