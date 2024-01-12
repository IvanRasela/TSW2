class SwitchService {
  constructor() {

  }

  getSwitches(user) {
    return $.get(AppConfig.backendServer+'/rest/switch/' + user);
  }

  getSwitchesSuscribe(user) {
    return $.get(AppConfig.backendServer+'/rest/switch/suscribe/' + user);
  }

  getSwitchesByUUID(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/public/' + uuid);
  }

  getSwitchesByPrivate(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/private/' + uuid);
  }

  addSwitch(switch_r) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/new/',
      method: 'POST',
      data: JSON.stringify(switch_r),
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
