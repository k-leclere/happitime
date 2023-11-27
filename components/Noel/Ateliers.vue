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
      <h3>Film de noël</h3>
      <h5>(une sélection de film vous sera proposée utltérieurement)</h5>
      <section>
        <label>lundi 18 décembre (18h30-20h)</label>
        <div class="radio">
          <input type="radio" v-model="film" name="film" value="1" />Oui
          <input type="radio" v-model="film" name="film" value="0" />Non
        </div>
      </section>
      <h3>Ateliers Déco</h3>
      <h5>(20 personnes max par atelier)</h5>
      <section v-if="compteur_date1<maxParticipants">
        <label>lundi 4 décembre (12h30-13h30)</label>
        <div class="radio">
          <input type="radio" v-model="date1" name="date1" value="1" />Oui
          <input type="radio" v-model="date1" name="date1" value="0" />Non
        </div>
      </section>
      <section v-if="compteur_date2<maxParticipants">
        <label>mercredi 6 décembre (12h30-13h30)</label>
        <div class="radio">
          <input type="radio" v-model="date2" name="date2" value="1" />Oui
          <input type="radio" v-model="date2" name="date2" value="0" />Non
        </div>
      </section>
      <section v-if="compteur_date3<maxParticipants">
        <label>vendredi 8 décembre (12h30-13h30)</label>
        <div class="radio">
          <input type="radio" v-model="date3" name="date3" value="1" />Oui
          <input type="radio" v-model="date3" name="date3" value="0" />Non
        </div>
      </section>
      <section v-if="compteur_date4<maxParticipants">
        <label>mardi 12 décembre (12h30-13h30)</label>
        <div class="radio">
          <input type="radio" v-model="date4" name="date4" value="1" />Oui
          <input type="radio" v-model="date4" name="date4" value="0" />Non
        </div>
      </section>
      <section v-if="compteur_date5<maxParticipants">
        <label>jeudi 14 décembre (12h30-13h30)</label>
        <div class="radio">
          <input type="radio" v-model="date5" name="date5" value="1" />Oui
          <input type="radio" v-model="date5" name="date5" value="0" />Non
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
            { nom: this.nom, prenom: this.prenom, date_04: this.date1, date_06: this.date2, date_08: this.date3, date_12: this.date4, date_14: this.date5, film: this.film },
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
            date1: '',
            date2: '',
            date3: '',
            date4: '',
            date5: '',
            film: '',
            compteur_date1: 0,
            compteur_date2: 0,
            compteur_date3: 0,
            compteur_date4: 0,
            compteur_date5: 0,
            compteur_film: 0,
        };
    },
    async mounted() {        

      const fetchCpt1 = this.$supabase.rpc('nb_inscrits_noel_atelier1').then(({ data }) => {
          this.compteur_date1 = data;
      });
      const fetchCpt2 = this.$supabase.rpc('nb_inscrits_noel_atelier2').then(({ data }) => {
          this.compteur_date2 = data;
      });
      const fetchCpt3 = this.$supabase.rpc('nb_inscrits_noel_atelier3').then(({ data }) => {
          this.compteur_date3 = data;
      });
      const fetchCpt4 = this.$supabase.rpc('nb_inscrits_noel_atelier4').then(({ data }) => {
          this.compteur_date4 = data;
      });
      const fetchCpt5 = this.$supabase.rpc('nb_inscrits_noel_atelier5').then(({ data }) => {
          this.compteur_date5 = data;
      });
      const fetchCptFilm = this.$supabase.rpc('nb_inscrits_noel_atelier_film').then(({ data }) => {
          this.compteur_film = data;
      });

      await Promise.allSettled([
        fetchCpt1,
        fetchCpt2,
        fetchCpt3,
        fetchCpt4,
        fetchCpt5,
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
