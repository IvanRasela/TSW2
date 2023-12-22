//Fronty.Model es parte del framework Fronty para gestionar modelos 
//  de una sola página como SPA

class SwitchesModel extends Fronty.Model {

    constructor() {
      super('SwitchesModel'); //call super
  
      // model attributes
      this.switch = [];
    }
  
    setSelectedSwitch(switch_r) {
      this.set((self) => {
        self.selectedSwitch = switch_r;
      });
    }
  
    setSwitch(switch_r) {
      this.set((self) => {
        self.switch = switch_r;
      });
    }
  }