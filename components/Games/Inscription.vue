<!-- Please remove this file from your project -->
<template>
  <div class="inscription">
    <form v-if="!isSuscribed" @submit.prevent="inscription()">
      <section>
        <label>Votre nom <span>*</span></label>
        <input 
          v-model="nom"
          type="text"
          required
        />
      </section>
      <section>
      <label>Votre prénom <span>*</span></label>
      <input 
        v-model="prenom"
        type="text"
        required
      />
      </section>
      <section>
        <label>Pouvez-vous apporter des jeux ?</label>
        <div class="radio">
          <input type="radio" name="jeux" value="1" />Oui
          <input type="radio" name="jeux" value="0" />Non
        </div>
      </section>
      <section>
        <label>Souhaitez-vous être Game Master ?</label>
        <div class="radio">
          <input type="radio" name="master" value="1" />Oui
          <input type="radio" name="master" value="0" />Non
        </div>
      </section>
      <input type="submit" value="S'inscrire"/>
    <h3 v-if="compteur>0">Déjà {{compteur}} inscrits.</h3>

    </form>
    <div v-else>
      Votre inscription a bien été prise en compte. Rendez-vous le 30 mars prochain !<br/>
    </div>
  </div>
</template>

<script>

export default {
    name: 'Inscription',
    methods: {
      async inscription() {
        
        this.setCompteur();

        if(this.isFull) {
          const { data, error } = await this.$supabase
            .from('games')
            .insert([
              { nom: this.nom, prenom: this.prenom, jeux: this.jeux, master: this.master },
            ]);
          
          if(!error) {
            this.isSuscribed = true;
          }
          else {
            console.log('ERREUR', error)
          }
        }
      }
    },
    computed: {
      isFull() {
        return this.nbMax && this.compteur<this.nbMax;
      }
    },
    props: {
        /**
         * Détermine le nb max participants
         */
        nbMax: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            isSuscribed: false,
            nom: '',
            prenom: '',
            service: '',
            agence: '',
            compteur: 0,
        };
    },
    methods: {

      async setCompteur() {
        const { data } = await this.$supabase
          .rpc('nb_inscrits_games');

          if(data) {
            this.compteur = data;
          }
        }
    },
    async mounted() {
      this.setCompteur();
    }
};
</script>


<style scoped>
.inscription {
  padding: 3rem 0;
}

span {
  color: #e2001a;
}

input,select {
  display: block;
  margin: 7px auto;
  width: 320px;
  padding: 5px 0;
  text-align: center;
}

input[type=submit] {
  font-size: 14px;
  font-weight: 600;
  height: 34px;
  background: #e2001a;
  color: #fff;
  border: 0;
  padding: 0 25px;
  margin: 1.68em auto;
  border-radius: 3px;
  transition: all .4s;
  width: 120px;
}
.radio > * {
  display: inline-block;
  width: 30px;
}
</style>
