class SwitchsModel extends Fronty.Model {

    constructor() {
      super('SwitchsModel'); //call super
  
      // model attributes
      this.switchs = [];
    }
  
    setSelectedSwitch(switchs) {
      this.set((self) => {
        self.selectedPost = post;
      });
    }
  
    setSwitchs(switchs) {
      this.set((self) => {
        self.switchs = switchs;
      });
    }
  }