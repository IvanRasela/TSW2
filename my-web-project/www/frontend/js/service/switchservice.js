class SwitchService {
  constructor() {

  }

  getSwitches() {
    return $.get(AppConfig.backendServer+'/rest/switch/get');
  }

  getSwitchesByPublic(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/' + uuid);
  }
  getSwitchesByPrivate(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/' + uuid);
  }

  getSwitchesSuscribe() {
    return $.get(AppConfig.backendServer+'/rest/switch/' + uuid);
  }

  createSwitch(switch_r) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch',
      method: 'POST',
      data: JSON.stringify(post),
      contentType: 'application/json'
    });
  }
  deleteSwitch(uuid) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/' + uuid,
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
