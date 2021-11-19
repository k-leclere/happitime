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
      <label>Quelle est votre BU/Service ? <span>*</span></label>
      <select v-model="service" required>
        <option>BU Immo</option>
        <option>BU Auto</option>
        <option>BU Emploi</option>
        <option>BU Obsèques</option>
        <option>BU Commerces et loisirs</option>
        <option>BU Alimentation</option>
        <option>BU Habitat</option>
        <option>BU Services et Collectivités</option>
        <option>BU Monétisation</option>
        <option>Marketing transverse</option>
        <option>DSI</option>
        <option>DAF/RH</option>
        <option>Studio</option>
        <option>Medialex</option>
        <option>RRR</option>
      </select>
      </section>
      <section>
      <label>Quelle est votre agence ? <span>*</span></label>
      <select v-model="agence" required>
        <option>Rennes (de noël ;-) )</option>
        <option>Caen (le 17 decembre)</option>
        <option>Nantes</option>
        <option>Angers</option>
        <option>Vannes</option>
      </select>
      </section>
      <input type="submit" value="S'inscrire"/>

    </form>
    <div v-else>
      Merci de vous être inscrit !<br/>
      Rendez-vous dans quelques jours afin de connaître l'heureux bénéficiaire de votre cadeau.
    </div>
  </div>
</template>

<script>

export default {
    name: 'Inscription',
    methods: {
      async inscription() {
        
        const { data, error } = await this.$supabase
          .from('noel')
          .insert([
            { agence: this.agence, service: this.service, nom: this.nom, prenom: this.prenom },
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
            isSuscribed: false,
            nom: '',
            prenom: '',
            service: '',
            agence: '',
        };
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
</style>
