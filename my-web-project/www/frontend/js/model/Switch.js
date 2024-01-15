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
        this.LastTimePowerOn = LastTimePowerOn;
      }

      if (MaxTimePowerOn) {
        this.MaxTimePowerOn = MaxTimePowerOn;
      }
      this.State=false;
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

    setState(State) {
      this.set((self) => {
          self.State = State;
      });
    }

    changeState(State){
      const timeLast = self.LastTimePowerOn.split(':');//"00:00"
      //en minutos
      const totalLast = 0;
      const totalMax = 0;
      if (timeLast.length === 2) {
          const hours = parseInt(timeLast[0], 10);
          const minutes = parseInt(timeLast[1], 10);
          const totalLast = hours * 60 + minutes;
      }

      const timeMax = self.MaxTimePowerOn.split(':');
      if (timeMax.length === 2) {
        const hours = parseInt(timeMax[0], 10);
        const minutes = parseInt(timeMax[1], 10);
        const totalMax = hours * 60 + minutes;
      }
      if (totalLast>totalMax) {
        this.set((self) => {
          self.State = false;
        });
      }
      if (totalLast>0 && totalLast<totalMax) {
        this.set((self) => {
          self.State = true;
        });
      }
      if (totalLast===0) {
        this.set((self) => {
          self.State = false;
        });
      }
    }
  }