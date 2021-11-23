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
        <option>Affiouest</option>
        <option>BU Alimentation</option>
        <option>BU Auto</option>
        <option>BU Commerce & Loisirs</option>
        <option>BU Emploi</option>
        <option>BU Habitat</option>
        <option>BU Immo</option>
        <option>BU Obsèques</option>
        <option>BU Services & Collectivités</option>
        <option>BU télévente</option>
        <option>Caen</option>
        <option>DAF</option>
        <option>DEMO FM</option>
        <option>Direction Générale</option>
        <option>DRH</option>
        <option>DSI</option>
        <option>Europe Régie Ouest</option>
        <option>IST Lannion</option>
        <option>Marketing transverse</option>
        <option>MEDIALEX</option>
        <option>Monétisation numérique</option>
        <option>Océane Questember</option>
        <option>Pole projets et méthodes</option>
        <option>Precontact</option>
        <option>Radio Hit West</option>
        <option>Service informatique</option>
        <option>Skillapp</option>
        <option>Studio/ Pole technique</option>
        <option>COCKTAIL FM</option>
      </select>
      </section>
      <section>
      <label>Quelle est votre agence ? <span>*</span></label>
      <select v-model="agence" required>
        <option>ALENCON</option>
        <option>ANGERS</option>
        <option>AUCH</option>
        <option>BESANCON</option>
        <option>BIARRITZ</option>
        <option>BLOIS CEDEX</option>
        <option>BOE</option>
        <option>BORDEAUX</option>
        <option>BRESSUIRE</option>
        <option>BREST (rue Dupleix)</option>
        <option>BREST (rue des Neireides)</option>
        <option>BRIVE LA GAILLARDE</option>
        <option>CABESTANY</option>
        <option>CHAMPAGNE AU MONT D OR</option>
        <option>CLERMONT-FERRAND</option>
        <option>DIJON</option>
        <option>DINAN</option>
        <option>EPRON</option>
        <option>EVREUX</option>
        <option>FORBACH</option>
        <option>LA GARDE</option>
        <option>LA ROCHE SUR YON</option>
        <option>LA ROCHELLE</option>
        <option>LANGUEUX</option>
        <option>LANNION (rue de Viarmes)</option>
        <option>LANNION (rue St Marc)</option>
        <option>LAVAL (rue de la paix)</option>
        <option>LAVAL (av. R. Buron)</option>
        <option>LE HAVRE</option>
        <option>LE MANS</option>
        <option>LILLE</option>
        <option>LIMOGES</option>
        <option>LISIEUX</option>
        <option>LORIENT</option>
        <option>LUCE</option>
        <option>MARSEILLE</option>
        <option>METZ</option>
        <option>MONTAUBAN</option>
        <option>MONTPELLIER</option>
        <option>MULHOUSE</option>
        <option>NANCY</option>
        <option>NANTES</option>
        <option>NICE</option>
        <option>NIORT</option>
        <option>PARIS 9</option>
        <option>PAU</option>
        <option>PLOERMEL</option>
        <option>PONTIVY</option>
        <option>QUESTEMBERT</option>
        <option>QUIMPER (bd Dupleix)</option>
        <option>QUIMPER (rue E. Perchec)</option>
        <option>RENNES</option>
        <option>ROUEN</option>
        <option>SAINT GEORGES DES COTEAUX</option>
        <option>SAINT JULIEN EN GENEVOIS</option>
        <option>SAINT LEONARD</option>
        <option>SAINT LO</option>
        <option>SAINT OMER</option>
        <option>SAINT QUENTIN</option>
        <option>SAUMUR</option>
        <option>ST HERBLAIN</option>
        <option>ST MALO (av. J. Jaures)</option>
        <option>ST MALO (bd des Talards)</option>
        <option>ST NAZAIRE</option>
        <option>STRASBOURG</option>
        <option>TOULOUSE</option>
        <option>TOURS</option>
        <option>VALENCE</option>
        <option>VANNES (6 bd de la Paix)</option>
        <option>VANNES (4 bd de la Paix)</option>
        <option>VITRE</option>
      </select>
      </section>
      <input type="submit" value="S'inscrire"/>
    <h3 v-if="compteur>0">Déjà {{compteur}} inscrits.</h3>

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
            compteur: 0,
        };
    },
    async mounted() {
      const { data } = await this.$supabase
        .rpc('nb_inscrits');

        if(data) {
          this.compteur = data;
        }
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
