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
      url: AppConfig.backendServer + '/rest/switch/' + uuid,
      method: 'DELETE'
    });
  }

  desSubscribe(uuid) {
    return $.ajax({
      url: AppConfig.backendServer + '/rest/switch/des/' + uuid,
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
    return $.get(AppConfig.backendServer+'/rest/switch/search/' + searchCont);
  }


}
