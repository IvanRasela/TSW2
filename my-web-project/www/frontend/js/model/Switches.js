//Fronty.Model es parte del framework Fronty para gestionar modelos 
//  de una sola página como SPA

class SwitchesModel extends Fronty.Model {

    constructor() {
      super('SwitchesModel'); //call super
  
      // model attributes
      this.switches = [];
      this.switchesSuscribe = [];
      this.selectedSwitch;
    }

    setSelectedSwitch(selectedSwitch) {
      this.set((self) => {
        self.selectedSwitch = selectedSwitch;
      });
    }
  
    setSwitches(switches) {
      this.set((self) => {
        self.switches = switches;
      });
    }

    setSwitchesSuscribe(switchesSuscribe) {
      this.set((self) => {
        self.switchesSuscribe = switchesSuscribe;
      });
    }
  }