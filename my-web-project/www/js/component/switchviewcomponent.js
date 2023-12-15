class PostViewComponent extends Fronty.ModelComponent {
    constructor(switchssModel, userModel, router) {
      super(Handlebars.templates.switchview, switchsModel);
  
      this.switchsModel = switchsModel; // posts
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
  
      this.addEventListener('click', '#createswitch', () => {
        var selectedId = this.router.getRouteQueryParam('id');
        this.switchsService.createSwitch(selectedId, {
            content: $('#switchcontent').val()
          })
          .then(() => {
            $('#switchcontent').val('');
            //this.loadPost(selectedId);
          })
          .fail((xhr, errorThrown, statusText) => {
            if (xhr.status == 400) {
              this.switchsModel.set(() => {
                this.switchsModel.commentErrors = xhr.responseJSON;
              });
            } else {
              alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
            }
          });
      });
    }
  
    onStart() {
      var selectedId = this.router.getRouteQueryParam('id');
      this.loadSwitch(selectedId);
    }
  
    loadPost(postId) {
      if (postId != null) {
        this.postsService.findPost(postId)
          .then((post) => {
            this.postsModel.setSelectedPost(post);
          });
      }
    }
  }