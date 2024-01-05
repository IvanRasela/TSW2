class SwitchService {
  constructor() {

  }

  getSwitches() {
    return $.get(AppConfig.backendServer+'/rest/switch');
  }

  getSwitchesByPublic(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/public/' + uuid);
  }
  getSwitchesByPrivate(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/private/' + uuid);
  }

  getSwitchesSuscribe() {
    return $.get(AppConfig.backendServer+'/rest/switch/suscribers/' + uuid);
  }

  createSwitch(switch_r) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/new/',
      method: 'POST',
      data: JSON.stringify(post),
      contentType: 'application/json'
    });
  }
  deleteSwitch(uuid) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/del/' + uuid,
      method: 'DELETE'
    });
  }

  savePost(post) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/' + post.id,
      method: 'PUT',
      data: JSON.stringify(post),
      contentType: 'application/json'
    });
  }

/*createComment(postid, comment) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/post/' + postid + '/comment',
      method: 'POST',
      data: JSON.stringify(comment),
      contentType: 'application/json'
    });
  }*/

}
