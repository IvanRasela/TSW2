//Fronty.Model es parte del framework Fronty para gestionar modelos 
//  de una sola página como SPA

class SwitchesModel extends Fronty.Model {

    constructor() {
      super('SwitchesModel'); //call super
  
      // model attributes
      this.switches = [];
    }
  
    setSelectedSwitch(switch_r) {
      this.set((self) => {
        self.selectedSwitch = switch_r;
      });
    }
  
    setSwitches(switches) {
      this.set((self) => {
        self.switches = switches;
      });
    }
  }