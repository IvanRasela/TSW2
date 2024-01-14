class SwitchModel extends Fronty.Model {

    constructor(SwitchName, Private_UUID, Public_UUID, AliasUser, DescriptionSwitch, LastTimePowerOn,MaxTimePowerOn ) {
      super('SwitchModel'); //call super
      
      if (SwitchName) {
        this.SwitchName = SwitchName;
      }
      
      if (Private_UUID) {
        this.Private_UUID = Private_UUID;
      }
      
      if (Public_UUID) {
        this.Public_UUID = Public_UUID;
      }

      if (AliasUser) {
        this.AliasUser = AliasUser;
      }

      if (DescriptionSwitch) {
        this.DescriptionSwitch = DescriptionSwitch;
      }

      if (LastTimePowerOn) {
        this.LastTimePowerOn = null;
      }

      if (MaxTimePowerOn) {
        this.MaxTimePowerOn = MaxTimePowerOn;
      }

    }
  
    setTitle(SwitchName) {
      this.set((self) => {
        self.SwitchName = SwitchName;
      });
    }
  
    setAuthor_id(Private_UUID) {
      this.set((Private_UUID) => {
        self.Private_UUID = Private_UUID;
      });
    }

    setAuthor_id(Public_UUID) {
        this.set((self) => {
          self.Public_UUID = Public_UUID;
        });
      }

    setAuthor_id(AliasUser) {
    this.set((self) => {
        self.AliasUser = AliasUser;
    });
    }

    setAuthor_id(DescriptionSwitch) {
    this.set((self) => {
        self.DescriptionSwitch = DescriptionSwitch;
    });
    }

    setAuthor_id(LastTimePowerOn) {
    this.set((self) => {
        self.LastTimePowerOn = LastTimePowerOn;
    });
    }

    setAuthor_id(MaxTimePowerOn) {
    this.set((self) => {
        self.MaxTimePowerOn = MaxTimePowerOn;
    });
    }
  }