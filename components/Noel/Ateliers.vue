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
      <h3>Film de noël 🎥</h3>
      <h5>(une sélection de film vous sera proposée ultérieurement)</h5>
      <section>
        <label>lundi 18 décembre (18h30-20h)</label>
        <div class="radio">
          <input type="radio" v-model="film" name="film" value="1" />Oui
          <input type="radio" v-model="film" name="film" value="0" />Non
        </div>
      </section>
      
      <input type="submit" value="S'inscrire"/>
    </form>
    <div v-else>
      Merci de vous être inscrit !<br/>
    </div>
  </div>
</template>

<script>

export default {
    name: 'Ateliers',
    methods: {
      async inscription() {
        
        const { data, error } = await this.$supabase
          .from('noel_ateliers')
          .insert([
            { nom: this.nom, prenom: this.prenom, film: this.film },
          ]);
        
        if(!error) {
          this.isSuscribed = true;
        }
        else {
          console.log('ERREUR', error)
        }
      }
    },
    data() {
        return {
            maxParticipants: 20,
            isSuscribed: false,
            nom: '',
            prenom: '',
            film: '',
            compteur_film: 0,
        };
    },
    async mounted() {        

      const fetchCptFilm = this.$supabase.rpc('nb_inscrits_noel_atelier_film').then(({ data }) => {
          this.compteur_film = data;
      });

      await Promise.allSettled([
        fetchCptFilm,
      ]);
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
section {
  padding: 10px 0;
}
textarea {
  display: block;
  margin: 0 auto;
  width: 320px;
  height: 150px;
}
</style>
