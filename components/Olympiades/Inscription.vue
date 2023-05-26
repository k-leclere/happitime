<!-- Please remove this file from your project -->
<template>
  <div class="inscription">
    <form v-if="!isSuscribed" @submit.prevent="inscription()">
      
      <section>
        <label>Capitaine d'équipe <span>*</span></label>
        <input 
          v-model="capitaine"
          placeholder="Nom Prénom"
          type="text"
          required
        />
      </section>
      <section>
      <label>Equipier n°1 <span>*</span></label>
      <input 
        v-model="equipier1"
          placeholder="Nom Prénom"
        type="text"
        required
      />
      </section>
      <section>
      <label>Equipier n°2 <span>*</span></label>
      <input 
        v-model="equipier2"
          placeholder="Nom Prénom"
        type="text"
        required
      />
      </section>
      <section>
      <label>Equipier n°3 <span>*</span></label>
      <input 
        v-model="equipier3"
          placeholder="Nom Prénom"
        type="text"
        required
      />
      </section>
      <section>
      <label>Equipier n°4 <span>*</span></label>
      <input 
        v-model="equipier4"
          placeholder="Nom Prénom"
        type="text"
        required
      />
      </section>
      <section>
      <label>Equipier n°5 <span>*</span></label>
      <input 
        v-model="equipier5"
          placeholder="Nom Prénom"
        type="text"
        required
      />
      </section>
      <section>
      <label>Equipier n°6</label>
      <input 
        v-model="equipier6"
          placeholder="Nom Prénom"
        type="text"
      />
      </section>
      <section>
      <label>Equipier n°7</label>
      <input 
        v-model="equipier7"
          placeholder="Nom Prénom"
        type="text"
      />
      </section>
      <input type="submit" value="S'inscrire"/>
    <h3 v-if="compteur>5">Déjà {{compteur}} équipes.</h3>

    </form>
    <div v-else>
      Votre inscription a bien été prise en compte. Rendez-vous le 29 mai prochain !<br/>
    </div>
  </div>
</template>

<script>

export default {
    name: 'Inscription',
    methods: {
      async inscription() {
        
        this.setCompteur();

        const { data, error } = await this.$supabase
          .from('olympiades')
          .insert([
            { capitaine: this.capitaine, equipier1: this.equipier1, equipier2: this.equipier2, equipier3: this.equipier3, equipier4: this.equipier4, equipier5: this.equipier5, equipier6: this.equipier6, equipier7: this.equipier7 },
          ]);
        
        if(!error) {
          this.isSuscribed = true;
        }
        else {
          console.log('ERREUR', error)
        }
      },

      async setCompteur() {
        const { data } = await this.$supabase
          .rpc('nb_inscrits_olympiades');

          if(data) {
            this.compteur = data;
          }
        }
    },
    data() {
        return {
            isSuscribed: false,
            capitaine: '',
            equipier1: '',
            equipier2: '',
            equipier3: '',
            equipier4: '',
            equipier5: '',
            equipier6: '',
            equipier7: '',
            compteur: 0,
        };
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
section {
  padding: 10px 0;
}
</style>
