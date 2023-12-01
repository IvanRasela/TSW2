class PostModel extends Fronty.Model {

    constructor(switchsName, Private_UUID, Public_UUID, AliasUser, Descriptionswitchs, LastTimePowerOn,MaxTimePowerOn ) {
      super('PostModel'); //call super
      
      if (switchsName) {
        this.switchsName = switchsName;
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

      if (Descriptionswitchs) {
        this.Descriptionswitchs = Descriptionswitchs;
      }

      if (LastTimePowerOn) {
        this.LastTimePowerOn = LastTimePowerOn;
      }

      if (MaxTimePowerOn) {
        this.MaxTimePowerOn = MaxTimePowerOn;
      }

    }
  
    setTitle(switchsName) {
      this.set((self) => {
        self.switchsName = switchsName;
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

    setAuthor_id(Descriptionswitchs) {
    this.set((self) => {
        self.Descriptionswitchs = Descriptionswitchs;
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