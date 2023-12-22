class SwitchModel extends Fronty.Model {

    constructor(switchName, Private_UUID, Public_UUID, AliasUser, Descriptionswitch, LastTimePowerOn,MaxTimePowerOn ) {
      super('SwitchModel'); //call super
      
      if (switchName) {
        this.switchName = switchName;
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

      if (Descriptionswitch) {
        this.Descriptionswitch = Descriptionswitch;
      }

      if (LastTimePowerOn) {
        this.LastTimePowerOn = LastTimePowerOn;
      }

      if (MaxTimePowerOn) {
        this.MaxTimePowerOn = MaxTimePowerOn;
      }

    }
  
    setTitle(switchName) {
      this.set((self) => {
        self.switchName = switchName;
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

    setAuthor_id(Descriptionswitch) {
    this.set((self) => {
        self.Descriptionswitch = Descriptionswitch;
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