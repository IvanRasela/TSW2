class SwitchViewComponent extends Fronty.ModelComponent {
  constructor(switchModel, userModel, router) {
      super(Handlebars.templates.switchview, switchModel);

      this.switchModel = switchModel; // switches
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;

      
      this.switchesService = new SwitchService();

      /*this.addEventListener('click', '#createswitch', () => {
          var selectedId = this.router.getRouteQueryParam('id');//id?
          this.switchesService.createSwitch(selectedId, {
                  content: $('#switchcontent').val()
              })
              .then(() => {
                  $('#switchcontent').val('');
                  //this.loadSwitch(selectedId);
              })
              .fail((xhr, errorThrown, statusText) => {
                  if (xhr.status == 400) {
                      this.switchModel.set(() => {
                          this.switchModel.commentErrors = xhr.responseJSON;
                      });
                  } else {
                      alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
                  }
              });
      });*/
      
  }

  onStart() {
      var selectedId = this.router.getRouteQueryParam('uuid');//parametro de una URL
      this.loadSwitch(selectedId);
      if (switchId != null) {
        this.switchesService.getSwitchesByPublic(switchId)
          .then((data) => {
            this.switchModel.setSelectedSwitch(data);
          });
      }
  }

}