class SwitchService {
  constructor() {

  }

  getSwitches(user) {
    return $.get(AppConfig.backendServer+'/rest/switch/' + user);
  }

  getSwitchesSuscribe(user) {
    return $.get(AppConfig.backendServer+'/rest/switch/suscribers/' + user);
  }

  getSwitchesByPublic(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/public/' + uuid);
  }

  getSwitchesByPrivate(uuid) {
    return $.get(AppConfig.backendServer+'/rest/switch/private/' + uuid);
  }

  addSwitch(switch_r) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/switch/new',
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

  search(searchCont) {
    return new Promise((resolve, reject) => {
      $.get({
        url: AppConfig.backendServer + '/rest/switch/public/' + searchCont,
        success: (data) => {
          resolve(data);
        },
        error: (error) => {
          window.sessionStorage.removeItem('searchCont');
          reject(error);
        }
      });
    });
  }


}
