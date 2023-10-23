<template>
  <div>
    <table>
      <thead>
        <tr>
          <th width="200">Nom</th>
          <th width="10">Compteur</th>
          <th width="200">Email</th>
          <th>Mission</th>
          <th width="300">Cible</th>
          <th width="50">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="player in players" :key="player.id" :class="{ killed: player.killed_at }">
          <td>
            <input v-model="player.nom" @input="edite(player)" />
          </td>
          <td>
            {{ player.cpt }}
          </td>
          <td>
            <input v-model="player.email" @input="edite(player)" />
          </td>
          <td>
            <input v-model="player.mission" @input="edite(player)" />
          </td>
          <td v-if="!player.killed_at">
            🎯 {{ player.cible }}<br/>
            📜 {{ getMission(player.cible) }}
            <select v-model="player.cible" @input="edite(player)">
              <option v-for="p in [...playersDispo, { nom: player.cible }]" :key="p.id" :value="p.nom"
                v-if="p.nom !== player.nom">
                {{ p.nom }}
              </option>
            </select>
          </td>
          <td v-else>
            🎯 {{ player.cible }}<br />
            🔪 {{ player.killed_by }}<br />
            📅 {{ formaterDate(player.killed_at) }}
          </td>
          <td>
            <template v-if="!player.killed_at">
              <button @click="confirmKill(player)">Tuer</button>
            </template>
            <template v-else>
              <button @click="ressuscite(player)">Ressuscite</button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal" v-if="showModal">
      <div class="modal-content">
        <h3>Confirmer le meurtre</h3>
        <label for="killer">Qui a tué {{ currentPlayer.nom }} ?</label>
        <select v-model="selectedKiller" id="killer">
          <option v-for="p in potentialKillers" :key="p.id">{{ p.nom }}</option>
        </select>
        <button @click="showModal = false">Annuler</button>
        <button @click="executeKill">Confirmer</button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ListPlayers',
  created() {
    this.loadPlayers();
  },
  methods: {
    async loadPlayers() {
      // Utilisez le module Supabase pour récupérer les données depuis la table "killerparty"
      const { data, error } = await this.$supabase.from('killerparty')
        .select('id, nom, email, mission, cible, cpt, killed_at, killed_by')
        .order('killed_at', { nullsFirst: true })
        .order('nom');

      if (error) {
        console.error('Erreur lors de la récupération des données de killerparty', error);
      } else {
        this.tabPlayers = data;
      }
    },

    async edite(player) {
      // Mettre à jour les données dans la base de données Supabase
      const { data, error } = await this.$supabase.from("killerparty").upsert([player]);
      if (error) {
        console.error("Erreur lors de la mise à jour du joueur : ", error);
      }
    },
    confirmKill(player) {
      this.potentialKillers = this.players.filter(
        (p) => (p.cible === player.nom && !p.killed_at) || p.nom === player.cible
      );
      this.currentPlayer = player;
      this.showModal = true;
    },
    getMission(nom) {
      const player = this.players.find(
        (p) => p.nom === nom
      );
      return player ? player.mission : '';
    },
    async executeKill() {
      if (this.selectedKiller) {
        const killer = this.players.find((p) => p.nom === this.selectedKiller);
        const currentDate = new Date();
        if (killer && this.currentPlayer) {

          const updatedKiller = {
            ...killer,
            cpt: killer.cpt + this.currentPlayer.cpt + 1
          };

          const { dataKiller, errorKiller } = await this.$supabase.from("killerparty").upsert([updatedKiller]);

          const updatedPlayer = {
            ...this.currentPlayer,
            killed_at: currentDate,
            killed_by: killer.nom,
          };
          const { dataPlayer, errorPlayer } = await this.$supabase.from("killerparty").upsert([updatedPlayer]);

          if (errorKiller || errorPlayer) {
            console.error("Erreur lors de la mise à jour du tueur : ", errorKiller, errorPlayer);
          }
        }
        this.showModal = false;
        this.loadPlayers();
      }
    },
    async ressuscite(player) {
      const killer = this.players.find((p) => p.nom === player.killed_by);
      const updatedKiller = {
        ...killer,
        cpt: killer.cpt - player.cpt - 1
      };

      const { dataKiller, errorKiller } = await this.$supabase.from("killerparty").upsert([updatedKiller]);


      const updatedPlayer = {
        ...player,
        killed_at: null,
        killed_by: null,
      };
      const { dataPlayer, errorPlayer } = await this.$supabase.from("killerparty").upsert([updatedPlayer]);

      if (errorPlayer || errorKiller) {
        console.error("Erreur lors de la mise à jour du joueur : ", error);
      }
      this.loadPlayers();
    },
    formaterDate(dateString) {
      const date = new Date(dateString);

      // Formatez la date au format "dd/mm/yyyy hh:mm:ss"
      const options = {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      };

      return date.toLocaleString('fr-FR', options);
    }
  },
  mounted() {
    this.intervalId = setInterval(() => {
      this.loadPlayers();
    }, 300000); // 5 minutes en millisecondes
  },
  beforeDestroy() {
    clearInterval(this.intervalId); // Assurez-vous de nettoyer la minuterie lorsque le composant est détruit
  },
  computed: {
    players() {
      return this.tabPlayers;
    },
    playersDispo() {
      return this.tabPlayers.filter(player => (!this.playerTargeted.includes(player.nom) && !player.killed_at));
    },
    playerTargeted() {
      return this.tabPlayers.reduce((acc, objet) => {
        acc.push(objet.cible);
        return acc;
      }, []);
    }
  },
  data() {
    return {
      intervalId: null,
      tabPlayers: [],
      showModal: false,
      selectedKiller: null,
      potentialKillers: [],
      currentPlayer: null,
    };
  },
};
</script>


<style scoped>
/* Style du tableau */
table {
  width: 100%;
  border-collapse: collapse;
}

table,
th,
td {
  border: 1px solid #ddd;
}

th,
td {
  padding: 8px;
  text-align: left;
}

.killed {
  background: #AAA;
}
.killed input {
  background: #AAA;
  border: none;
}

/* Style des boutons d'édition et de confirmation de meurtre */
button {
  background-color: #2a4a59;
  color: #fff;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

input {
  width: 100%;
}

/* Style de la modal de confirmation */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  width: 300px;
  text-align: center;
}

/* Style du sélecteur */
select {
  width: 100%;
  padding: 5px;
}

/* Style de l'entête du tableau */
thead {
  background-color: #2a4a59;
  color: #fff;
}
</style>
