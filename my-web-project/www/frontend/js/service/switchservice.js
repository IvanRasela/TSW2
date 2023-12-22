class PostsService {
    constructor() {
  
    }

    getSwitchs() {
      return $.get(AppConfig.backendServer+'/rest/post');
    }
  
    getSwitchsByPublic(id) {
        return $.get(AppConfig.backendServer+'/rest/post/' + id);
      }

    getSwitchsByPrivate(id) {
        return $.get(AppConfig.backendServer+'/rest/post/' + id);
    }

    getSwitchsSuscribe(id) {
        return $.get(AppConfig.backendServer+'/rest/post/' + id);
    }
    
    findPost(id) {
      return $.get(AppConfig.backendServer+'/rest/post/' + id);
    }
  
    deleteSwitch(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/post/' + id,
        method: 'DELETE'
      });
    }
  
    createSwitch(switchs) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/post/' + post.id,
        method: 'PUT',
        data: JSON.stringify(post),
        contentType: 'application/json'
      });
    }
  
    createSwitch(switchs) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/post',
        method: 'POST',
        data: JSON.stringify(post),
        contentType: 'application/json'
      });
    }
  
    turnOnOffSwitchPublic(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/post/' + postid + '/comment',
        method: 'POST',
        data: JSON.stringify(comment),
        contentType: 'application/json'
      });
    }

    turnOnOffSwitchPrivate(id) {
        return $.ajax({
          url: AppConfig.backendServer+'/rest/post/' + postid + '/comment',
          method: 'POST',
          data: JSON.stringify(comment),
          contentType: 'application/json'
        });
      }
  
  }