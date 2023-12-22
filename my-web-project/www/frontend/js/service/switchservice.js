class SwitchService {
    constructor() {
  
    }

    getSwitch() {
      return $.get(AppConfig.backendServer+'/rest/switch');
    }
  
    getSwitchByPublic(id) {
        return $.get(AppConfig.backendServer+'/rest/switch/' + id);
      }

    getSwitchByPrivate(id) {
        return $.get(AppConfig.backendServer+'/rest/switch/' + id);
    }

    getSwitchSuscribe(id) {
        return $.get(AppConfig.backendServer+'/rest/switch/' + id);
    }
    
    findSwitch(id) {
      return $.get(AppConfig.backendServer+'/rest/switch/' + id);
    }
  
    deleteSwitch(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch/' + id,
        method: 'DELETE'
      });
    }
  
    createSwitch(switch_r) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch/' + switch_r.id,
        method: 'PUT',
        data: JSON.stringify(switch_r),
        contentType: 'application/json'
      });
    }
  
    createSwitch(switch_r) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch',
        method: 'POST',
        data: JSON.stringify(switch_r),
        contentType: 'application/json'
      });
    }
  
    turnOnOffSwitchPublic(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch/' + switchid + '/comment',
        method: 'POST',
        data: JSON.stringify(comment),
        contentType: 'application/json'
      });
    }

    turnOnOffSwitchPrivate(id) {
        return $.ajax({
          url: AppConfig.backendServer+'/rest/switch/' + switchid + '/comment',
          method: 'POST',
          data: JSON.stringify(comment),
          contentType: 'application/json'
        });
      }
  
  }